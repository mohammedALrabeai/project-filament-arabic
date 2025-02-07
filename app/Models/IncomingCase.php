<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_number',
        'year',
        'general_number',
        'subject',
        'is_serious',
        'plaintiff',
        'defendant',
        'authority',
    ];

    // علاقة ربط حالة واردة بقضية منتهية (علاقة واحد لواحد)
    public function terminatedCase()
    {
        return $this->hasOne(TerminatedCase::class, 'general_number', 'general_number');
    }
}
