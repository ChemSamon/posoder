<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function store(Request $request)
{
    $cart = session('cart', []);
    if (empty($cart)) {
        return response()->json(['success' => false, 'message' => 'Cart is empty.']);
    }

    $discount = $request->input('discount', 0);
    $note = $request->input('note', '');
    $itemNotes = $request->input('item_notes', []); // ✅ receive all item notes
    $receiptNumber = $request->input('receipt_number');

    // 🔁 Ensure unique receipt number
    while (Order::where('receipt_number', $receiptNumber)->exists()) {
        $receiptNumber = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    // 🧮 Calculate totals
    $total = collect($cart)->sum(fn($item) => $item['quantity'] * $item['price']);
    $discountAmount = $total * ($discount / 100);
    $finalTotal = $total - $discountAmount;

    // ✅ Create the main order
    $order = Order::create([
        'receipt_number' => $receiptNumber,
        'total' => $total,
        'discount' => $discount,
        'total_after_discount' => $finalTotal,
        'note' => $note, // file-level note
    ]);

    // ✅ Save each order item + its specific note
    foreach ($cart as $id => $item) {
        $order->items()->create([
            'product_name' => $item['name'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'line_total' => $item['quantity'] * $item['price'],
            'note' => $itemNotes[$id] ?? null, // ✅ each item’s note from JS
        ]);
    }

    // ✅ Clear cart after order saved
    session()->forget('cart');

    return response()->json(['success' => true, 'order_id' => $order->id]);
}


    public function history(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $orders = $query->paginate(5);

        return view('orders.history', compact('orders'));
    }

    public function daily()
    {
        $orders = Order::with('items')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('orders.history', compact('orders'));
    }

    public function monthly()
    {
        $orders = Order::with('items')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('orders.history', compact('orders'));
    }

    public function clearHistory()
    {
        Order::truncate(); // deletes all orders
        OrderItem::truncate(); // deletes all related items

        return redirect()->route('orders.history')->with('success', 'Order history cleared.');
    }

    public function getMonthlySalesData()
    {
        $labels = [];
        $values = [];

        // Generate sales data for Jan to Dec
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->format('F');
            $values[] = Order::whereMonth('created_at', $i)
                            ->whereYear('created_at', now()->year)
                            ->sum('total_after_discount');
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }

    public function print($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('orders.print', compact('order'));
    }
}
