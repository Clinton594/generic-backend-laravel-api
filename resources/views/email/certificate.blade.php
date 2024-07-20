<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.google.com/" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;500&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Open Sans";
        }

        table {
            width: 60%;
            margin: 100px auto;
            box-shadow: 10px 10px 18px 14px rgba(126, 124, 124, 0.75);
            background-color: #00296b;
            border-radius: 10px 10px 0px 0px;
            padding: 0;
        }

        thead {
            background-color: #00296b;
        }

        thead tr {
            width: 100%;
        }

        thead td {
            display: block;
        }

        thead td .certificate_bordered {
            width: 90%;
            margin: auto;
            margin-top: 50px;
            min-height: 200px;
            border: 1px solid white;
            border-bottom: none;
        }

        thead td .certificate_bordered .image_div {
            height: fit-content;
            width: fit-content;
            margin: 50px auto 0;
        }

        thead td .certificate_bordered h2 {
            color: #fff;
            text-align: center;
            font-style: normal;
            font-weight: 500;
            margin-top: 20px;
        }

        tbody {
            background-color: #fff;
        }

        tbody tr {
            width: 100%;
        }

        tbody td {
            display: block;
        }

        tbody td .certificate_content {
            width: 80%;
            margin: 60px auto;
            min-height: 200px;
        }

        tbody td .certificate_content h6 {
            color: #2a2b2c;
            font-size: 24px;
            text-align: center;
            font-style: italic;
            font-weight: 400;
            line-height: normal;
        }

        tbody td .certificate_content h3 {
            color: #2a2b2c;
            text-align: center;
            margin: 32px auto 0;
            width: 75%;
            font-size: 32px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
            padding-bottom: 10px;
            border-bottom: 1px solid #999faa;
        }

        tbody td .certificate_content p {
            color: #00296b;
            text-align: center;
            font-size: 15px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
            letter-spacing: 0.16px;
            margin: 22px auto;
            width: 70%;
        }

        .signnature_box {
            margin-top: 80px;
        }

        tbody td .certificate_content .signature_one {
            width: fit-content;
            display: inline-block;
            width: 30%;
        }

        tbody td .certificate_content .signature_two {
            width: fit-content;
            margin-left: auto;
            float: right;
            vertical-align: top;
            width: 30%;
        }

        .signature_one .image,
        .signature_two .image {
            height: fit-content;
            width: fit-content;
            margin: auto;
        }

        .signature_one .light,
        .signature_two .light {
            font-size: 13px;
            margin: 0 auto;
        }

        .signature_one .bold,
        .signature_two .bold {
            text-align: center;
            font-size: 16px;
            margin: 10px auto;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td>
                    <div class="certificate_bordered">
                        <div class="image_div"
                            style="
                height: 40px;
                width: 150px;
                display: block;
                background: url(https://res.cloudinary.com/nonsoalbert/image/upload/v1708678294/Frame_1765_1_jcrr0q.png);
                background-size: contain;
                background-repeat: no-repeat;
              ">
                        </div>
                        <h2>Certification of completion</h2>
                    </div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="certificate_content">
                        <h6>This is to certify that</h6>
                        <h3>{{ $to_name }}</h3>
                        <p>
                            Has completed an intensive training on {{ $course_name }}
                            during their training in codelandcs academy online training
                        </p>

                        <div class="signnature_box">
                            <div class="signature_one">
                                <div class="image"
                                    style="
                      height: 40px;
                      width: 50%;
                      display: block;
                      background: url(https://res.cloudinary.com/nonsoalbert/image/upload/v1707733736/signature_gvgq7o.svg);
                      background-size: contain;
                      background-repeat: no-repeat;
                    ">
                                </div>
                                <hr />
                                <p class="bold">Ugwu somto</p>
                                <p class="light">Program Instructor</p>
                            </div>
                            <div class="signature_two">
                                <div class="image"
                                    style="
                      height: 40px;
                      width: 50%;
                      display: block;
                      background: url(https://res.cloudinary.com/nonsoalbert/image/upload/v1707733736/signature_gvgq7o.svg);
                      background-size: contain;
                      background-repeat: no-repeat;
                    ">
                                </div>
                                <hr />
                                <p class="bold">Onuigbo Clinton</p>
                                <p class="light">Program coordinator</p>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
