<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeInfaqTransaction extends Model
{
    use HasFactory; 
    protected $table = 'income_infaq_transactions';
    protected $fillable = ['infaq_type_id', 'transaction_code', 'name', 'amount'];
    public function infaqType()
    {
        return $this->belongsTo(InfaqType::class, 'infaq_type_id');
    }
}
