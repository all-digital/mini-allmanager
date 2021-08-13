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
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('login')->unique();

            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->string('company', 150);
            $table->string('company_fantasy', 150);
            $table->string('state_registration', 50)->nullable();
            $table->string('municipal_registration', 50)->nullable();
            $table->string('cpf_cnpj', 20)->unique();

            $table->string('address', 80)->default('sem endereço');
            $table->integer('number')->default('00');
            $table->string('complement', 150)->nullable();
            $table->string('city', 50)->default('sem cidade');
            $table->string('neighborhood', 70)->nullable();
            $table->string('state', 3)->default('xxx');
            $table->string('country', 50)->default('xxx');

            
            $table->string('password');
            $table->rememberToken();
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
}
