<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display the stock count page (on-screen view).
     */
    public function stockCount()
    {
        $stockLevels = Product::with('category')->orderBy('name')->get();
        $breadcrumbs = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Stock Count']];
        return view('inventory.stock_count', compact('stockLevels', 'breadcrumbs'));
    }

    /**
     * New method to show the dedicated print view.
     */
    public function stockCountPrint()
    {
        $stockLevels = Product::with('category')->orderBy('name')->get();
        return view('inventory.stock_count_print', compact('stockLevels'));
    }

    /**
     * Display the stock adjustment page.
     */
    public function stockAdjustment()
    {
        $this->authorize('admin-only');
        $products = Product::orderBy('name')->get();
        $adjustments = StockAdjustment::with('product', 'user')->latest()->limit(25)->get();
        $breadcrumbs = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Stock Adjustment']];
        return view('inventory.stock_adjustment', compact('products', 'adjustments', 'breadcrumbs'));
    }

    /**
     * Handle the stock adjustment form submission.
     */
    public function processStockAdjustment(Request $request)
    {
        $this->authorize('admin-only');
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:add,remove',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int)$request->quantity;

        try {
            DB::beginTransaction();

            if ($request->adjustment_type === 'remove' && $quantity > $product->quantity) {
                DB::rollBack();
                return redirect()->back()->with('error', "Cannot remove more stock than is available. Current stock: {$product->quantity}");
            }

            if ($request->adjustment_type === 'add') {
                $product->quantity += $quantity;
            } else {
                $product->quantity -= $quantity;
            }
            $product->save();

            StockAdjustment::create([
                'product_id' => $product->id, 
                'user_id' => Auth::id(), 
                'adjustment_type' => $request->adjustment_type, 
                'quantity_changed' => $quantity, 
                'reason' => $request->reason,
            ]);

            DB::commit();
            return redirect()->route('inventory.stock_adjustment')->with('success', 'Stock adjusted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while adjusting stock: ' . $e->getMessage())->withInput();
        }
    }
}
