<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid()->after('id');

            $table->after('email_verified_at', function (Blueprint $table) {
                $table->string('avatar')->nullable();
                $table->foreignId('banner_id')->constrained()->cascadeOnDelete();
                $table->text('biography')->nullable();
                $table->string('daily_status', 60)->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
