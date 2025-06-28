<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display the specified sale as an invoice.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        // Eager load the relationships to prevent multiple queries
        $sale->load('items.product', 'customer', 'user');

        // Laravel's route model binding automatically finds the sale or throws a 404 error
        return view('invoice.show', compact('sale'));
    }
}