<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsController extends Controller
{
    public function index()
    {
        $stats = [
            'todaySales' => $this->getTodaySales(),
            'todayOrders' => $this->getTodayOrders(),
            'monthlyRevenue' => $this->getMonthlyRevenue(),
            'yearlyRevenue' => $this->getYearlyRevenue(),
            'totalProducts' => Product::count(),
            'totalCustomers' => User::whereHas('role', fn ($q) => $q->where('slug', 'customer'))->count(),
        ];

        $salesChart = $this->getSalesChartData();
        $ordersChart = $this->getOrdersChartData();
        $revenueChart = $this->getRevenueChartData();

        return view('admin.analytics.index', compact('stats', 'salesChart', 'ordersChart', 'revenueChart'));
    }

    public function dailySales(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        $orders = Order::whereDate('ordered_at', $date)
            ->with(['items.product', 'user'])
            ->orderBy('ordered_at', 'desc')
            ->get();

        $totalSales = $orders->sum('total_price');
        $orderCount = $orders->count();

        $hourlyData = Order::whereDate('ordered_at', $date)
            ->select(DB::raw('HOUR(ordered_at) as hour'), DB::raw('COUNT(*) as count'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('admin.analytics.daily-sales', compact('orders', 'date', 'totalSales', 'orderCount', 'hourlyData'));
    }

    public function monthlyRevenue(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        
        $monthlyData = collect(range(1, 12))->map(function ($month) use ($year) {
            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            $revenue = Order::whereBetween('ordered_at', [$start, $end])->sum('total_price');
            $orders = Order::whereBetween('ordered_at', [$start, $end])->count();

            return [
                'month' => $start->format('F'),
                'month_num' => $month,
                'revenue' => $revenue,
                'orders' => $orders,
            ];
        });

        $totalRevenue = $monthlyData->sum('revenue');
        $totalOrders = $monthlyData->sum('orders');

        return view('admin.analytics.monthly-revenue', compact('monthlyData', 'year', 'totalRevenue', 'totalOrders'));
    }

    public function yearlyRevenue(Request $request)
    {
        $startYear = $request->get('start_year', Carbon::now()->subYears(4)->year);
        $endYear = $request->get('end_year', Carbon::now()->year);

        $yearlyData = collect(range($startYear, $endYear))->map(function ($year) {
            $start = Carbon::create($year, 1, 1)->startOfYear();
            $end = $start->copy()->endOfYear();

            $revenue = Order::whereBetween('ordered_at', [$start, $end])->sum('total_price');
            $orders = Order::whereBetween('ordered_at', [$start, $end])->count();

            return [
                'year' => $year,
                'revenue' => $revenue,
                'orders' => $orders,
            ];
        });

        $totalRevenue = $yearlyData->sum('revenue');
        $totalOrders = $yearlyData->sum('orders');

        return view('admin.analytics.yearly-revenue', compact('yearlyData', 'startYear', 'endYear', 'totalRevenue', 'totalOrders'));
    }

    public function topProducts(Request $request)
    {
        $limit = $request->get('limit', 10);
        $period = $request->get('period', 'all');

        $query = OrderItem::query()
            ->select('product_id', 'product_name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold');

        if ($period !== 'all') {
            $startDate = match ($period) {
                'week' => Carbon::now()->subWeek(),
                'month' => Carbon::now()->subMonth(),
                'year' => Carbon::now()->subYear(),
                default => Carbon::minValue(),
            };
            $query->whereHas('order', fn ($q) => $q->where('ordered_at', '>=', $startDate));
        }

        $products = $query->limit($limit)->get();

        return view('admin.analytics.top-products', compact('products', 'limit', 'period'));
    }

    public function inventoryReport(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Product::with('category');

        $products = match ($status) {
            'in_stock' => $query->where('stock_quantity', '>', 0),
            'low_stock' => $query->whereBetween('stock_quantity', [1, DB::raw('low_stock_threshold')]),
            'out_of_stock' => $query->where('stock_quantity', '<=', 0),
            default => $query,
        };

        $products = $query->orderBy('stock_quantity')->get();

        $stats = [
            'total' => Product::count(),
            'inStock' => Product::where('stock_quantity', '>', 0)->count(),
            'lowStock' => Product::whereBetween('stock_quantity', [1, DB::raw('low_stock_threshold')])->count(),
            'outOfStock' => Product::where('stock_quantity', '<=', 0)->count(),
        ];

        return view('admin.analytics.inventory', compact('products', 'status', 'stats'));
    }

    public function customerAnalytics(Request $request)
    {
        $period = $request->get('period', 'all');

        $query = User::query()
            ->select('users.*', 
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total_price) as total_spent'),
                DB::raw('MAX(orders.ordered_at) as last_order_date')
            )
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->whereHas('role', fn ($q) => $q->where('slug', 'customer'))
            ->groupBy('users.id');

        if ($period !== 'all') {
            $startDate = match ($period) {
                'week' => Carbon::now()->subWeek(),
                'month' => Carbon::now()->subMonth(),
                'year' => Carbon::now()->subYear(),
                default => Carbon::minValue(),
            };
            $query->where('orders.ordered_at', '>=', $startDate);
        }

        $customers = $query->orderByDesc('total_spent')->get();

        $stats = [
            'totalCustomers' => User::whereHas('role', fn ($q) => $q->where('slug', 'customer'))->count(),
            'activeCustomers' => User::whereHas('role', fn ($q) => $q->where('slug', 'customer'))
                ->whereHas('orders', fn ($q) => $q->where('ordered_at', '>=', Carbon::now()->subMonth()))
                ->count(),
            'totalRevenue' => Order::sum('total_price'),
            'averageOrderValue' => Order::avg('total_price') ?? 0,
        ];

        return view('admin.analytics.customers', compact('customers', 'period', 'stats'));
    }

    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'dashboard');
        $data = $this->getExportData($type);

        $pdf = Pdf::loadView("admin.analytics.exports.pdf.{$type}", $data);

        return $pdf->download("sudagold-{$type}-report-".Carbon::now()->format('Y-m-d').'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $type = $request->get('type', 'dashboard');
        $data = $this->getExportData($type);

        $filename = "sudagold-{$type}-report-".Carbon::now()->format('Y-m-d').'.csv';
        
        $csvData = $this->formatDataForCsv($type, $data);

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    private function getTodaySales(): float
    {
        return Order::whereDate('ordered_at', Carbon::today())->sum('total_price');
    }

    private function getTodayOrders(): int
    {
        return Order::whereDate('ordered_at', Carbon::today())->count();
    }

    private function getMonthlyRevenue(): float
    {
        return Order::whereMonth('ordered_at', Carbon::now()->month)
            ->whereYear('ordered_at', Carbon::now()->year)
            ->sum('total_price');
    }

    private function getYearlyRevenue(): float
    {
        return Order::whereYear('ordered_at', Carbon::now()->year)->sum('total_price');
    }

    private function getSalesChartData(): array
    {
        $days = 7;
        $data = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('M d');
            $data[] = Order::whereDate('ordered_at', $date)->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getOrdersChartData(): array
    {
        $days = 7;
        $data = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('M d');
            $data[] = Order::whereDate('ordered_at', $date)->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getRevenueChartData(): array
    {
        $days = 7;
        $data = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('M d');
            $data[] = Order::whereDate('ordered_at', $date)->sum('total_price');
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getExportData(string $type): array
    {
        return match ($type) {
            'dashboard' => [
                'title' => 'Dashboard Report',
                'stats' => [
                    'todaySales' => $this->getTodaySales(),
                    'todayOrders' => $this->getTodayOrders(),
                    'monthlyRevenue' => $this->getMonthlyRevenue(),
                    'yearlyRevenue' => $this->getYearlyRevenue(),
                    'totalProducts' => Product::count(),
                    'totalCustomers' => User::whereHas('role', fn ($q) => $q->where('slug', 'customer'))->count(),
                ],
            ],
            'daily-sales' => [
                'title' => 'Daily Sales Report',
                'orders' => Order::whereDate('ordered_at', Carbon::today())->get(),
                'date' => Carbon::today()->format('Y-m-d'),
            ],
            'monthly-revenue' => [
                'title' => 'Monthly Revenue Report',
                'data' => collect(range(1, 12))->map(function ($month) {
                    $start = Carbon::create(Carbon::now()->year, $month, 1)->startOfMonth();
                    return [
                        'month' => $start->format('F'),
                        'revenue' => Order::whereMonth('ordered_at', $month)->whereYear('ordered_at', Carbon::now()->year)->sum('total_price'),
                        'orders' => Order::whereMonth('ordered_at', $month)->whereYear('ordered_at', Carbon::now()->year)->count(),
                    ];
                }),
            ],
            'top-products' => [
                'title' => 'Top Products Report',
                'products' => OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
                    ->groupBy('product_id', 'product_name')
                    ->orderByDesc('total_sold')
                    ->limit(20)
                    ->get(),
            ],
            'inventory' => [
                'title' => 'Inventory Report',
                'products' => Product::with('category')->orderBy('stock_quantity')->get(),
            ],
            'customers' => [
                'title' => 'Customer Analytics Report',
                'customers' => User::select('users.*', 
                    DB::raw('COUNT(orders.id) as total_orders'),
                    DB::raw('SUM(orders.total_price) as total_spent')
                )
                ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
                ->whereHas('role', fn ($q) => $q->where('slug', 'customer'))
                ->groupBy('users.id')
                ->orderByDesc('total_spent')
                ->get(),
            ],
            default => [],
        };
    }

    private function formatDataForCsv(string $type, array $data): string
    {
        $output = fopen('php://temp', 'r+');

        switch ($type) {
            case 'dashboard':
                fputcsv($output, ['Metric', 'Value']);
                foreach ($data['stats'] as $key => $value) {
                    fputcsv($output, [ucwords(str_replace('_', ' ', $key)), $value]);
                }
                break;
            case 'daily-sales':
                fputcsv($output, ['Order #', 'Customer', 'Total', 'Status', 'Date']);
                foreach ($data['orders'] as $order) {
                    fputcsv($output, [
                        $order->order_number,
                        $order->user?->full_name ?? 'Guest',
                        $order->total_price,
                        $order->order_status,
                        $order->ordered_at?->format('Y-m-d H:i'),
                    ]);
                }
                break;
            case 'monthly-revenue':
                fputcsv($output, ['Month', 'Revenue', 'Orders']);
                foreach ($data['data'] as $row) {
                    fputcsv($output, [$row['month'], $row['revenue'], $row['orders']]);
                }
                break;
            case 'top-products':
                fputcsv($output, ['Product', 'Total Sold', 'Revenue']);
                foreach ($data['products'] as $product) {
                    fputcsv($output, [$product->product_name, $product->total_sold, $product->total_revenue]);
                }
                break;
            case 'inventory':
                fputcsv($output, ['Product', 'SKU', 'Stock', 'Price', 'Status']);
                foreach ($data['products'] as $product) {
                    fputcsv($output, [
                        $product->name,
                        $product->sku,
                        $product->stock_quantity,
                        $product->price,
                        $product->stock_quantity <= 0 ? 'Out of Stock' : ($product->is_low_stock ? 'Low Stock' : 'In Stock'),
                    ]);
                }
                break;
            case 'customers':
                fputcsv($output, ['Customer', 'Email', 'Total Orders', 'Total Spent']);
                foreach ($data['customers'] as $customer) {
                    fputcsv($output, [
                        $customer->full_name,
                        $customer->email,
                        $customer->total_orders ?? 0,
                        $customer->total_spent ?? 0,
                    ]);
                }
                break;
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}
