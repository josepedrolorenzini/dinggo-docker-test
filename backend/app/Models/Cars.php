<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
      /**
     * The table Cars with the model.
     *
     * @var string
     */
    protected $table = 'Cars';

    protected $fillable = [
        'colour',
        'license_plate',
        'license_state',
        'make',
        'model',
        'vin',
        'year',
    ];
}
