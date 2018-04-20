<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'studentId', 'firstName', 'lastName', 'middleName', 'birthday', 'homeAddress', 'contactNumber', 'studentEmailAddress', 'personalEmailAddress', 'status',
  ];
  protected $primaryKey = 'studentId'; // Specifies a custom primary key column other than the default 'id'.
  public $incrementing = FALSE; // Disables the default incrementing feature of the primary key.
  protected $keyType = 'string'; // If your primary key isn't an integer.

}
