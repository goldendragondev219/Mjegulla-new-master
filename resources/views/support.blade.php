@extends('layouts.app')
<title>{{trans('general.help')}} | {{ config('app.name') }} </title>
@section('content')

<style>

.centered-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 30vh; 
  }

</style>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">{{ trans('general.help') }}</h1>
                    <button class="btn btn-primary mb-3" id="create_ticket" data-toggle="modal" data-target="#ticketModal">{{ trans('general.create_ticket') }}</button>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{ trans('general.help') }}</h6>
                        </div>
                        <div class="card-body">
                        @if($tickets->isEmpty())
                            <div class="centered-container">
                                <label><i class="fas fa-question-circle" style="font-size: 100px;"></i></label>
                                <label><h5>{{ trans('general.no_tickets') }}</h5></label>
                            </div>
                        @else
                            @foreach ($tickets as $ticket)
                            <div class="col-12 py-2">
                                <div class="card" onclick="window.location='/ticket/{{ $ticket->ticket_id }}'" style="cursor: pointer;">
                                    <div class="card-body d-flex justify-content-between align-items-center settings-profile" style="background-color: @if(auth()->user()->dark_mode == '1') #2a2e39; @else #e9ecef; @endif border-radius: 5px;">
                                        <h5 class="card-title mb-0">{{ $ticket->title }} @if($ticket->user_seen == 0)<span class="badge badge-primary">Unread</span>@endif</h5>
                                        <label class="card-text">{{ $ticket->updated_at->diffForHumans() }}</label>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->


        </div>
        <!-- End of Content Wrapper -->



<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ticketModalLabel">{{ trans('general.create_ticket') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ticketForm" method="POST" action="{{ route('create_ticket') }}">
          @csrf 
          <div class="form-group">
            <label for="title">{{ trans('general.title') }}:</label>
            <input type="text" name="title" class="form-control" id="title" required>
          </div>
          <div class="form-group">
            <label for="message">{{ trans('general.message') }}:</label>
            <textarea class="form-control" name="message" id="message" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" form="ticketForm">{{ trans('general.send') }}</button> 
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('general.close') }}</button>
      </div>
    </div>
  </div>
</div>


@endsection