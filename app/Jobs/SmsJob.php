<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $mobile, $text;

    public function __construct($mobile, $text)
    {
        $this->mobile = $mobile;
        $this->text = $text;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

    }
}
