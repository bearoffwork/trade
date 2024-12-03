<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const users = [
        '藥吹',
        '藥炎',
        '藥頭',
        'Rush',
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

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        foreach (self::users as $name) {
            User::create([
                'name' => $name,
                'password' => Hash::make($name),
                'email' => $name . '@localhost',
            ]);
        }

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
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
};
