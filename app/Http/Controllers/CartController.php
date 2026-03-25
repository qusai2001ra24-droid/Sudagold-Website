<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private const SESSION_CART_KEY = 'guest_cart';

    public function index(): View
    {
        return view('cart');
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (!$product->is_active || $product->stock_quantity < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available in the requested quantity.',
            ], 400);
        }

        $user = auth()->user();

        if ($user) {
            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['total_amount' => 0, 'item_count' => 0]
            );

            $existingItem = $cart->items()->where('product_id', $product->id)->first();

            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $validated['quantity'];

                if ($product->stock_quantity < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Requested quantity exceeds available stock.',
                    ], 400);
                }

                $existingItem->update([
                    'quantity' => $newQuantity,
                    'price' => $product->special_price ?? $product->price,
                ]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'price' => $product->special_price ?? $product->price,
                ]);
            }

            $cart->recalculateTotals();
        } else {
            $this->addToGuestCart($product, $validated['quantity']);
        }

        $cartData = $this->getCartData();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart.',
            'cart' => $cartData,
        ]);
    }

    public function updateQuantity(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item_id' => ['required'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $user = auth()->user();

        if ($user) {
            $cartItem = CartItem::whereHas('cart', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->find($validated['item_id']);

            if (!$cartItem) {
                return response()->json(['success' => false, 'message' => 'Cart item not found.'], 404);
            }

            $product = $cartItem->product;

            if ($product->stock_quantity < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested quantity exceeds available stock.',
                ], 400);
            }

            $cartItem->update(['quantity' => $validated['quantity']]);
            $cartItem->cart->recalculateTotals();
        } else {
            $guestCart = $this->getGuestCart();

            if (!isset($guestCart[$validated['item_id']])) {
                return response()->json(['success' => false, 'message' => 'Cart item not found.'], 404);
            }

            $productId = $guestCart[$validated['item_id']]['product_id'];
            $product = Product::find($productId);

            if (!$product || $product->stock_quantity < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested quantity exceeds available stock.',
                ], 400);
            }

            $guestCart[$validated['item_id']]['quantity'] = $validated['quantity'];
            $guestCart[$validated['item_id']]['price'] = $product->special_price ?? $product->price;
            $this->saveGuestCart($guestCart);
        }

        $cartData = $this->getCartData();

        return response()->json([
            'success' => true,
            'cart' => $cartData,
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item_id' => ['required'],
        ]);

        $user = auth()->user();

        if ($user) {
            $cartItem = CartItem::whereHas('cart', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->find($validated['item_id']);

            if (!$cartItem) {
                return response()->json(['success' => false, 'message' => 'Cart item not found.'], 404);
            }

            $cartItem->delete();
            $cartItem->cart->recalculateTotals();
        } else {
            $guestCart = $this->getGuestCart();

            if (!isset($guestCart[$validated['item_id']])) {
                return response()->json(['success' => false, 'message' => 'Cart item not found.'], 404);
            }

            unset($guestCart[$validated['item_id']]);
            $this->saveGuestCart($guestCart);
        }

        $cartData = $this->getCartData();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'cart' => $cartData,
        ]);
    }

    public function clear(): JsonResponse
    {
        $user = auth()->user();

        if ($user) {
            $cart = Cart::where('user_id', $user->id)->first();

            if ($cart) {
                $cart->items()->delete();
                $cart->update(['total_amount' => 0, 'item_count' => 0]);
            }
        } else {
            $this->saveGuestCart([]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared.',
            'cart' => $this->getCartData(),
        ]);
    }

    public function getCart(): JsonResponse
    {
        return response()->json($this->getCartData());
    }

    private function addToGuestCart(Product $product, int $quantity): void
    {
        $guestCart = $this->getGuestCart();
        $itemId = Str::uuid()->toString();

        $existingIndex = null;
        foreach ($guestCart as $key => $item) {
            if ($item['product_id'] === $product->id) {
                $existingIndex = $key;
                break;
            }
        }

        if ($existingIndex !== null) {
            $newQuantity = $guestCart[$existingIndex]['quantity'] + $quantity;

            if ($product->stock_quantity < $newQuantity) {
                return;
            }

            $guestCart[$existingIndex]['quantity'] = $newQuantity;
            $guestCart[$existingIndex]['price'] = $product->special_price ?? $product->price;
        } else {
            $guestCart[$itemId] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->special_price ?? $product->price,
                'added_at' => now()->toDateTimeString(),
            ];
        }

        $this->saveGuestCart($guestCart);
    }

    private function getGuestCart(): array
    {
        return session()->get(self::SESSION_CART_KEY, []);
    }

    private function saveGuestCart(array $cart): void
    {
        session()->put(self::SESSION_CART_KEY, $cart);
    }

    private function getCartData(): array
    {
        $user = auth()->user();

        if ($user) {
            $cart = Cart::with(['items.product'])->where('user_id', $user->id)->first();

            if (!$cart || $cart->items->isEmpty()) {
                return [
                    'items' => [],
                    'item_count' => 0,
                    'subtotal' => 0,
                    'total_weight' => 0,
                    'tax' => 0,
                    'total' => 0,
                ];
            }

            return $this->formatCartData($cart);
        }

        return $this->getGuestCartData();
    }

    private function getGuestCartData(): array
    {
        $guestCart = $this->getGuestCart();

        if (empty($guestCart)) {
            return [
                'items' => [],
                'item_count' => 0,
                'subtotal' => 0,
                'total_weight' => 0,
                'tax' => 0,
                'total' => 0,
            ];
        }

        $items = [];
        $itemCount = 0;
        $subtotal = 0;
        $totalWeight = 0;

        foreach ($guestCart as $itemId => $item) {
            $product = Product::with('category')->find($item['product_id']);

            if (!$product) {
                continue;
            }

            $itemSubtotal = $item['price'] * $item['quantity'];
            $items[] = [
                'id' => $itemId,
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'image' => $product->image_url,
                'price' => (float) $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => (float) $itemSubtotal,
                'gold_purity' => $product->gold_purity,
                'weight' => (float) $product->weight,
            ];

            $itemCount += $item['quantity'];
            $subtotal += $itemSubtotal;
            $totalWeight += $item['quantity'] * $product->weight;
        }

        $tax = round($subtotal * 0.08, 2);

        return [
            'items' => $items,
            'item_count' => $itemCount,
            'subtotal' => round($subtotal, 2),
            'total_weight' => round($totalWeight, 3),
            'tax' => $tax,
            'total' => round($subtotal + $tax, 2),
        ];
    }

    private function formatCartData(Cart $cart): array
    {
        $items = $cart->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'sku' => $item->product->sku,
                'image' => $item->product->image_url,
                'price' => (float) $item->price,
                'quantity' => $item->quantity,
                'subtotal' => (float) $item->subtotal,
                'gold_purity' => $item->product->gold_purity,
                'weight' => (float) $item->product->weight,
            ];
        });

        $subtotal = $items->sum('subtotal');
        $totalWeight = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->weight;
        });
        $tax = round($subtotal * 0.08, 2);

        return [
            'items' => $items->toArray(),
            'item_count' => $cart->item_count,
            'subtotal' => $subtotal,
            'total_weight' => round($totalWeight, 3),
            'tax' => $tax,
            'total' => $subtotal + $tax,
        ];
    }
}
