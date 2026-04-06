<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->get();
        $recentTransactions = StockTransaction::with(['product', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('inventory.index', compact('products', 'recentTransactions'));
    }

    public function update(Request $request, Product $product)
    {
        if (!Auth::user()->canManageStock()) {
            abort(403, 'Only managers and administrators can record stock movements.');
        }
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'action' => 'required|in:add,remove',
            'notes' => 'nullable|string|max:255'
        ]);

        $qty = $request->quantity;
        $type = $request->action;

        if ($type == 'add') {
            $product->quantity += $qty;
        } else {
            if ($product->quantity < $qty) {
                return redirect()->back()->with('error', 'Not enough stock.');
            }
            $product->quantity -= $qty;
        }

        $product->save();

        // Log the transaction
        StockTransaction::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => $type,
            'quantity' => $qty,
            'balance_after' => $product->quantity,
            'notes' => $request->notes
        ]);

        return redirect()->route('inventory.index')->with('success', 'Stock updated and logged successfully.');
    }

    public function print(StockTransaction $transaction)
    {
        $transaction->load(['product', 'user']);
        return view('inventory.print', compact('transaction'));
    }
}
