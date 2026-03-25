<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_reports']);
    }

    public function index(Request $request): View
    {
        $period = $request->get('period', 'month');
        
        $startDate = match($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'quarter' => now()->startOfQuarter(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $totalRevenue = Order::where('order_status', '!=', 'cancelled')
            ->where('ordered_at', '>=', $startDate)
            ->sum('total_price');

        $totalOrders = Order::where('ordered_at', '>=', $startDate)->count();
        
        $dailySales = Order::where('order_status', '!=', 'cancelled')
            ->whereDate('ordered_at', '>=', $startDate)
            ->selectRaw('DATE(ordered_at) as date, SUM(total_price) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthlyRevenue = Order::where('order_status', '!=', 'cancelled')
            ->where('ordered_at', '>=', now()->startOfYear())
            ->selectRaw('MONTH(ordered_at) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $totalCustomers = User::whereHas('orders')->count();
        
        $newCustomers = User::where('created_at', '>=', $startDate)->count();

        $bestSellingProducts = OrderItem::whereHas('order', function ($q) use ($startDate) {
            $q->where('ordered_at', '>=', $startDate)->where('order_status', '!=', 'cancelled');
        })
        ->selectRaw('product_id, SUM(quantity) as total_sold, SUM(subtotal) as total_revenue')
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->limit(10)
        ->get();

        $ordersByStatus = Order::where('ordered_at', '>=', $startDate)
            ->selectRaw('order_status, COUNT(*) as count')
            ->groupBy('order_status')
            ->pluck('count', 'order_status');

        return view('admin.reports.index', compact(
            'totalRevenue', 
            'totalOrders', 
            'dailySales',
            'monthlyRevenue',
            'totalCustomers',
            'newCustomers',
            'bestSellingProducts',
            'ordersByStatus',
            'period'
        ));
    }

    public function export(Request $request)
    {
        return back()->with('info', 'Export functionality coming soon.');
    }
}
