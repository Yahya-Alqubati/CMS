<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'description',
        'card_id'
    ];

    public function transactions(){
        return $this->morphOne(Transaction::class,'transaction');
    }

    public function Card(){
        return $this->belongsTo(Card::class);
    }
}
