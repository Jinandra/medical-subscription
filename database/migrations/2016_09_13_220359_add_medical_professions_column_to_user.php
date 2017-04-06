<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMedicalProfessionsColumnToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users', function ($table) {
        $table->string('medical_profession');
        $table->string('medical_degree');
        $table->mediumText('office_address');
        $table->string('website_type', 16);
        $table->string('profile_website_url');
        $table->string('website_url');

        $table->dateTime('verified_at');
        $table->dateTime('declined_at');
        $table->mediumText('decline_message');

        $table->dropColumn('is_verified');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('users', function ($table) {
        $table->dropColumn('medical_profession');
        $table->dropColumn('medical_degree');
        $table->dropColumn('office_address');
        $table->dropColumn('website_type');
        $table->dropColumn('profile_website_url');
        $table->dropColumn('website_url');

        $table->dropColumn('verified_at');
        $table->dropColumn('declined_at');
        $table->dropColumn('decline_message');

        $table->boolean('is_verified');
      });
    }
}
