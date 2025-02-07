<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminatedCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'general_number',
        'verdict_number',
        'verdict_date',
        'verdict_method',
        'draft_editor',
    ];

    // علاقة ربط القضية المنتهية بحالة واردة
    public function incomingCase()
    {
        return $this->belongsTo(IncomingCase::class, 'general_number', 'general_number');
    }
}
