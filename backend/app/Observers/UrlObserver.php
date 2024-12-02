<?php

namespace App\Observers;

use App\Models\Url;
use Illuminate\Support\Facades\Redis;

class UrlObserver
{
    /**
     * Handle the Url "created" event.
     */
    public function created(Url $url): void
    {
        $data = [
            "expired" => 0,
            "url" => $url->url
        ];
        Redis::set($url->short_code, json_encode($data));
    }

    /**
     * Handle the Url "updated" event.
     */
    public function updated(Url $url): void
    {
        $data = [
            "expired" => $url->expired,
            "url" => $url->url
        ];
        Redis::set($url->short_code, json_encode($data));
    }

    /**
     * Handle the Url "deleted" event.
     */
    public function deleted(Url $url): void
    {
        Redis::del($url->shortCode);
    }
}
