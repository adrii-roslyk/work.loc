<?php

use App\Models\Organization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            //$table->string('status');
            $table->string('vacancy_name');
            $table->unsignedInteger('workers_amount');
            //$table->unsignedInteger('workers_booked');

            $table->foreignIdFor(Organization::class, 'organization_id');
            //$table->foreignId('organization_id')->constrained('organizations');
            $table->unsignedInteger('salary');
            $table->timestamps();
            $table->softDeletes();
            //$table->json('workers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancies');
    }
}
