<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $cart = Cart::with(['items.product'])->where('user_id', $user->id)->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart');
        }

        return view('checkout');
    }

    public function process(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_first_name' => ['required', 'string', 'max:255'],
            'shipping_last_name' => ['required', 'string', 'max:255'],
            'shipping_email' => ['required', 'email'],
            'shipping_phone' => ['required', 'string', 'max:50'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_state' => ['required', 'string', 'max:255'],
            'shipping_zip_code' => ['required', 'string', 'max:20'],
            'shipping_country' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'in:credit_card,debit_card,paypal,bank_transfer,cash_on_delivery'],
            'same_as_billing' => ['nullable', 'boolean'],
            'billing_first_name' => ['required_if:same_as_billing,0', 'nullable', 'string', 'max:255'],
            'billing_last_name' => ['required_if:same_as_billing,0', 'nullable', 'string', 'max:255'],
            'billing_address' => ['required_if:same_as_billing,0', 'nullable', 'string', 'max:500'],
            'billing_city' => ['required_if:same_as_billing,0', 'nullable', 'string', 'max:255'],
            'billing_state' => ['required_if:same_as_billing,0', 'nullable', 'string', 'max:255'],
            'billing_zip_code' => ['required_if:same_as_billing,0', 'nullable', 'string', 'max:20'],
            'billing_country' => ['required_if:same_as_billing,0', 'nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = auth()->user();
        
        $cart = Cart::with(['items.product'])->where('user_id', $user->id)->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        foreach ($cart->items as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return redirect()->back()->with('error', 'Insufficient stock for ' . $item->product->name);
            }
        }

        $subtotal = $cart->items->sum(fn ($item) => $item->price * $item->quantity);
        $taxAmount = round($subtotal * 0.08, 2);
        $shippingAmount = 0;
        $totalPrice = $subtotal + $taxAmount + $shippingAmount;

        try {
            DB::beginTransaction();

            $orderNumber = Order::generateOrderNumber();

            $billingFirstName = $request->boolean('same_as_billing') ? $validated['shipping_first_name'] : $validated['billing_first_name'];
            $billingLastName = $request->boolean('same_as_billing') ? $validated['shipping_last_name'] : $validated['billing_last_name'];
            $billingAddress = $request->boolean('same_as_billing') ? $validated['shipping_address'] : $validated['billing_address'];
            $billingCity = $request->boolean('same_as_billing') ? $validated['shipping_city'] : $validated['billing_city'];
            $billingState = $request->boolean('same_as_billing') ? $validated['shipping_state'] : $validated['billing_state'];
            $billingZipCode = $request->boolean('same_as_billing') ? $validated['shipping_zip_code'] : $validated['billing_zip_code'];
            $billingCountry = $request->boolean('same_as_billing') ? $validated['shipping_country'] : $validated['billing_country'];

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => 0,
                'total_price' => $totalPrice,
                'payment_method' => $validated['payment_method'],
                'order_status' => 'pending',
                'shipping_first_name' => $validated['shipping_first_name'],
                'shipping_last_name' => $validated['shipping_last_name'],
                'shipping_email' => $validated['shipping_email'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_zip_code' => $validated['shipping_zip_code'],
                'shipping_country' => $validated['shipping_country'],
                'billing_first_name' => $billingFirstName,
                'billing_last_name' => $billingLastName,
                'billing_address' => $billingAddress,
                'billing_city' => $billingCity,
                'billing_state' => $billingState,
                'billing_zip_code' => $billingZipCode,
                'billing_country' => $billingCountry,
                'notes' => $validated['notes'] ?? null,
                'ordered_at' => now(),
            ]);

            foreach ($cart->items as $cartItem) {
                $product = $cartItem->product;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'gold_purity' => $product->gold_purity,
                    'weight' => $product->weight,
                    'subtotal' => $cartItem->price * $cartItem->quantity,
                ]);

                $oldQuantity = $product->stock_quantity;
                $newQuantity = $oldQuantity - $cartItem->quantity;
                
                $product->update(['stock_quantity' => $newQuantity]);

                InventoryMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'movement_type' => 'sale',
                    'quantity_change' => -$cartItem->quantity,
                    'quantity_before' => $oldQuantity,
                    'quantity_after' => $newQuantity,
                    'reason' => 'Order #' . $orderNumber,
                ]);
            }

            $transactionRef = 'TXN-' . strtoupper(uniqid()) . '-' . time();
            
            Payment::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'amount' => $totalPrice,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'transaction_reference' => $transactionRef,
            ]);

            $order->update([
                'transaction_reference' => $transactionRef,
                'order_status' => 'processing',
            ]);

            $cart->items()->delete();
            $cart->update(['total_amount' => 0, 'item_count' => 0]);

            DB::commit();

            return redirect()->route('order.confirmation', $order->id)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('error', 'Failed to process order. Please try again.')->withInput();
        }
    }

    public function confirmation(int $orderId): View
    {
        $user = auth()->user();
        
        $order = Order::with(['items', 'payment'])
            ->where('user_id', $user->id)
            ->findOrFail($orderId);

        return view('order-confirmation', compact('order'));
    }
}
