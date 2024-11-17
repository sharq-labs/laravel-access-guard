<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccessRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_access_records', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable()->unique();
            $table->string('primary_ip')->nullable(); // User's main IP for IP-only checks
            $table->boolean('is_whitelisted')->default(false); // User's record never expires
            $table->timestamp('last_verified_at')->nullable(); // Last verification timestamp
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
        Schema::dropIfExists('user_access_records'); // Correct table name
    }
}
