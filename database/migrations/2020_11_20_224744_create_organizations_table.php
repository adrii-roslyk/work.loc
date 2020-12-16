<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->id();
                $table->string('title')->unique();
                $table->string('city', 100);
                $table->string('country', 100);

                $table->foreignIdFor(User::class, 'user_id');

                $table->timestamps();
                //$table->json('creator');
                $table->softDeletes();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations');
    }
}
