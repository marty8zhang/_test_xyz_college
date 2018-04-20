<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {

  const STATUS_INACTIVE = 0;
  const STATUS_ACTIVE = 1;
  const STATUS_DISCONTINUED = 2;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'courseCode', 'courseName', 'courseDescription', 'coursePoints', 'status',
  ];
  protected $primaryKey = 'courseCode'; // Specifies a custom primary key column other than the default 'id'.
  public $incrementing = FALSE; // Disables the default incrementing feature of the primary key.
  protected $keyType = 'string'; // If your primary key isn't an integer.
  protected $attributes = [
      'status' => self::STATUS_ACTIVE,
  ];

}
