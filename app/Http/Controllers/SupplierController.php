<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        if (!Auth::user()->canEditEverything()) {
            abort(403, 'Unauthorized action.');
        }
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->canEditEverything()) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'nullable|max:20',
            'address' => 'nullable'
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['products.category']);
        $totalProducts = $supplier->products->count();
        $totalValue = $supplier->products->sum(fn($p) => $p->price * $p->quantity);
        
        return view('suppliers.show', compact('supplier', 'totalProducts', 'totalValue'));
    }

    public function edit(Supplier $supplier)
    {
        if (!Auth::user()->canEditEverything()) {
            abort(403, 'Unauthorized action.');
        }
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        if (!Auth::user()->canEditEverything()) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'nullable|max:20',
            'address' => 'nullable'
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can delete suppliers.');
        }
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
