<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\UsersNewsletterEmails as UserMail;
use App\Models\User;


class UsersNewsletterEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:newsletter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Users new features and other updates via email.';

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
        // $users = User::all();

        // $message = 'Ekskluzivisht për ju për 24 orë! Përdorni kodin "<b>MJEGULLA50</b>" gjatë pagesës, për një zbritje prej 50% në dyqanin tuaj ekzistues ose në rastin e krijimit të një dyqani të ri me pakon Bazike apo Premium. Kjo ofertë e veçantë është e vlefshme për të gjithë!<br><br>';

        // $message .= 'Faleminderit,<br>';
        // $message .= 'Gjitha të mirat.<br>';
        // foreach($users as $user){
        //     Mail::to($user->email)->queue(new UserMail($message));
        //     //Log::info('EMAIL --- '.$user->email);
        // }
        

        //Mail::to('arian.demnika@gmail.com')->queue(new UserMail($message));
        
    }
}
