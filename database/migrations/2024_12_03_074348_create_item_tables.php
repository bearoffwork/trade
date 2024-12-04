<?php

use App\Models\ItemType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *  Storage size of DECIMAL(M, D)
 *  M = 數字總長度
 *  D = 浮點長度
 *  B() 長度對照 byte size
 *
 *  B(1..2) = 1 byte
 *  B(3..4) = 2 bytes
 *  B(5..6) = 3 bytes
 *  B(7..9) = 4 bytes
 *
 *  DECIMAL(13, 4)   = B(13 - 4) + B(4)
 *                   = B(9) + B(4)
 *                   = 4 + 2 = 6 bytes
 */
return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_types', function (Blueprint $table) {
            $table->comment('物品分類');
            $table->string('id')->primary()->comment('物品分類名稱');
            $table->string('type_desc')->nullable()->comment('說明');
            $table->timestamps();
        });

        // TODO
        ItemType::insert([
            ['id' => '素材']
        ]);

        Schema::create('activities', function (Blueprint $table) {
            $table->comment('活動');
            $table->string('id')->primary()->comment('活動名稱');
            $table->string('act_desc')->nullable()->comment('活動說明');
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->comment('物品');
            $table->id();
            $table->string('item_name');

            $table->string('item_type')->comment('分類');
            $table->foreign('item_type')->references('id')->on('item_type');

            $table->integer('qty')->default(1)->comment('數量');

            $table->string('act_id')->comment('活動名稱');
            $table->foreign('act_id')->references('id')->on('activities');

            $table->integer('amount')->nullable()->comment('入帳金額');
            $table->foreignId('buyer_uid')->nullable()->comment('得標者');

            $table->timestamp('drop_at')->comment('物品掉落時間');
            $table->timestamp('close_at')->comment('截標時間');
            $table->timestamp('pay_at')->nullable()->comment('繳費時間');
            $table->foreignId('create_uid')->comment('填單人員')->constrained('users');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('item_users', function (Blueprint $table) {
            $table->comment('參加人員 中間表');
            $table->foreignId('iid')->constrained('items');
            $table->foreignId('uid')->constrained('users');
        });

        Schema::create('user_money', function (Blueprint $table) {
            $table->comment('錢包紀錄');
            $table->id();
            $table->foreignId('iid')->constrained('items');
            $table->foreignId('uid')->constrained('users');
            $table->decimal('amount', 13, 4)->comment('進出金額');
            $table->decimal('balance', 9)->comment('餘額');
            $table->timestamps();
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
