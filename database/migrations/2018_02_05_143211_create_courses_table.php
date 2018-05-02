<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('courses', function (Blueprint $table) {
      $table->smallIncrements('id');
      $table
              ->string('courseCode', 20)
              ->nullable(FALSE)
              ->unique();
      $table
              ->string('courseName')
              ->nullable(FALSE);
      $table
              ->mediumText('courseDescription')
              ->nullable(FALSE);
      $table
              ->unsignedTinyInteger('coursePoints')
              ->nullable(FALSE);
      $table
              ->unsignedTinyInteger('status')
              ->nullable(FALSE);
      // And more...
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('courses');
  }

}
