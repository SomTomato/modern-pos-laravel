<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of all purchase orders.
     */
    public function index(): View
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->latest()->paginate(15);
        return view('purchase_orders.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new purchase order.
     */
    public function create(): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created purchase order in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        // Use a database transaction to ensure data integrity
        DB::beginTransaction();
        try {
            $totalCost = 0;
            foreach ($request->items as $item) {
                $totalCost += $item['quantity'] * $item['cost_price'];
            }

            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'notes' => $request->notes,
                'total_cost' => $totalCost,
                'status' => 'pending',
            ]);

            foreach ($request->items as $item) {
                $purchaseOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                ]);
            }

            DB::commit(); // Commit the transaction if everything is successful
            return redirect()->route('purchase_orders.index')->with('success', 'Purchase Order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction on error
            return back()->with('error', 'Failed to create Purchase Order. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified purchase order.
     */
    public function show(PurchaseOrder $purchaseOrder): View
    {
        // Eager load relationships for efficiency
        $purchaseOrder->load('supplier', 'items.product');
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     * Note: This example allows editing the status and notes. 
     * A full item editor is a more complex feature.
     */
    public function edit(PurchaseOrder $purchaseOrder): View
    {
        return view('purchase_orders.edit', compact('purchaseOrder'));
    }

    /**
     * Update the specified purchase order (e.g., to mark as received).
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,received,received_with_discrepancy,cancelled',
            'discrepancy_notes' => 'nullable|string',
        ]);
        
        // Use a database transaction for the update
        DB::beginTransaction();
        try {
            $oldStatus = $purchaseOrder->status;
            $newStatus = $request->status;
            
            // Update the PO details
            $purchaseOrder->status = $newStatus;
            $purchaseOrder->discrepancy_notes = $request->discrepancy_notes;
            $purchaseOrder->save();

            // If the status is changed to 'received', update the stock levels.
            // This logic prevents stock from being added multiple times if the status is edited again.
            if ($newStatus === 'received' && $oldStatus !== 'received') {
                foreach ($purchaseOrder->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->quantity += $item->quantity;
                        $product->save();
                    }
                }
            }
            
            DB::commit();
            return redirect()->route('purchase_orders.show', $purchaseOrder)->with('success', 'Purchase Order updated successfully.');

        } catch(\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update Purchase Order. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        // Ensure that a received order's stock is not deleted,
        // which would cause inventory discrepancies.
        if($purchaseOrder->status === 'received') {
            return back()->with('error', 'Cannot delete a received order. Please adjust stock manually if needed.');
        }

        $purchaseOrder->delete();
        return redirect()->route('purchase_orders.index')->with('success', 'Purchase Order deleted successfully.');
    }
}
