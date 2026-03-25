<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

uses(RefreshDatabase::class);

beforeEach(function () {
    Session::flush();
});

describe('Cart Controller', function () {
    describe('GET /cart', function () {
        it('displays the cart page', function () {
            $response = $this->get('/cart');

            $response->assertStatus(200);
            $response->assertSee('Shopping Cart');
        });
    });

    describe('GET /cart/data', function () {
        it('returns empty cart data for guest', function () {
            $response = $this->getJson('/cart/data');

            $response->assertStatus(200);
            $response->assertJson([
                'items' => [],
                'item_count' => 0,
            ]);
        });

        it('returns cart data for authenticated user', function () {
            $user = User::factory()->create();
            $product = Product::factory()->create(['price' => 100, 'stock_quantity' => 10]);

            $cart = Cart::create(['user_id' => $user->id, 'total_amount' => 0, 'item_count' => 0]);
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => 100,
            ]);
            $cart->recalculateTotals();

            $response = $this->actingAs($user)->getJson('/cart/data');

            $response->assertStatus(200);
            $response->assertJsonCount(1, 'items');
            $response->assertJsonPath('item_count', 2);
            $response->assertJsonPath('subtotal', 200);
        });
    });

    describe('POST /cart/add', function () {
        it('adds product to guest cart', function () {
            $product = Product::factory()->create(['price' => 150, 'stock_quantity' => 10]);

            $response = $this->postJson('/cart/add', [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);

            $response->assertStatus(200);
            $response->assertJson([
                'success' => true,
                'message' => 'Product added to cart.',
            ]);

            $cartData = $this->getJson('/cart/data');
            $cartData->assertJsonPath('item_count', 2);
            $cartData->assertJsonPath('subtotal', 300);
        });

        it('adds product to user cart', function () {
            $user = User::factory()->create();
            $product = Product::factory()->create(['price' => 200, 'stock_quantity' => 10]);

            $response = $this->actingAs($user)->postJson('/cart/add', [
                'product_id' => $product->id,
                'quantity' => 1,
            ]);

            $response->assertStatus(200);
            $response->assertJson(['success' => true]);

            $this->assertDatabaseHas('cart_items', [
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        });

        it('prevents adding out of stock product', function () {
            $product = Product::factory()->create(['stock_quantity' => 0]);

            $response = $this->postJson('/cart/add', [
                'product_id' => $product->id,
                'quantity' => 1,
            ]);

            $response->assertStatus(400);
            $response->assertJson(['success' => false]);
        });

        it('prevents adding more than available stock', function () {
            $product = Product::factory()->create(['stock_quantity' => 5]);

            $response = $this->postJson('/cart/add', [
                'product_id' => $product->id,
                'quantity' => 10,
            ]);

            $response->assertStatus(400);
            $response->assertJson(['success' => false]);
        });

        it('updates quantity when same product added again', function () {
            $user = User::factory()->create();
            $product = Product::factory()->create(['price' => 100, 'stock_quantity' => 20]);

            $cart = Cart::create(['user_id' => $user->id, 'total_amount' => 0, 'item_count' => 0]);
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => 100,
            ]);
            $cart->recalculateTotals();

            $response = $this->actingAs($user)->postJson('/cart/add', [
                'product_id' => $product->id,
                'quantity' => 3,
            ]);

            $response->assertStatus(200);
            $this->assertDatabaseHas('cart_items', [
                'product_id' => $product->id,
                'quantity' => 5,
            ]);
        });
    });

    describe('POST /cart/update-quantity', function () {
        it('updates item quantity for user cart', function () {
            $user = User::factory()->create();
            $product = Product::factory()->create(['price' => 50, 'stock_quantity' => 20]);

            $cart = Cart::create(['user_id' => $user->id, 'total_amount' => 0, 'item_count' => 0]);
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => 50,
            ]);
            $cart->recalculateTotals();

            $response = $this->actingAs($user)->postJson('/cart/update-quantity', [
                'item_id' => $cartItem->id,
                'quantity' => 5,
            ]);

            $response->assertStatus(200);
            $response->assertJson(['success' => true]);
            $this->assertDatabaseHas('cart_items', [
                'id' => $cartItem->id,
                'quantity' => 5,
            ]);
        });

        it('rejects quantity exceeding stock', function () {
            $user = User::factory()->create();
            $product = Product::factory()->create(['stock_quantity' => 5]);

            $cart = Cart::create(['user_id' => $user->id, 'total_amount' => 0, 'item_count' => 0]);
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => 100,
            ]);

            $response = $this->actingAs($user)->postJson('/cart/update-quantity', [
                'item_id' => $cartItem->id,
                'quantity' => 10,
            ]);

            $response->assertStatus(400);
        });
    });

    describe('POST /cart/remove', function () {
        it('removes item from user cart', function () {
            $user = User::factory()->create();
            $product = Product::factory()->create();

            $cart = Cart::create(['user_id' => $user->id, 'total_amount' => 0, 'item_count' => 0]);
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => 100,
            ]);
            $cart->recalculateTotals();

            $response = $this->actingAs($user)->postJson('/cart/remove', [
                'item_id' => $cartItem->id,
            ]);

            $response->assertStatus(200);
            $response->assertJson(['success' => true]);
            $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
        });

        it('returns 404 for non-existent item', function () {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->postJson('/cart/remove', [
                'item_id' => 99999,
            ]);

            $response->assertStatus(404);
        });
    });

    describe('POST /cart/clear', function () {
        it('clears all items from user cart', function () {
            $user = User::factory()->create();
            $product = Product::factory()->create();

            $cart = Cart::create(['user_id' => $user->id, 'total_amount' => 0, 'item_count' => 0]);
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => 100,
            ]);
            $cart->recalculateTotals();

            $response = $this->actingAs($user)->postJson('/cart/clear');

            $response->assertStatus(200);
            $response->assertJson(['success' => true]);
            $this->assertDatabaseCount('cart_items', 0);
        });

        it('clears guest cart', function () {
            $product = Product::factory()->create(['price' => 100, 'stock_quantity' => 10]);

            $this->postJson('/cart/add', [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);

            $response = $this->postJson('/cart/clear');

            $response->assertStatus(200);
            $cartData = $this->getJson('/cart/data');
            $cartData->assertJsonPath('item_count', 0);
        });
    });
});
