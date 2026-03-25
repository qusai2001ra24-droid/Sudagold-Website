<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_orders,edit_orders']);
    }

    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $orders = Order::with('user')
            ->when($search, fn($q) => $q->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('user', fn($u) => $u->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")))
            ->when($status, fn($q) => $q->where('order_status', $status))
            ->when($dateFrom, fn($q) => $q->whereDate('ordered_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('ordered_at', '<=', $dateTo))
            ->orderBy('ordered_at', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders', 'search', 'status', 'dateFrom', 'dateTo'));
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'user', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
        ]);

        $previousStatus = $order->order_status;
        $newStatus = $validated['order_status'];

        if ($newStatus === 'cancelled' && in_array($previousStatus, ['pending', 'processing'])) {
            foreach ($order->items as $item) {
                $product = $item->product;
                $oldQuantity = $product->stock_quantity;
                $newQuantity = $oldQuantity + $item->quantity;

                $product->update(['stock_quantity' => $newQuantity]);

                InventoryMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'movement_type' => 'cancellation',
                    'quantity_change' => $item->quantity,
                    'quantity_before' => $oldQuantity,
                    'quantity_after' => $newQuantity,
                    'reason' => 'Order #' . $order->order_number . ' cancelled',
                ]);
            }
        }

        $order->update(['order_status' => $newStatus]);

        if ($newStatus === 'delivered' && !$order->delivered_at) {
            $order->update(['delivered_at' => now()]);
        }

        if ($newStatus === 'shipped' && !$order->shipped_at) {
            $order->update(['shipped_at' => now()]);
        }

        return redirect()->back()->with('success', 'Order status updated.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        if ($order->order_status !== 'pending' && $order->order_status !== 'cancelled') {
            return redirect()->back()->with('error', 'Cannot delete active orders. Cancel first.');
        }

        $order->items()->delete();
        $order->payment()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }
}
