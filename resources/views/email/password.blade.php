@extends('email.layouts.master')
@section('content')
    <tr style="display: flex;">
        <td style="margin-top: 56px;">
            <div style="font-weight: 300; font-size:16px; ">
                Hi {{ $to_name }},

                @if (!empty($resend))
                    <br> <span>You requested a password reset link.</span>
                @endif

                <span>
                    <br /> <br />
                    Use this link to authorize your password reset process.
                </span>
            </div>
        </td>
    </tr>
    <tr style="display: flex;">
        <td style="margin-top:20px">
            <table>
                <tbody>
                    <tr>
                        <td width="250px" style="margin-top: 20px;">
                            <table bgcolor="#00296B"
                                style="border-radius:8px;text-decoration:none;width:100%;border:solid 2px #00296B"
                                cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td class="m_7030289048136942514button-copy"
                                            style="font-family:NetflixSans-Bold,Helvetica,Roboto,Segoe UI,sans-serif;font-weight:500;font-size:18px;letter-spacing:-0.2px;color:#f5f5f1;"
                                            align="center" height="42">
                                            <a href="{{ $link }}"
                                                style="text-align:center;display:inline-block;text-decoration:none;color:#f5f5f1"
                                                target="_blank">Authorize Reset</a>
                                        </td>
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
