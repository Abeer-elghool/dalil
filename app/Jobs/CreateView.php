<?php

namespace App\Jobs;

use App\Services\ViewService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateView implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;
    public $model_path;

    /**
     * Create a new job instance.
     */
    public function __construct($model, $model_path)
    {
        $this->model = $model;
        $this->model_path = $model_path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ViewService::view($this->model->id, $this->model, $this->model_path);
    }
}
