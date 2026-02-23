<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    //DB::table('quotes')
    protected $table = 'quotes';
    protected $fillable = [
        'car_id',
        'overviewOfWork',
        'price',
        'repairer'
    ];
}
