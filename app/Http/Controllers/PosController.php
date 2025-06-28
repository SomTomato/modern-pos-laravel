<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    /**
     * Display the POS terminal interface.
     */
    public function index()
    {
        $products = Product::where('is_active', 1)
                           ->where('quantity', '>', 0)
                           ->orderBy('name')
                           ->get();

        return view('pos.terminal', compact('products'));
    }

    /**
     * Search for customers via AJAX request.
     */
    public function searchCustomers(Request $request)
    {
        $term = $request->input('term', '');

        if (empty(trim($term))) {
            $customers = Customer::where('id', '!=', 1)
                                 ->orderBy('name')
                                 ->limit(15)
                                 ->get(['id', 'name', 'phone_number']);
        } else {
            $customers = Customer::where('id', '!=', 1)
                                ->where(function ($query) use ($term) {
                                    $query->where('name', 'LIKE', '%' . $term . '%')
                                          ->orWhere('phone_number', 'LIKE', '%' . $term . '%');
                                })
                                ->limit(15)
                                ->get(['id', 'name', 'phone_number']);
        }

        return response()->json($customers);
    }

    /**
     * Process and store a new sale via AJAX request.
     */
    public function processSale(Request $request)
    {
        $data = $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric',
            'customerId' => 'required|exists:customers,id',
            'paymentMethod' => 'required|string',
            'paymentProvider' => 'nullable|string',
        ]);

        try {
            $sale = DB::transaction(function () use ($data) {

                $total_amount = 0;
                foreach ($data['cart'] as $item) {
                    $total_amount += $item['price'] * $item['quantity'];
                }

                // Create the main sale record.
                // The Sale Model now handles the 'sale_date' automatically.
                $sale = Sale::create([
                    'user_id' => Auth::id(),
                    'customer_id' => $data['customerId'],
                    'total_amount' => $total_amount,
                    'payment_method' => $data['paymentMethod'],
                    'payment_provider' => $data['paymentProvider'],
                ]);

                // Create sale items and update product stock
                foreach ($data['cart'] as $item) {
                    $sale->items()->create([
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price_per_unit' => $item['price'],
                    ]);

                    $product = Product::find($item['id']);
                    $product->quantity -= $item['quantity'];
                    $product->save();
                }
                
                return $sale;
            });

            return response()->json(['success' => true, 'sale_id' => $sale->id]);

        } catch (\Exception $e) {
            Log::error('Sale Processing Failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to process sale. Please try again.'], 500);
        }
    }
}
