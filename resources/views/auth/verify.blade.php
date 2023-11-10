@extends('layouts.app')

@section('content')
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ trans('general.verify_your_email_address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ trans('general.fresh_verify_link_sent') }}
                        </div>
                    @endif

                    {{ trans('general.before_proceding_verify_email') }}
                    {{ trans('general.verification_not_received') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ trans('general.get_another_verification') }}</button>.
                    </form>
                </div>
            </div>
        </div>
@endsection
