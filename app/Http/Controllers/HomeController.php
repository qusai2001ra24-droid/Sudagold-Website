<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->with('category')
            ->limit(8)
            ->get();

        return view('home', compact('featuredProducts'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function catalog(Request $request)
    {
        $query = Product::active()->with('category');

        if ($request->category && $request->category !== 'all') {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $sortBy = $request->sort ?? 'featured';

        switch ($sortBy) {
            case 'price-low':
                $query->orderBy('price', 'asc')->orderBy('name', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc')->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc')->orderBy('name', 'asc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderByDesc('is_featured')->orderBy('name', 'asc');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('catalog', compact('products', 'categories'));
    }

    public function product($id)
    {
        $product = Product::with(['category'])->findOrFail($id);
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('category')
            ->limit(4)
            ->get();

        return view('product', compact('product', 'relatedProducts'));
    }

    public function cart()
    {
        return view('cart');
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function dashboard()
    {
        $user = auth()->user();
        
        $orders = Order::where('user_id', $user->id)
            ->with(['items'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)->sum('total_price');
        $pendingOrders = Order::where('user_id', $user->id)->where('order_status', 'pending')->count();

        return view('dashboard', compact('orders', 'totalOrders', 'totalSpent', 'pendingOrders'));
    }

    public function orderConfirmation(int $orderId)
    {
        $user = auth()->user();
        $order = Order::with(['items', 'payment'])
            ->where('user_id', $user->id)
            ->findOrFail($orderId);

        return view('order-confirmation', compact('order'));
    }
}
