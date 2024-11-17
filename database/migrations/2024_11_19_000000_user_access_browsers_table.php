<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccessBrowsersTable extends Migration
{
    public function up()
    {
        Schema::create('user_access_browsers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_access_record_id')->constrained()->onDelete('cascade');
            $table->string('session_ip'); // Session-specific IP for detailed checks
            $table->string('browser'); // User-Agent for session identification
            $table->string('session_token')->unique()->nullable();
            $table->string('otp')->nullable(); // OTP for session validation
            $table->timestamp('expires_at')->nullable(); // OTP expiration
            $table->timestamp('verified_at')->nullable(); // Session verified at
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('user_access_browsers'); // Drop table on rollback
    }
}
