<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\User;

class DiscordBot
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct($type,$data = null)
    {
        if($type == 'registration'){
            $webhook = 'https://discord.com/api/webhooks/1099298749248974948/cDn64SiddUVdM_vMBTIaNpfR6qiewempjR2EjgIx3PROBHuv_kD73DXs6TTGEOVKaHlU';
            $response = Http::post($webhook, [
                'content' => 'New Registration - Name: '.$data['name'].' - Email: '.$data['email'],
            ]);
        }

        if($type == 'shop_create'){
            $webhook = 'https://discord.com/api/webhooks/1099303848734105700/60-_dHxrXK-ICUT4SoiMTW-2eSC_0bePCeF388HamzthAbzd9PnOC1Fkf7oYNaJ5l7FS';
            $response = Http::post($webhook, [
                'content' => 'New Shop - Name: '.$data['name'].' - Email: '.$data['email'].' - '.' Url: '.$data['shop_url'],
            ]);
        }

        if ($type == 'error') {
            $webhook = 'https://discord.com/api/webhooks/1099893231715614730/m8t004WsdmBfnHenwXxe_vqljI6vLT-qX2xNjOm6JS1R0ZfFqScMcCHxWcZyzbTb2cLF';
            $response = Http::post($webhook, [
                'content' => $data.' - '.Carbon::now()
            ]);
        }

        if($type == 'custom-domain'){
            $webhook = 'https://discord.com/api/webhooks/1162146727470575726/gPqJL0TGulu95-lSXTMd69OuP3kOKjAmlwLv_gWDISK-BVqqQKAYQ0VsnMFx_GPvoMYq';
            $response = Http::post($webhook, [
                'content' => 'Store ID: '.$data['shop_id'].' - Domain: '.$data['custom_domain'],
            ]);
        }

        if($type == 'custom-domain-delete'){
            $webhook = 'https://discord.com/api/webhooks/1162146727470575726/gPqJL0TGulu95-lSXTMd69OuP3kOKjAmlwLv_gWDISK-BVqqQKAYQ0VsnMFx_GPvoMYq';
            $response = Http::post($webhook, [
                'content' => 'DELETED - Store ID: '.$data['shop_id'].' - Domain: '.$data['custom_domain'],
            ]);
        }

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
