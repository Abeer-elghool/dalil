<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{Visit};

class SaveVisitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $requestIP;
    /**
     * Create a new job instance.
     */
    public function __construct($requestIP)
    {
        $this->requestIP = $requestIP;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Visit::firstOrCreate(['request_ip'=>$this->requestIP]);
    }
}
