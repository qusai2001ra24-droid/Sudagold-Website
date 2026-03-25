<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Auth\Events\Login;

class MergeGuestCart
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $guestCart = session()->get('guest_cart', []);

        if (empty($guestCart)) {
            return;
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['total_amount' => 0, 'item_count' => 0]
        );

        foreach ($guestCart as $itemId => $item) {
            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $item['product_id'])
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $item['quantity'],
                ]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }

        $cart->recalculateTotals();

        session()->forget('guest_cart');
    }
}
