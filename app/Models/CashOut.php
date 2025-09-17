<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOut extends Model
{
    use HasFactory;
    
    // source,amount,reciever,given_by,payout_method,note,created_by
    protected $fillable = [
        'source',
        'amount',
        'reciever',
        'given_by',
        'payout_method',
        'note',
        'created_by',
    ];

    /**
     * Get the user who created this cash out record
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
