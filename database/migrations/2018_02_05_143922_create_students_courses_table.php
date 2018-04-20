<?php

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
              ->unsignedInteger('studentId')
              ->nullable(FALSE);
      $table
              ->unsignedSmallInteger('courseId')
              ->nullable(FALSE);
      // An easy workaround for the test only, 4 digits for the year + 1 digit for the semester no.
      $table
              ->string('semester', 8)
              ->nullable(FALSE);
      $table
              ->unsignedTinyInteger('status')
              ->nullable(FALSE);
      $table->timestamps();

      $table
              ->foreign('studentId')
              ->references('id')
              ->on('students')
              ->onDelete('cascade')
              ->onUpdate('cascade');
      $table
              ->foreign('courseId')
              ->references('id')
              ->on('courses')
              ->onDelete('cascade')
              ->onUpdate('cascade');
      // If use semester id instead of CHAR, here should have another foreign key constraint definiation.

      $table->unique(['studentId', 'courseId', 'semester']);
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
