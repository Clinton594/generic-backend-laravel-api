@extends('email.layouts.master')
@section('content')
<tr>
    <td>
        <div style="margin-top: 56px;font-weight: 200; font-size:18px; color: #000;">
            <img height="150px" width="150px"
                src={{asset('images/password-account-security-reset-safety-svgrepo-com.png') }} alt="" srcset="" />
            <h3 style="color:{{config('app.color.primary')}}">Password Changed !</h3>
            <div style=" font-size:18px; font-family:{{config('mail.font')}}">
                {{-- {{$body}}. --}}
                    {{$to_name}}, just to notify you that your password has been changed.
            </div>
            <br>

            <br>

            <div style=" font-size:18px; font-weight: 300; font-family:{{config('mail.font')}}">

                If this
                activity is not your own operation, please contact our official customer representative by the
                following
                link: <a style="color:{{config('app.color.primary')}}; text-decoration: none" href="<?=config('app.frontend')?>/contact" target="_blank">
                    <?=config('app.frontend')?>/contact

            </div>
        </div>
    </td>
</tr>

@endsection