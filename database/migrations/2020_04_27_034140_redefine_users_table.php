<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RedefineUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('api_token', 80)
                  ->unique()
                  ->nullable()
                  ->default(Str::random(80));
            $table->string('name');
            $table->string('photo')->default('default-photo.jpg');
            $table->string('self_expectation')->default('*');
            $table->string('job_title')->default('*');
            $table->string('department')->default('*');
            $table->string('office_location')->default('*');
            $table->string('extension')->default('*');
            $table->tinyInteger('active_group')->nullable();
            $table->timestamps();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
}
