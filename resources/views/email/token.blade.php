@extends('email.layouts.master')
@section('content')
<tr>
    <td>
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-spacing:0px">
            <tbody>
                <tr style="border-collapse:collapse">
                    <td class="m_3745784173069059634es-m-p0r" valign="top" align="center"
                        style="padding:0;Margin:0;width:520px">
                        <table cellpadding="0" cellspacing="0" width="100%" role="presentation"
                            style="border-collapse:collapse;border-spacing:0px">
                            <tbody>
                                <tr style="border-collapse:collapse">
                                    <td align="center" height="16" style="padding:0;Margin:0"></td>
                                </tr>
                                <tr style="border-collapse:collapse">
                                    <td align="left" style="padding:0;Margin:0">
                                        <h1
                                            style="Margin:0;line-height:34px;">
                                            <strong> OTP </strong>
                                        </h1>
                                    </td>
                                </tr>
                                <tr style="border-collapse:collapse">
                                    <td align="center" height="16" style="padding:0;Margin:0"></td>
                                </tr>
                                <tr style="border-collapse:collapse">
                                    <td align="left" style="padding:0;Margin:0; font-weight:200">
                                        <p
                                            style="font-size:18px, font-weight:200">
                                            Here is your {{strtolower($subject) }} code:</p>
                                    </td>
                                </tr>
                                <tr style="border-collapse:collapse">
                                    <td align="center" height="16" style="padding:0;Margin:0"></td>
                                </tr>
                                <tr style="border-collapse:collapse">
                                    <td align="left" bgcolor="#f4f5ff" style="padding:15px; Margin:0 ;font-weight:200">
                                        @if (is_numeric($otp))
                                            <p
                                            style="Margin:0;font-family:-apple-system,blinkmacsystemfont,'segoe ui',roboto,helvetica,arial,sans-serif,'apple color emoji','segoe ui emoji','segoe ui symbol';line-height:33px;color:#673de6;font-size:22px">
                                            <strong style="letter-spacing: 5px">{{$otp}}</strong>
                                            </p>
                                        @else

                                            <p >
                                                <a  href="{{config('app.frontend')}}/reset/{{$otp}}">
                                                <button style="font-family:{{config('mail.font')}}; font-size:20px; font-weight:bold; border:none; padding:5px 15px; cursor:pointer; color:{{config('app.color.secondary')}} ; background-color:{{config('app.color.primary')}}; border-radius:3px">
                                                    Click Here
                                                </button>
                                            </a>
                                           <span style="font-family:{{config('mail.font')}}"> to proceed.</span>
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                                <tr style="border-collapse:collapse; font-weight:200">
                                    <td align="center" height="16" style="padding:0;Margin:0"></td>
                                </tr>
                                <tr style="border-collapse:collapse">
                                    <td align="left" style="padding:0;Margin:0; font-weight:200">
                                        <p style="Margin:0; font-size:18px">
                                            Please make sure you never share this code with anyone.</p>
                                    </td>
                                </tr>
                                <tr style="border-collapse:collapse">
                                    <td align="center" height="16" style="padding:0;Margin:0"></td>
                                </tr>
                                <tr style="border-collapse:collapse">
                                    <td align="left" style="padding:0;Margin:0; font-weight:200">
                                        <p
                                            style="Margin:0 ;font-size:18px">
                                            <b>Note:</b> The code will expire in {{ config('data.tokenExpiration') }}
                                            minutes.
                                        </p>
                                    </td>
                                </tr>
                                <tr style="border-collapse:collapse">
                                    <td align="center" height="24" style="padding:0;Margin:0"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
@endsection