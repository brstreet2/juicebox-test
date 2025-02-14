<?php

namespace App\Console\Commands;

use App\Jobs\WelcomeEmailJob;
use Illuminate\Console\Command;

class DispatchWelcomeEmailJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:welcome {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch a welcome email job for the specified email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            WelcomeEmailJob::dispatch($email);
            $this->info("Welcome email job dispatched for: {$email}");
        } else {
            $this->error("Invalid email address: {$email}");
        }
    }
}
