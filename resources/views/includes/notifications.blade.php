@if(auth()->user()->notificationsCount() > 0)
    @foreach(auth()->user()->getNotifications() as $notification)
        @switch($notification->notif_id)
            @case('1')
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">{{ \Carbon\Carbon::parse($notification->created_at)->format('F j, Y') }} - €{{ $notification->amount }}</div>
                        <span class="font-weight-bold">{{ trans('general.new_order_made') }}</span>
                    </div>
                </a>
                @break
        @endswitch
    @endforeach
@else
    <div class="dropdown-item d-flex align-items-center">
        {{ trans('general.no_notifications') }}
    </div>
@endif
