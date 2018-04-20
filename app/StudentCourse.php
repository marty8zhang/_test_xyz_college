<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model {

  protected $table = 'students_courses'; // Specifies a custom table for the Model.
  protected $fillable = [
      'firstName', 'lastName', 'middleName', 'homeAddress', 'contactNumber', 'studentEmailAddress', 'personalEmailAddress', 'status',
  ];

}
