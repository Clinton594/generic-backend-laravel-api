@extends('email.layouts.master')
@section('content')
<tr style="display: flex; font-ga">
  <td style="margin-top: 56px;">
    <div style="font-size:18px; ">
      <table width="100%" border="0" cellspacing="0" cellpadding="0"     font-weight="200">
        <tbody>
          <tr>
            {{-- line-height:30px --}}
            <td
              style="font-family: {{config('mail.font')}}; width: 100%; font-size:18px; padding:20px 0; font-weight: 200">
              <strong> 
                Welcome to <?=$company->name?>!<br>
              </strong>

              Dear
              <?=$to_name?>, We are more than happy to recieve you in our team. Please read the following carefully.
              Also make sure your email address is confirmed.
            </td>
          </tr>
          <tr>
            <td
              style="font-family: {{config('mail.font')}}; width: 100%; font-size:18px; line-height:25px; padding:20px 0 10px 0;font-weight: 200; ">
              <span style="font-size:18px;color:red">Security Tips:</span>

              <ul style="padding:0 1.1rem; font-weight:200">
                <li style="margin-bottom:7px; font-family: {{config('mail.font')}};">
                   DO NOT give your password to anyone!
                </li>
                <li style="margin-bottom:7px; font-family: {{config('mail.font')}};">
                   DO NOT call any phone number for someone claiming to be <?=$company->name?> Support!
                  </li>
                <li style="margin-bottom:7px; font-family: {{config('mail.font')}};">
                  DO NOT send any money to anyone claiming to be a member of <?=$company->name?>!
                </li>
                <li style="margin-bottom:7px; font-family: {{config('mail.font')}};">
                   Enable Google Two Factor Authentication!
                </li>

                <li style="margin-bottom:7px; font-family: {{config('mail.font')}};">
                   Make sure you are visiting
                    <a style="color:{{config('app.color.primary')}}; href="<?=config('app.frontend')?>" target="_blank">
                <?=config('app.frontend')?>
                </li>
              </ul>

            </td>
            <td>
            </td>
          </tr>
          <tr>
            <td
              style="font-family: {{config('mail.font')}}; font-weight:300; font-size:18px; width: 100%;padding:20px 0 0 0;">
              If this
              activity is not your own operation, please contact our official customer representative by the following
              link: <a style="color:{{config('app.color.primary')}}; text-decoration: none" href="<?=config('app.frontend')?>/contact" target="_blank">
                <?=config('app.frontend')?>/contact</td>
          </tr>

        </tbody>
      </table>
    </div>

    @if (!empty($otp))
    <hr>
    <div style="font-family: {{config('mail.font')}};font-weight: 200; font-size:18px; color: #000;">
      <br>
      <br>
      <span style="font-family: {{config('mail.font')}};font-size:18px; font-weight:200">
        Use this verification code below to verify your email on {{ config('company.name') }} website
      </span>
      <b
        style="font-family: {{config('mail.font')}};padding: 10px;margin: 10px 0;display: block;background-color: #e6e647;{{config('app.color.primary')}} ;color:{{config('app.color.primary')}};border-radius: 3px;text-align: center; font-size:2rem">
        {{ $otp }}
      </b>
    </div>
    <hr>
    @endif
  </td>
</tr>
@endsection