<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // source,amount,reciever,given_by,payout_method,note,created_by
        Schema::create('cash_outs', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->float('amount', 15, 2);
            $table->string('reciever');
            $table->string('given_by');
            $table->string('payout_method');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_outs');
    }
};
