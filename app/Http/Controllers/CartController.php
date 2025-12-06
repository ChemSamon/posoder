<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class CartController extends Controller
{
    
    public function add($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Item added to cart.');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }


public function checkout(Request $request)
{
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->back()->with('error', 'Cart is empty.');
    }

    $discount = $request->input('discount', 0);
    $total = 0;

    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $discountAmount = $total * ($discount / 100);
    $totalAfterDiscount = $total - $discountAmount;

    $order = Order::create([
        'receipt_number' => strtoupper(Str::random(10)),
        'total' => $total,
        'discount' => $discount,
        'total_after_discount' => $totalAfterDiscount,
    ]);

    foreach ($cart as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'line_total' => $item['price'] * $item['quantity'],
        ]);
    }

    session()->forget('cart'); // Clear cart after order is saved
    return redirect()->back()->with('success', 'Order has been stored.');
}

public function clear()
{
    session()->forget('cart');
    return redirect()->route('pos')->with('success', 'Cart cleared successfully!');
}


}
