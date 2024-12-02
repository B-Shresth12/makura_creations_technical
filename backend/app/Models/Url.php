<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\UrlObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([UrlObserver::class])]
class Url extends Model
{
    protected $primaryKey = 'short_code';
    protected $keyType = 'string';
    public $incrementing = false;


    protected $fillable = [
        "url",
        "expired",
        "short_code",
        "hit_count",
        "expires_at",
    ];

    protected static function booted()
    {
        static::creating(function ($url) {
            if (!@$url->expires_at) {
                $url->expires_at = now()->addYears(5)->format('Y-m-d');
            }
        });
    }

    public function scopeFilter($query, $filter){
        if(@$filter['expiryCheck']){
            $query->where('expired', 0)->where('expires_at', "<", today()->format('Y-m-d'));
        }
    }
}
