<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailQueuer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $to;
    public $data;
    public $cc;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $to, $data, array $cc = [])
    {
        $this->to = $to;
        $this->data = $data;
        $this->cc = $cc;
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [1, 5, 10];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        count($this->cc) ?
            Mail::to($this->to)->cc($this->cc)->send($this->data) :
            Mail::to($this->to)->send($this->data);
    }
}
