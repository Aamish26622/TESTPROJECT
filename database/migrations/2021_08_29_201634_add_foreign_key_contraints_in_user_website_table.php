<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyContraintsInUserWebsiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_website', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->index()->change();
            $table->unsignedBigInteger('website_id')->index()->change();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onDelete('cascade');
            $table->foreign('website_id')
                ->references('id')
                ->on('websites')
                ->onDelete('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_website', function (Blueprint $table) {
            //
        });
    }
}
