<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::all();
        return view('sales', compact('sales'));
    }

    public function create()
    {
        $sale = new Sale(); // Empty sale model for creating a new sale
        $inventories = Inventory::all();
        return view('sales_form', compact('sale', 'inventories')); // Updated variable name
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id); // Renamed to $sale for clarity
        $inventories = Inventory::all(); // Pass inventories for product selection during editing
        return view('sales_form', compact('sale', 'inventories')); // Ensure we pass inventories too
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'product_code' => 'required|string|exists:inventories,product_code', // Ensure product_code exists in inventory
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        try {
            $sale = new Sale();
            $this->save($sale, $request);

            $inventory = Inventory::where('product_code', $request->product_code)->first();

            if ($inventory && $inventory->quantity >= $request->quantity) {
                $inventory->quantity -= $request->quantity;
                $inventory->save();
            } else {
                throw new \Exception('Not enough stock available.');
            }

            return redirect()->route('sale.index')
                ->with('success', 'Congratulations! Your sale <strong>"' . $sale->product_code . ' | ' . $sale->product_name . '"</strong> has been successfully completed!');

        } catch (\Exception $e) {
            return redirect()->route('sale.index')->with('error', 'An error occurred while process sales the sale.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'product_code' => 'required|string|exists:inventories,product_code', // ตรวจสอบให้แน่ใจว่า product_code มีอยู่ใน inventory
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // เริ่มต้นการทำธุรกรรมฐานข้อมูล
        DB::beginTransaction();

        try {
            // ค้นหาการขายตาม ID และอัปเดต
            $sale = Sale::findOrFail($id);

            // ตรวจสอบว่าจำนวนที่ขายเปลี่ยนแปลงหรือไม่
            $previousQuantity = $sale->quantity;

            // อัปเดตการขาย
            $this->save($sale, $request);

            // ค้นหา inventory ที่เกี่ยวข้องตามรหัสสินค้า
            $inventory = Inventory::where('product_code', $request->product_code)->firstOrFail();

            // ปรับปรุง inventory เมื่อจำนวนที่ขายเปลี่ยนแปลงเท่านั้น
            $quantityDifference = $request->quantity - $previousQuantity;

            if ($quantityDifference > 0 && $inventory->quantity < $quantityDifference) {
                return redirect()->back()->withErrors('Not Enough Stock Available.');
            }

            // อัปเดตจำนวนใน inventory
            $inventory->quantity -= $quantityDifference;
            $inventory->save();

            // ยืนยันการทำธุรกรรม
            DB::commit();

            return redirect()->route('sale.index')
                ->with('warning', 'Your product <strong>"' . $sale->product_code . '"</strong> has been updated successfully.');

        } catch (\Exception) {
            // ยกเลิกการทำธุรกรรมหากเกิดข้อผิดพลาด
            DB::rollBack();

            return redirect()->route('sale.index')->with('error', 'An error occurred while updating the sale.');
        }
    }
    public function save($sale, $value)
    {
        $sale->first_name = $value->first_name;
        $sale->last_name = $value->last_name;
        $sale->product_code = $value->product_code;

        // Get product details from inventory using the product_code
        $inventory = Inventory::where('product_code', $value->product_code)->first();
        if ($inventory) {
            $sale->product_name = $inventory->name;
            $sale->color = $inventory->color;
            $sale->price = $inventory->price;
        }

        $sale->quantity = $value->quantity;
        $sale->total = $sale->quantity * $sale->price; // Calculate total based on quantity and price

        $sale->description = $value->description ?? $inventory->description;
        $sale->save();
    }

    public function destroy($id)
    {
        $sale = Sale::find($id);

        if ($sale) {
            $sale->delete();
            return redirect()->route('sale.index')
                ->with('error', 'Your sale <strong>"' . $sale->product_code . ' | ' . $sale->product_name . '"</strong> has been deleted successfully.');
        } else {
            return redirect()->route('sale.index')
                ->with('error', 'Product not found.');
        }
    }
}
