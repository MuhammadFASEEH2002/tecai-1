<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EPlan extends Model
{
    use HasFactory;

    protected $table = 'e_plan';
    protected $fillable = [
        'plan_name',
        'plan_details',
        'plan_price',
    ];
}
