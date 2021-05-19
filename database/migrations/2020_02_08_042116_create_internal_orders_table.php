<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_orders', function (Blueprint $table) {
          $table->string('id',100)->primary('id');
          $table->string('descr', 100)->nullable();
          $table->string('order_type', 10)->nullable();
          $table->string('status', 1)->nullable();
          $table->string('cost_center', 20)->nullable();
          $table->string('company_code', 10)->nullable();
          $table->string('pers_responsible', 40)->nullable();
          $table->decimal('budget',10,2)->default(0.00);
          $table->dateTime('upd_dm')->nullable();
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
        Schema::dropIfExists('internal_orders');
    }
}
