<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('students', function (Blueprint $table) {
      $table->increments('id');
      $table
              ->string('studentId', 12)
              ->nullable(FALSE)
              ->unique();
      $table
              ->string('firstName')
              ->nullable(FALSE);
      $table
              ->string('lastName')
              ->nullable(FALSE);
      $table->char('middleName');
      $table
              ->date('birthday')
              ->nullable(FALSE);
      $table
              ->text('homeAddress')
              ->nullable(FALSE);
      $table
              ->string('contactNumber')
              ->nullable(FALSE);
      $table
              ->string('studentEmailAddress')
              ->nullable(FALSE);
      $table
              ->string('personalEmailAddress')
              ->nullable(FALSE);
      $table
              ->unsignedTinyInteger('status')
              ->nullable(FALSE);
      // And more...
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('students');
  }

}
