<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property User $from
 * @property User $to
 * @property int $payer
 * @property int $payee
 * @property float $value
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payer',
        'payee',
        'value',
    ];

    public function from()
    {
        return $this->belongsTo(User::class, 'payer');
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'payee');
    }
}
