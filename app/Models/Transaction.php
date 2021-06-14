<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property User $payer
 * @property User $payee
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
}
