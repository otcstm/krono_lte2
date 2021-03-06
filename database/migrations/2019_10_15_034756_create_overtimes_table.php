<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('refno')->unique();
            $table->integer('user_id');
            $table->integer('month_id');
            $table->date('date');
            $table->date('date_created');
            $table->date('date_expiry')->nullable();
            $table->integer('total_hour')->default(0);
            $table->integer('total_minute')->default(0);
            $table->decimal('amount', 10,2)->default(0);
            $table->integer('profile_id')->nullable();
            $table->string('status')->default("D1");
            $table->string('company_id')->nullable();
            $table->string('state_id')->nullable();
            $table->integer('approver_id')->unsigned()->nullable();
            $table->integer('verifier_id')->unsigned()->nullable();            
            $table->integer('daytype_id')->nullable();
            $table->string('charge_type')->nullable();
            $table->datetime('approved_date')->nullable();
            $table->datetime('verification_date')->nullable();
            $table->datetime('queried_date')->nullable();
            $table->integer('queried_id')->nullable();
            $table->datetime('payment_date')->nullable();
            $table->string('region', 25)->nullable();
            $table->string('wage_type')->nullable();
            $table->integer('punch_id')->nullable();
            $table->integer('user_records_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtimes');
    }
}
