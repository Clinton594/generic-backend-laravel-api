@extends('email.layouts.master')
@section('content')
    <tr style="display: flex;">
        <td style="margin-top: 56px;">
            <div style="font-weight: 300; font-size:16px; ">
                <strong>{{ $subject }}</strong>
                <br />
                <br />
                <p style="font-family: {{config('mail.font')}}">

                    {{ html_entity_decode($body) }}
                </p>
                <br>
            </div>
        </td>
    </tr>
@endsection
