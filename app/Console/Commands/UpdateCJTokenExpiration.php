<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\CJTokenExpired;
use Illuminate\Support\Facades\Mail;
use App\Models\CjDropshipping;
use App\Models\User;
use Carbon\Carbon;

class UpdateCJTokenExpiration extends Command
{
    protected $signature = 'update:cj_token';

    protected $description = 'Update CJ token when expired';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $expired_tokens = CjDropshipping::where('token_exp_date', '<=', now())->get();

        foreach ($expired_tokens as $expired) {
            $api_key = $expired->api_key;

            $session = DB::table('sessions')
            ->select(DB::raw('*'))
            ->where('user_id', $expired->user_id)
            ->first();
            if($session){
                $cj_data = $this->getAccessToken($expired->email, $api_key, $session->ip_address);
            }else{
                $cj_data = $this->getAccessToken($expired->email, $api_key, '1.1.1.1');
            }
            

            if ($cj_data) {
                Log::info('CJ DATA ---- '.json_encode($cj_data));
                if($cj_data['message'] === 'User not found'){
                    //send email to the user, and delete the old api key
                    $user = User::find($expired->user_id);
                    Mail::to($user->email)->queue(new CJTokenExpired($user->default_language));

                    // Log the error and handle it as needed.
                    Log::error('CJ API KEY UPDATE USER NOT FOUND: ' . $expired->email);
                    $expired->delete();
                }else{
                    // Handle the access token, for example, update the database with the new token.
                    $accessToken = $cj_data['data']['accessToken'];
                    $accessTokenExpiryDate = Carbon::parse($cj_data['data']['accessTokenExpiryDate'])->tz('Europe/Paris');
                    // Update the database with the new token.
                    $expired->update([
                        'generated_token' => $accessToken,
                        'token_exp_date' => $accessTokenExpiryDate
                    ]);
                }

            } else {
                //send email to the user, and delete the old api key
                $user = User::find($expired->user_id);
                Mail::to($user->email)->queue(new CJTokenExpired($user->default_language));

                // Log the error and handle it as needed.
                Log::error('Failed to get access token for user: ' . $expired->email);
                $expired->delete();
            }
        }
    }

    public function getAccessToken($email, $api_key, $ip)
    {

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Forwarded-For' => $ip, // Include the client's IP address
            ])
                ->post('https://developers.cjdropshipping.com/api2.0/v1/authentication/getAccessToken', [
                    'email' => $email,
                    'password' => $api_key,
                ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                return false;
            }
        } catch (\Exception $e) {
            // Log the error and handle the exception as needed.
            Log::error('Error while fetching access token: ' . $e->getMessage());
            return false;
        }
    }
}
