<?php

/**
 * The MODEL of the students and courses enrollment relationship.
 * @author Marty Zhang
 * @createdAt 3 May 2018, 2:28 PM AEST
 * @version 0.9.201805091114
 */

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

class StudentsCourses extends Pivot {

  const STATUS_CANCELLED_BY_ADMIN = 0;
  const STATUS_ENROLLED = 1;
  const STATUS_DROPPED_OUT = 2;

  protected $table = 'students_courses';

  /**
   * The attributes that are mass assignable.
   * @var array
   */
  protected $fillable = [
      'sId', 'cId', 'semester', 'status',
  ];
  protected $attributes = [
      'status' => self::STATUS_ENROLLED, // Gives the student course enrollment status a default value.
  ];

  /**
   * Save the model to the database.
   * Development Note: Due to an issue in the Laravel version before 5.6, using the inherited save() method to update the relationship record via its Model directly will cause an exception being thrown. Hence, a quick workaround is done here by overriding the parent method.
   * To-do.
   * @param  array  $options
   * @return bool
   */
  public function save(array $options = array()) {

  }

  /**
   * Gets the textual meaning of the status number of a student and course enrollment entry.
   * @var integer $status The status number.
   * @return string The textual meaning of the status number of a student and course enrollment entry; or a warning message otherwise.
   */
  public static function getStatusText($status) {
    $result = '';

    switch ($status) {
      case self::STATUS_CANCELLED_BY_ADMIN:
        $result = 'Cancelled';
        break;

      case self::STATUS_ENROLLED:
        $result = 'Enrolled';
        break;

      case self::STATUS_DROPPED_OUT:
        $result = 'Dropped out';
        break;

      default:
        $result = "The status number '{$status}' of the student and course enrollment cannot be recognised.";
    }

    return $result;
  }

}
