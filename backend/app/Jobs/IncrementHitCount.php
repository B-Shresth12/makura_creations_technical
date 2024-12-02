<?php

namespace App\Jobs;

use App\Models\Url;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class IncrementHitCount implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $shortCode)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = Url::find($this->shortCode);

        $url->hit_count = $url->hit_count + 1;
        $url->update();
    }
}
