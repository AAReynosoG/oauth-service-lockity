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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->nullable(false);
            $table->string('name', 100)->nullable(false);
            $table->string('last_name', 100)->nullable(false);
            $table->string('second_last_name', 100)->nullable(false);
            $table->string('email', 100)->nullable(false)->unique();
            $table->boolean('has_email_verified')->default(false);
            $table->timestamp('verification_link_sent_at')->nullable();
            $table->string('password')->nullable(false);
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
        Schema::dropIfExists('users');
    }
};
