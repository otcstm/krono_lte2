<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_orders', function (Blueprint $table) {
            $table->string('id',100)->primary('id');
            $table->string('descr', 100)->nullable();
            $table->string('type', 10)->nullable();
            $table->string('status', 8)->nullable();
            $table->string('cost_center', 20)->nullable();
            $table->string('company_code', 10)->nullable();
            $table->string('approver_id', 20)->nullable();
            $table->decimal('budget',10,2)->default(0.00);
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
        Schema::dropIfExists('maintenance_orders');
    }
}
