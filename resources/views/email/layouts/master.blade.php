<body style="background-color:#f4f5ff">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            font-size: 18px
        }
    </style>

    <div
        style="width: 100%; min-width: 360px; max-width:670px; margin: 0 auto; min-height: 300px; background-color: white;">
        <table style="margin:24px">
            <tbody style="width: 100%;">
                <tr style="display: flex; ">
                    <td style="margin-top: 30px">
                        <div>
                            <a href="{{ config('app.frontend') }}">
                                <img src="{{ $company->logo }}" width="240px" />
                            </a>

                        </div>
                    </td>
                </tr>

                @yield('content')

                <tr style="display: flex;">
                    <td style="margin-top: 26px;">
                        <div style="font-weight: 200; font-size:18px; line-height:20px; ">
                            Thanks,
                            <br> {{ $company->name }} team.
                        </div>
                    </td>
                </tr>
               
                <tr style="display: flex;">
                    <td style="margin-top: 30px;">
                        <div
                            style="font-family: {{config('mail.font')}}; font-weight: 200; font-size:15px; line-height:20px;">
                            This email was sent to <span style="font-size:15px; color: {{config('app.color.primary')}};">{{ $to_name }}</span> because you
                            have chosen to use {{ $company->name }} services. If you didn't initiate this email,
                            please reach out to support to <a style="font-size:15px; color: {{config('app.color.primary')}}; text-decoration:none"
                                href="mailto:{{ $company->helpdesk }}">unsubscribe</a> you from the mailing
                            list.
                            <br>
                            <br>
                            @php
                            $year = date('Y');
                            @endphp
                            © {{ $year }} {{ $company->name }}, {{ $company->address }}
                        </div>
                    </td>
                </tr>
                <tr style="display: flex; justify-content:space-between; margin-top: 20px;padding: 10px">
                    <td style="margin: auto 0;width:80%">
                        <div>
                            <a style="font-family: {{config('mail.font')}};text-align: left" href{{
                                config('app.frontend') }}>

                                <img style="" src="{{ $company->logo}}" width="120px" />
                            </a>
                        </div>
                    </td>

                    <td style="width:20%;">
                        <div
                            style="font-family: {{config('mail.font')}};font-weight: 300; font-size:16px; line-height:20px; display:flex; justify-content: flex-end;gap: 30px; margin-top:8px; ">
                            @forelse ($company->social_media as $item)
                            <div style="width: 40px;">
                                <a href="{{ $item->desc }}">
                                    <img src="{{ $item->src }}" width="24px" />
                                </a>
                            </div>
                            @empty
                            <p></p>
                            @endforelse
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>