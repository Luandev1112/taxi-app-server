@extends('email.layout')

@section('content')
    <div class="content">
        <div class="content-header content-header--blue">
            Welcome to Base!
        </div>
        <div class="content-body">
            <p>Hi {{ $user->name }},</p>

            <div class="text-center">
                <a href="{{ url("api/v1/verify/email/{$token}?email=".$user->email) }}" target="_blank" class="btn btn-default">
                    Verify your email
                </a>
            </div>

            <div class="hr-line"></div>

            <p>We are committed to offering not just a Convenience but a Choice Driven by Technology.</p>

            <ul>

            </ul>
        </div>
    </div>
@endsection
