<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('position');
            $table->string('phone_number')->nullable();
            $table->string('email')->unique()->nullable();
            $table->date('hire_date');
            
            // THE FIX: Added a column to store the path to the CV file
            $table->string('cv_path')->nullable();

            $table->boolean('is_active')->default(true);
            
            $table->foreignId('user_id')->nullable()->unique()->constrained()->onDelete('set null');

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
        Schema::dropIfExists('employees');
    }
}
