<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $lowStockCount = Product::whereColumn('quantity', '<=', 'min_stock_level')->count();
        
        $recentTransactions = StockTransaction::with('product')
            ->latest()
            ->limit(5)
            ->get();

        $totalInventoryValue = Product::selectRaw('SUM(price * quantity) as total')->value('total');

        // Category distribution
        $categoryDistribution = Category::withCount('products')
            ->has('products')
            ->get()
            ->map(function($category) use ($totalProducts) {
                return [
                    'name' => $category->name,
                    'count' => $category->products_count,
                    'percentage' => $totalProducts > 0 ? ($category->products_count / $totalProducts) * 100 : 0
                ];
            });

        // Top moving products (by transaction count)
        $topMovingProducts = Product::withCount('transactions')
            ->orderBy('transactions_count', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts', 
            'totalCategories', 
            'totalSuppliers', 
            'lowStockCount', 
            'recentTransactions',
            'totalInventoryValue',
            'categoryDistribution',
            'topMovingProducts'
        ));
    }
}
