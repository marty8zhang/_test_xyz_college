<?php

/**
 * The MODEL of students.
 * @author Marty Zhang
 * @version 0.9.201805071048
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {

  const STATUS_DISMISSED = 0;
  const STATUS_ENROLLED = 1;
  const STATUS_SUSPENDED = 2;
  const STATUS_DROPPED_OUT = 3;

  /**
   * The attributes that are mass assignable.
   * @var array
   */
  protected $fillable = [
      'studentId', 'firstName', 'lastName', 'middleName', 'birthday', 'homeAddress', 'contactNumber', 'studentEmailAddress', 'personalEmailAddress', 'status',
  ];
  protected $primaryKey = 'studentId'; // Specifies a custom primary key column other than the default 'id'.
  public $incrementing = FALSE; // Disables the default incrementing feature of the primary key.
  protected $keyType = 'string'; // If your primary key isn't an integer.

  /**
   * Gets the courses that the student have/were enrolled into.
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */

  public function courses() {
    return $this->belongsToMany('App\Course', 'students_courses', 'sId', 'cId', 'id', 'id') // Development Note: The last 4 parameters are necessary because the foreign key constraints in the actual relationship database table are not referencing the primary key fields of the Models, which violates the default values of belongsToMany().
                    ->withPivot('id', 'semester', 'status')
                    ->withTimestamps()
                    ->using('App\StudentsCourses');
  }

  /**
   * Gets the textual meaning of the status number of a student.
   * @return string The textual meaning of the status number of a student; or a warning message otherwise.
   */
  public function getStatusText() {
    $result = '';

    switch ($this->status) {
      case self::STATUS_DISMISSED:
        $result = 'Dismissed';
        break;

      case self::STATUS_ENROLLED:
        $result = 'Enrolled';
        break;

      case self::STATUS_SUSPENDED:
        $result = 'Suspended';
        break;

      case self::STATUS_DROPPED_OUT:
        $result = 'Dropped out';
        break;

      default:
        $result = "The status number '{$this->status}' of the student cannot be recognised.";
    }

    return $result;
  }

}
