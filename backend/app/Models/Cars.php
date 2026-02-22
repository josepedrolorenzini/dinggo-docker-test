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
    protected $table = 'cars';

    protected $fillable = [
        'colour',
        'licensePlate',
        'licenseState',
        'make',
        'model',
        'vin',
        'year',
    ];
}
