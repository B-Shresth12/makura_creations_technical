<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\UrlObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
 
#[ObservedBy([UrlObserver::class])]
class Url extends Model
{
    protected $fillable = [
        "url",
        "short_code",
        "hit_count",
        "expires_at",
    ];

    protected static function booted()
    {
        static::creating(function ($url) {
            $url->expires_at = now()->addYears(5)->format('Y-m-d');
        });
    }
}
