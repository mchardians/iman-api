<?php

namespace App\Models;

use App\Traits\AutoResourceCodeGeneration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfaqType extends Model
{
    use HasFactory, AutoResourceCodeGeneration;
    protected $fillable = [
        'infaq_type_code',
        'name',
        'description',
    ];

    /**
     * @inheritDoc
     */
    public function getResourceCodeConfig(): array {
        return [
            "column" => "infaq_type_code",
            "prefix" => "IFTP"
        ];
    }
}
