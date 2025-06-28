<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAdjustment; // Make sure to import the model
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    /**
     * Display the main stock count page with current inventory levels.
     *
     * @return \Illuminate\View\View
     */
    public function stockCount(): View
    {
        $stockLevels = Product::orderBy('name')->get();
        return view('inventory.stock_count', compact('stockLevels'));
    }

    /**
     * Display a printable view of the stock count worksheet.
     *
     * @return \Illuminate\View\View
     */
    public function printView(): View
    {
        $stockLevels = Product::orderBy('name')->get();
        return view('inventory.stock_count_print', compact('stockLevels'));
    }

    /**
     * Show the page for making stock adjustments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function stockAdjustment(Request $request): View
    {
        // Retrieve all products for the dropdown menu.
        $products = Product::orderBy('name')->get();

        // THE FIX: Fetch recent stock adjustments, ordered by the newest first.
        $adjustments = StockAdjustment::with('product')->latest()->paginate(10); // Paginate for performance

        // Get the product_id from the URL to pre-select it in the dropdown.
        $selectedProductId = $request->get('product_id');

        // Return the view and pass all the necessary data to it.
        return view('inventory.stock_adjustment', compact('products', 'adjustments', 'selectedProductId'));
    }

    /**
     * Process the submitted stock adjustment form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processStockAdjustment(Request $request)
    {
        // Validate the incoming request data.
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'reason' => 'required|string|in:recount,damage,theft,return,other',
            'notes' => 'nullable|string|max:255',
        ]);

        // Find the product to be adjusted.
        $product = Product::findOrFail($request->product_id);

        // Create a new stock adjustment record.
        StockAdjustment::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'notes' => $request->notes,
        ]);

        // Update the product's main quantity.
        $product->quantity += $request->quantity;
        $product->save();

        // Redirect back with a success message.
        return redirect()->route('inventory.stock_adjustment')->with('success', 'Stock has been adjusted successfully!');
    }
}
