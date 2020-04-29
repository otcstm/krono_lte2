<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaidOtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_ot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('period', 6);
            $table->date('period_dt');
            $table->date('pay_date')->nullable();
            $table->string('wagetype', 6);
            $table->string('wage_descr', 6);
            $table->decimal('amount', 5, 2)->default(0.0);
            $table->dateTime('upd_dm')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'period', 'wagetype'], 
            'paidot_idx_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paid_ot');
    }
}
/**
  
*  PERS_NO NUMBER(8, 0) , FOR_PERIOD VARCHAR2(6 BYTE) 
*, PAYMENT_DATE VARCHAR2(8 BYTE) , WAGE_TYPE VARCHAR2(4 BYTE) 
*, WAGE_DESCR VARCHAR2(25 BYTE) , NO_HOURS_DAYS NUMBER(4, 2) 
*, AMOUNT NUMBER(5, 2) , LAST_UPD_DT DATE 
 
 */
