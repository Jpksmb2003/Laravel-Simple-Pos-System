<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoriesController extends Controller
{
    public function index()
    {
        $inventories = Inventory::all();
        return view('inventory', compact('inventories'));
    }


    public function create()
    {
        $inventories = new Inventory();
        return view('form', compact('inventories'));
    }

    public function edit($id)
    {
        $inventories = Inventory::findOrFail($id);
        return view('form', compact('inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string|min:8|max:8|regex:/^[A-Z0-9]+$/|unique:inventories,product_code',
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ],
            [
                'product_code.unique' => 'The product code already exists, please use a different one.',
            ]);

        $inventory = new Inventory();
        $this->save($inventory, $request);

        return redirect()->route('inventory.index')->with('success',  'Inventory product <strong>" ' . $inventory->product_code ." | ". $inventory->name . ' " </strong>has been created successfully.');

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_code' => 'required|string|min:8|max:8|regex:/^[A-Z0-9]+$/|unique:inventories,product_code,' . $id,
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ],
            [
                'product_code.unique' => 'The product code already exists, please use a different one.',
            ]);

        // Find and update the inventory
        $inventory = Inventory::findOrFail($request->id);
        $this->save($inventory, $request);
        return redirect()->route('inventory.index')->with('warning',  'Inventory product <strong>"' . $inventory->product_code . '" </strong>has been updated successfully.');
    }

    public function save($inventory, $value)
    {
        $inventory->product_code = $value->product_code;
        $inventory->name = $value->name;
        $inventory->color = $value->color;
        $inventory->quantity = $value->quantity;
        $inventory->price = $value->price;
        $inventory->description = $value->description;
        $inventory->save();
    }

    public function destroy($id)
    {
        $inventory = Inventory::find($id);

        if ($inventory) {
            $inventory->delete();
            return redirect()->route('inventory.index')->with('error', 'Inventory product <strong>"' .$inventory->product_code ." | ". $inventory->name . '" </strong> deleted successfully.');
        } else {
            return redirect()->route('inventory.index')->with('error', 'Inventory product not found.');
        }
    }

}
