<?php

use App\Models\Role;
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
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('role', 50)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name', 20)->nullable();
            $table->string('last_name', 40)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('phone', 30)->nullable();


            //$table->foreignIdFor(Role::class, 'role_id')->default(1);
            $table->timestamps();
            $table->softDeletes();

            //$table->timestamp('email_verified_at')->nullable();
            //$table->rememberToken();

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
