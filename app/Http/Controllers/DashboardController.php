<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;  

class DashboardController extends Controller
{
    public function index()
    {
        // Get total quantity sold per product name
        $sales = Sale::select('product_name', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_name')
            ->get();
    
        // Get total sales amount
        $totalSales = Sale::select(DB::raw('DATE(created_at) as sale_date'), DB::raw('SUM(total) as total_sales'))
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();
    
        return view('dashboard', compact('sales', 'totalSales'));
    }
}
