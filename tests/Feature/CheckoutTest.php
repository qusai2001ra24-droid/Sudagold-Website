<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Checkout Controller', function () {
    describe('GET /checkout', function () {
        it('redirects guest users to login', function () {
            $response = $this->get('/checkout');

            $response->assertRedirect('/login');
        });

        it('redirects users with empty cart', function () {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->get('/checkout');

            $response->assertRedirect('/cart');
        });

        it('shows checkout page for users with cart', function () {
            $user = User::factory()->create();
            $product = Product::factory()->create(['price' => 100, 'stock_quantity' => 10]);

            $cart = Cart::create(['user_id' => $user->id, 'total_amount' => 0, 'item_count' => 0]);
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => 100,
            ]);
            $cart->recalculateTotals();

            $response = $this->actingAs($user)->get('/checkout');

            $response->assertStatus(200);
            $response->assertSee('Complete Your Order');
        });
    });

    describe('POST /checkout/process', function () {
        beforeEach(function () {
            $this->user = User::factory()->create();
            $this->product = Product::factory()->create([
                'price' => 100,
                'stock_quantity' => 10,
            ]);

            $this->cart = Cart::create([
                'user_id' => $this->user->id,
                'total_amount' => 0,
                'item_count' => 0,
            ]);
            CartItem::create([
                'cart_id' => $this->cart->id,
                'product_id' => $this->product->id,
                'quantity' => 2,
                'price' => 100,
            ]);
            $this->cart->recalculateTotals();
        });

        it('creates order successfully', function () {
            $response = $this->actingAs($this->user)->post('/checkout/process', [
                'shipping_first_name' => 'John',
                'shipping_last_name' => 'Doe',
                'shipping_email' => 'john@example.com',
                'shipping_phone' => '+1234567890',
                'shipping_address' => '123 Main St',
                'shipping_city' => 'New York',
                'shipping_state' => 'NY',
                'shipping_zip_code' => '10001',
                'shipping_country' => 'United States',
                'payment_method' => 'credit_card',
                'same_as_billing' => true,
            ]);

            $response->assertRedirect();
            $this->assertDatabaseHas('orders', [
                'user_id' => $this->user->id,
                'subtotal' => 200.0,
            ]);
            $this->assertDatabaseHas('order_items', [
                'product_id' => $this->product->id,
                'quantity' => 2,
            ]);
        });

        it('decrements product stock after order', function () {
            $initialStock = $this->product->stock_quantity;

            $this->actingAs($this->user)->post('/checkout/process', [
                'shipping_first_name' => 'John',
                'shipping_last_name' => 'Doe',
                'shipping_email' => 'john@example.com',
                'shipping_phone' => '+1234567890',
                'shipping_address' => '123 Main St',
                'shipping_city' => 'New York',
                'shipping_state' => 'NY',
                'shipping_zip_code' => '10001',
                'shipping_country' => 'United States',
                'payment_method' => 'paypal',
                'same_as_billing' => true,
            ]);

            $this->product->refresh();
            expect($this->product->stock_quantity)->toBe($initialStock - 2);
        });

        it('clears cart after order', function () {
            $this->actingAs($this->user)->post('/checkout/process', [
                'shipping_first_name' => 'John',
                'shipping_last_name' => 'Doe',
                'shipping_email' => 'john@example.com',
                'shipping_phone' => '+1234567890',
                'shipping_address' => '123 Main St',
                'shipping_city' => 'New York',
                'shipping_state' => 'NY',
                'shipping_zip_code' => '10001',
                'shipping_country' => 'United States',
                'payment_method' => 'bank_transfer',
                'same_as_billing' => true,
            ]);

            $this->assertDatabaseCount('cart_items', 0);
        });

        it('creates payment record', function () {
            $this->actingAs($this->user)->post('/checkout/process', [
                'shipping_first_name' => 'John',
                'shipping_last_name' => 'Doe',
                'shipping_email' => 'john@example.com',
                'shipping_phone' => '+1234567890',
                'shipping_address' => '123 Main St',
                'shipping_city' => 'New York',
                'shipping_state' => 'NY',
                'shipping_zip_code' => '10001',
                'shipping_country' => 'United States',
                'payment_method' => 'credit_card',
                'same_as_billing' => true,
            ]);

            $order = Order::first();
            $this->assertDatabaseHas('payments', [
                'order_id' => $order->id,
                'payment_status' => 'pending',
            ]);
        });

        it('accepts all valid payment methods', function () {
            $paymentMethods = ['credit_card', 'debit_card', 'paypal', 'bank_transfer', 'cash_on_delivery'];

            foreach ($paymentMethods as $method) {
                $product = Product::factory()->create(['stock_quantity' => 10]);
                $cart = Cart::create(['user_id' => $this->user->id, 'total_amount' => 0, 'item_count' => 0]);
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => 100,
                ]);
                $cart->recalculateTotals();

                $response = $this->actingAs($this->user)->post('/checkout/process', [
                    'shipping_first_name' => 'John',
                    'shipping_last_name' => 'Doe',
                    'shipping_email' => 'john@example.com',
                    'shipping_phone' => '+1234567890',
                    'shipping_address' => '123 Main St',
                    'shipping_city' => 'New York',
                    'shipping_state' => 'NY',
                    'shipping_zip_code' => '10001',
                    'shipping_country' => 'United States',
                    'payment_method' => $method,
                    'same_as_billing' => true,
                ]);

                $response->assertSessionHasNoErrors();
            }
        });

        it('calculates tax correctly', function () {
            $this->actingAs($this->user)->post('/checkout/process', [
                'shipping_first_name' => 'John',
                'shipping_last_name' => 'Doe',
                'shipping_email' => 'john@example.com',
                'shipping_phone' => '+1234567890',
                'shipping_address' => '123 Main St',
                'shipping_city' => 'New York',
                'shipping_state' => 'NY',
                'shipping_zip_code' => '10001',
                'shipping_country' => 'United States',
                'payment_method' => 'credit_card',
                'same_as_billing' => true,
            ]);

            $order = Order::first();
            expect($order->subtotal)->toBe(200.0);
            expect($order->tax_amount)->toBe(16.0);
        });
    });

    describe('GET /order/confirmation/{id}', function () {
        it('shows order confirmation', function () {
            $user = User::factory()->create();
            $order = Order::factory()->create(['user_id' => $user->id]);

            $response = $this->actingAs($user)->get("/order/confirmation/{$order->id}");

            $response->assertStatus(200);
            $response->assertSee($order->order_number);
        });

        it('prevents viewing other users orders', function () {
            $user1 = User::factory()->create();
            $user2 = User::factory()->create();
            $order = Order::factory()->create(['user_id' => $user2->id]);

            $response = $this->actingAs($user1)->get("/order/confirmation/{$order->id}");

            $response->assertStatus(404);
        });
    });
});

describe('Order Cancellation', function () {
    it('restores stock when order is cancelled', function () {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'order_status' => 'processing',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'sku' => $product->sku,
            'price' => 100,
            'quantity' => 3,
            'gold_purity' => '24k',
            'weight' => 10,
            'subtotal' => 300,
        ]);

        $product->update(['stock_quantity' => 7]);

        $response = $this->actingAs($user)->patch("/admin/orders/{$order->id}/status", [
            'order_status' => 'cancelled',
        ]);

        $response->assertSessionHas('success');
        $product->refresh();
        expect($product->stock_quantity)->toBe(10);
    });
});
