<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfaqType extends Model
{
    use HasFactory;
    protected $fillable = [
        'infaq_type_code',
        'name',
        'description',
    ];
}
