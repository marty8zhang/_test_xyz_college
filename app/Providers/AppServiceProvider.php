<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider {

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot() {
    // Provides a better compatibility to the utf8mb4 character set.
    Schema::defaultStringLength(191);

    // Registers custom validation rules.
    // Usage Example: 'requestParameter1' => 'uniqueMultipleFields:databaseTable,tableFieldForParameter1,tableFieldForParameter2,ValueOfParameter2,...'
    Validator::extend('uniqueMultipleFields', function ($attribute, $value, $parameters, $validator) {
      $result = FALSE;

      if (count($parameters) < 4 || empty($parameters[0]) || empty($parameters[1]) || empty($parameters[2]) || $parameters[3] == '') { // At lease two fields need to be provided.
        throw new ValidationException($validator, 'The given data failed to pass validation.');
      } else {
        $query = DB::table($parameters[0])
                ->where($parameters[1], $value)
                ->where($parameters[2], $parameters[3]);

        for ($i = 4; $i < count($parameters) - 4; $i += 2) {
          if (empty($parameters[$i]) || !isset($parameters[$i + 1])) {
            throw new ValidationException($validator, 'The given data failed to pass validation.');
          } else {
            $query->where($parameters[$i], $parameters[$i + 1]);
          }
        }

        $result = $query->count() === 0;
      }

      return $result;
    });

    Validator::replacer('uniqueMultipleFields', function ($message, $attribute, $rule, $parameters) {
      $fieldNames = $parameters[1];

      for ($i = 2; $i < count($parameters); $i += 2) {
        $fieldNames .= ", {$parameters[$i]}";
      }
      $fieldNames = substr_replace($fieldNames, ', and', strrpos($fieldNames, ','), 1);

      return str_replace(':uniqueFields', $fieldNames, $message);
    });
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register() {
    //
  }

}
