@extends('email.layouts.master')
@section('content')
<tr>
    <td style="font-weight: 200;">
        <div style="margin-top: 56px;font-weight: 200; font-size:18px;">
            <img height="150px" width="150px" src={{asset('images/kyc-success.png') }} alt="" srcset="" />
            <h3 style="color: green;">Congratulations !</h3>
            <div style=" font-size:18px; font-family:{{config('mail.font')}}">
                {{$to_name}}, your email has been successfuly verified.
            </div>
            <br>
            <div style=" font-size:18px; font-family:{{config('mail.font')}}">
                You are almost ready to embark on your journey to greatness.
            </div>
            <br>
            <div style=" font-size:18px; font-family:{{config('mail.font')}}">
               {{$body}}
            </div>
        </div>
    </td>
</tr>

@endsection