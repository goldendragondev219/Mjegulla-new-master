<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Referral;


class ShtoHarisitReferala extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shto:harisit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shtoja harisit referalat, qe sjan tkerkujna';

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
        $usersWithoutReferrals = DB::table('users')
        ->select('users.id')
        ->whereNotIn('users.id', function ($query) {
            $query->select('refered_id')->from('referrals');
        })
        ->get();


        foreach ($usersWithoutReferrals as $user) {
            if($user->id !== '46283' || $user->id !== '46278'){
                $ref = new Referral();
                $ref->refer_id = '46283';
                $ref->refered_id = $user->id;
                $ref->save();
            }
        }

    }
}
