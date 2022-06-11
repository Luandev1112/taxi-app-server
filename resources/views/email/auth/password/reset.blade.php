@extends('email.layout')

@section('content')
    <div class="content">
        <div class="content-header content-header--blue">
            Reset your password
        </div>
        <div class="content-body">
            <p>Hi {{ $user->name }},</p>
            <p>We've received a request to reset your password. You can reset your password by clicking on the button below.</p>

            <div class="text-center">
				
                <a href="{{ 'https://angularpwa-d828e.firebaseapp.com/reset-password/' . $token . '/' .$user->email }}"
                   target="_blank" class="btn btn-default">
                    Reset Password
                </a>
            </div>

            <p>You can ignore the email if you didn't make this request. Contact us for further help.</p>
        </div>
    </div>
@endsection
