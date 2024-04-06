<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialMediaLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'platform',
        'url'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
