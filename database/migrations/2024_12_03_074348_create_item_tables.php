<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('boss_name')->comment('boss名稱');
            $table->dateTime('drop_time')->comment('物品掉落時間');
            $table->foreignId('uid')->comment('填單人員')->constrained('users');
            $table->integer('amount')->nullable()->comment('入帳金額');
            $table->dateTime('settle_time')->nullable()->comment('結算時間');
            $table->timestamps();
        });

        //參加人員
        Schema::create('item_users', function (Blueprint $table) {
            $table->foreignId('iid')->constrained('items');
            $table->foreignId('uid')->constrained('users');
        });

        //分配紀錄
        Schema::create('item_records', function (Blueprint $table) {
            $table->foreignId('iid')->constrained('items');
            $table->foreignId('uid')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
