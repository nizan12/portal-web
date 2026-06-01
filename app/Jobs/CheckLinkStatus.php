<?php

namespace App\Jobs;

use App\Models\Link;
use App\Services\LinkStatusChecker;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckLinkStatus implements ShouldQueue
{
    use Queueable;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 2;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 60;

    public function __construct(
        public int $linkId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(LinkStatusChecker $checker): void
    {
        $link = Link::find($this->linkId);

        if (! $link) {
            return;
        }

        $statusData = $checker->check($link);
        $link->update($statusData);
    }
}
