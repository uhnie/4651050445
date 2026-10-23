<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->increments('IdUser');
            $table->string('UserName', 255)->unique();
            $table->string('Password', 255);
            $table->text('Token')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
