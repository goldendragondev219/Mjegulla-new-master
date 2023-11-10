<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PasswordReset;
use Carbon\Carbon;

class DeleteResetPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:resetPasswords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete reset password codes older than 10 minutes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        PasswordReset::where('created_at', '<', now()->subMinutes(10))->delete();
    }
}
