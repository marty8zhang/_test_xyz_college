<?php

/**
 * The MODEL of the students and courses enrollment relationship.
 * @author Marty Zhang
 * @createdAt 3 May 2018, 2:28 PM AEST
 * @version 0.9.201805091412
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
   * Get the name of the "created at" column.
   * Development Note: Due to an issue in the Laravel version before 5.6, using the inherited save() method to update the relationship record via its Model directly will cause an exception being thrown. Hence, a quick workaround is done here by overriding the problematic method concerning $this->pivotParent.
   * @return string
   */
  public function getCreatedAtColumn() {
    return $this->pivotParent ? $this->pivotParent->getCreatedAtColumn() : static::CREATED_AT;
  }

  /**
   * Get the name of the "updated at" column.
   * Development Note: Due to an issue in the Laravel version before 5.6, using the inherited save() method to update the relationship record via its Model directly will cause an exception being thrown. Hence, a quick workaround is done here by overriding the problematic method concerning $this->pivotParent.
   * @return string
   */
  public function getUpdatedAtColumn() {
    return $this->pivotParent ? $this->pivotParent->getUpdatedAtColumn() : static::UPDATED_AT;
  }

  /**
   * Gets the textual meaning of the value of the 'semester' field.
   * Development Note: Because the data of this pivot Model are usually accessed through the 'pivot' attribute of other related Models, making this function static can reduce the overheads of instantiating pivot objects.
   * @var string $semester The value of the 'semester' field.
   * @return string The textual meaning of the value of the 'semester' field.
   */
  public static function getSemesterText($semester) {
    $result = '';

    // Development Note: For now, the last two digits of $this->semester indicate the semester number, the rest of the string indicates the year.
    $result = [substr($semester, 0, -2), substr($semester, -2)];

    switch ($result[1]) {
      case '01':
        $result[1] = 'Semester 1';
        break;

      case '02':
        $result[1] = 'Winter School'; // Welcome to the Southern Hemisphere!
        break;

      case '03':
        $result[1] = 'Semester 2';
        break;

      case '04':
        $result[1] = 'Summer School';
        break;

      default:
        break;
    }

    $result = implode(' ', $result);

    return $result;
  }

  /**
   * Gets the textual meaning of the status number of a student and course enrollment entry.
   * Development Note: Because the data of this pivot Model are usually accessed through the 'pivot' attribute of other related Models, making this function static can reduce the overheads of instantiating pivot objects.
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
