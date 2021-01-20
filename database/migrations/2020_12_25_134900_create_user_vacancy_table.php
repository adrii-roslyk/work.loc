<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVacancyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vacancy', function (Blueprint $table) {
            $table->primary(['user_id','vacancy_id']);
            //$table->foreignIdFor(User::class, 'user_id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            //$table->foreignIdFor(Vacancy::class, 'vacancy_id');
            $table->bigInteger('vacancy_id')->unsigned()->nullable();
            $table->foreign('vacancy_id')
                ->references('id')
                ->on('vacancies')
                ->onDelete('cascade');
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
        Schema::dropIfExists('user_vacancy');
    }
}
