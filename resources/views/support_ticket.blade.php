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



  .message-container {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.message {
  display: inline-block;
  padding: 10px;
  border-radius: 10px;
}

.current-user {
  background-color: #e2f0fd;
  align-self: flex-end;
}

.message-content {
  display: flex;
  flex-direction: column;
}

.message-content p {
  margin: 0;
}

.message-time {
  margin-top: 5px;
}

.form-control {
  border-radius: 10px;
}

.input-group {
  margin-top: 10px;
}

.admin-message {
  align-self: flex-start;
}

</style>

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">{{ trans('general.help') }}</h1>


@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </ul>
    </div>
@endif


<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>
    </div>
    <div class="card-body">
        <div class="settings-profile">

        <div class="message-container">
        @foreach ($messages as $message)
            <div class="message{{ (auth()->user()->id == $message->admin_id) ? ' admin-message' : '' }}{{ $message->user_id != 0 ? ' current-user' : '' }}">
            <div class="message-content">
                <p>{{ $message->message }}</p>
                <span class="message-time small text-muted">
                    {{ $message->created_at->diffForHumans() }}
                </span>
            </div>
            </div>
        @endforeach
        </div>
        <br>

        <form id="messageForm" method="POST" action="{{ route('reply_ticket', $ticket_id) }}">
        @csrf
        <div class="form-group">
            <textarea name="message" placeholder="{{ trans('general.your_message') }}" class="form-control" rows="3" required></textarea>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-primary">{{ trans('general.send') }}</button>
        </div>
        </form>


        </div>
    </div>
</div>

@endsection