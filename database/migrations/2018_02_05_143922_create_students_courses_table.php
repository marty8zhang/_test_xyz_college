<?php

/**
 * The Migration for the students and courses enrollment MODEL.
 * @author Marty Zhang
 * @version 0.9.201805031614
 */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsCoursesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('students_courses', function (Blueprint $table) {
      $table->increments('id');
      $table
              ->unsignedInteger('sId')
              ->nullable(FALSE);
      $table
              ->unsignedSmallInteger('cId')
              ->nullable(FALSE);
      // An easy workaround for test-driving the whole project only. Minimum 5 digits - 4 digits for the year plus 1 digit for the semester number -.
      $table
              ->string('semester', 8)
              ->nullable(FALSE);
      $table
              ->unsignedTinyInteger('status')
              ->nullable(FALSE);
      $table->timestamps();

      $table
              ->foreign('sId')
              ->references('id')
              ->on('students')
              ->onDelete('cascade')
              ->onUpdate('cascade');
      $table
              ->foreign('cId')
              ->references('id')
              ->on('courses')
              ->onDelete('cascade')
              ->onUpdate('cascade');
      // If use semester id instead of CHAR, here should have another foreign key constraint definiation.

      $table->unique(['sId', 'cId', 'semester']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('students_courses');
  }

}
