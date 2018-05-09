<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

//class Test extends Model {
class Test extends Pivot {

  protected $table = 'tests';

  /**
   * The attributes that are mass assignable.
   * @var array
   */
  protected $fillable = [
      'name',
  ];

}
