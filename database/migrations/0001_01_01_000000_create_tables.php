<?php

use App\Database\Models\FundRecord;
use App\Database\Models\ItemType;
use App\Database\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->index(['id', 'name']);
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->comment('活動');
            $table->string('id')->primary()->comment('活動名稱');
            $table->string('act_desc')->nullable()->comment('活動說明');
            $table->timestamps();
        });

        Schema::create('item_types', function (Blueprint $table) {
            $table->comment('物品分類');
            $table->string('id')->primary()->comment('物品分類名稱');
            $table->string('type_desc')->nullable()->comment('說明');
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->comment('物品');
            $table->id();
            $table->string('item_name');

            $table->string('item_type')->comment('分類');
            $table->foreign('item_type')->references('id')->on('item_types');

            $table->integer('qty')->default(1)->comment('數量');

            $table->string('act_id')->comment('活動名稱');
            $table->foreign('act_id')->references('id')->on('activities');

            $table->decimal('tax_rate')->comment('稅率');
            $table->decimal('fund_rate')->comment('公積金分成');
            $table->integer('total_amt')->nullable()->comment('結標金額');
            $table->integer('posted_amt')->nullable()->comment('實際入帳金額');
            $table->foreignId('buyer_uid')->nullable()->comment('得標者');

            $table->timestamp('drop_at')->comment('物品掉落時間');
            $table->timestamp('close_at')->nullable()->comment('截標時間');
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

        Schema::create('fund_records', function (Blueprint $table) {
            $table->comment('公積金紀錄');
            $table->id();
            $table->nullableMorphs('fundable');
            $table->decimal('amount', 13, 4)->comment('進出金額');
            $table->decimal('balance', 9)->comment('餘額');
            $table->timestamps();
        });

        FundRecord::create([
            'amount' => 0,
            'balance' => 0,
        ]);

        Schema::create('wallet_records', function (Blueprint $table) {
            $table->comment('錢包紀錄');
            $table->id();
            /** @see \App\Enums\WalletRecordCategory */
            $table->tinyInteger('category')->comment('紀錄分類 1: 分潤, 2: 提款');
            $table->foreignId('uid')->comment('公積金 = null')
                ->constrained('users');
            $table->foreignId('iid')->nullable()->comment('交易紀錄有 item id')
                ->constrained('items');
            $table->foreignId('fid')->nullable()->comment('公積金紀錄 id, 對帳用')
                ->constrained('fund_records');
            $table->decimal('amount', 13, 4)->comment('進出金額');
            $table->decimal('balance', 9)->comment('餘額');
            $table->timestamps();
        });

        $this->insertDefaultData();
        $this->createBuiltinTables();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }

    public function createBuiltinTables(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function insertDefaultData(): void
    {
        $users = [
            '藥插',
            '藥吹',
            '藥炎',
            '藥頭',
            'Rushh',
            '藥夯',
            '藥嗨',
            '藥奶',
            '藥去了',
            '藥涼',
            '森上',
            'Bear',
            'Machillz',
            '藥精',
            '烏拉妮雅',
            '膏肓痛痛丸',
            '紅黑單雙',
            '很秀阿a',
            'KOKE',
            'YHao',
            '武翠紅',
        ];

        $passwd = Hash::make('1234');
        User::insert(collect($users)
            ->map(static fn($name) => [
                'name' => $name,
                'password' => $passwd,
                'email' => $name.'@localhost',
                'created_at' => now(),
                'updated_at' => now(),
            ])
            ->toArray()
        );

        ItemType::insert([
            ['id' => '武器'],
            ['id' => '頭盔'],
            ['id' => '上衣'],
            ['id' => '下衣'],
            ['id' => '手套'],
            ['id' => '鞋子'],
            ['id' => '披風'],
            ['id' => '項鍊'],
            ['id' => '腰帶'],
            ['id' => '耳環'],
            ['id' => '戒指'],
            ['id' => '印章'],
            ['id' => '天堂石'],
            ['id' => '稀有技能強化石'],
            ['id' => '技能書'],
            ['id' => '紫頭盔'],
            ['id' => '紫上衣'],
            ['id' => '紫下衣'],
            ['id' => '紫手套'],
            ['id' => '紫鞋子'],
            ['id' => '紫披風'],
            ['id' => '紫項鍊'],
            ['id' => '紫腰帶'],
            ['id' => '紫耳環'],
            ['id' => '紫戒指'],
            ['id' => '紫印章'],
            ['id' => '紫天堂石'],
        ]);
    }
};
