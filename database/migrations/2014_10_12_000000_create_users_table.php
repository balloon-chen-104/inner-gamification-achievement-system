<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            // $table->string('photo')->nullable();
            $table->string('self_expectation')->nullable();
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->string('office_location')->nullable();
            $table->integer('extension')->nullable();
            // $table->tinyInteger('active_group')->nullable();
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
