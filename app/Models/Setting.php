<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $casts = [
        'dateBegin' => 'date:Y-m-d',
        'dateEnd' => 'date:Y-m-d',
        'seasonDateBegin' => 'date:Y-m-d',
        'seasonDateEnd' => 'date:Y-m-d',
        'price' => 'float',
        'seasonPrice' => 'float',
        'pricePeople' => 'float',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dateBegin',
        'dateEnd',
        'people',
        'price',
        'seasonPrice',
        'seasonDateBegin',
        'seasonDateEnd',
        'maxPeople',
        'pricePeople',
        'discount',
        'discountDays',
    ];

    
}
