<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class SocialMediaLink extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'company_id',
        'platform',
        'url'
    ];

    public function toSearchableArray(): array
    {
        return $this->toArray();
    }

//    public function company(): BelongsTo
//    {
//        return $this->belongsTo(Company::class);
//    }
}
