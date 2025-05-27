<?php

namespace App\Models;

use App\Traits\AutoResourceCodeGeneration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, AutoResourceCodeGeneration;

    protected $fillable = [
        'role_code',
        'name',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @inheritDoc
     */
    public function getResourceCodeConfig(): array {
        return [
            "column" => "role_code",
            "prefix" => "ROL"
        ];
    }
}
