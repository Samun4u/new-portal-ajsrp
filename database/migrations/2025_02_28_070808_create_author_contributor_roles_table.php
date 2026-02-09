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
        Schema::create('author_contributor_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_order_submission_id');
            $table->unsignedBigInteger('author_details_id');
            $table->unsignedBigInteger('contributor_role_id');
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
        Schema::dropIfExists('author_contributor_roles');
    }
};
