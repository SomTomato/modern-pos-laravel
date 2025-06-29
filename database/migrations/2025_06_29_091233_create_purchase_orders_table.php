<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            
            // THE FIX: Added more descriptive status options to handle discrepancies
            $table->string('status')->default('pending'); // e.g., pending, received, received_with_discrepancy, cancelled

            $table->decimal('total_cost', 10, 2)->default(0.00);
            $table->text('notes')->nullable(); // General notes for the order

            // THE FIX: Added a new column for comments about over/missing items
            $table->text('discrepancy_notes')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
};
