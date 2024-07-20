<html lang="en">
  <head>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      .certificate {
        width: 60%;
        margin: 100px auto;
        box-shadow: 5px 5px 5px 0px rgba(126, 124, 124, 0.75);
      }
      .certificate_header {
        height: 28vh;
        width: 100%;
        background-color: #00296b;
        padding: 48px 38px 0px;
        border-radius: 4px;
      }
      .certificate_bordered {
        border: 1px solid #fff;
        height: 100%;
        border-bottom: none;
        border-radius: 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 38px;
      }
      .certificate_bordered h2 {
        color: #fff;
        text-align: center;

        font-size: 38px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        margin-top: 20px;
      }
      .certificate_bordered img {
        width: 120px;
      }
      .certificate_content {
        background-color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-radius: 4px;
        padding-top: 90px;
        padding-bottom: 42px;
      }
      .certificate_content > * {
        width: 68%;
      }
      .certificate_content h6 {
        color: #2a2b2c;

        font-size: 24px;
        text-align: center;
        font-style: italic;
        font-weight: 400;
        line-height: normal;
      }
      .certificate_content h3 {
        color: #2a2b2c;
        text-align: center;

        font-size: 32px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
        margin-top: 32px;
        padding-bottom: 10px;
        border-bottom: 1px solid #999faa;
      }
      .certificate_content p {
        color: #00296b;
        text-align: center;

        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        letter-spacing: 0.16px;
        margin-top: 22px;
        width: 50%;
      }

      .certificate_signatures {
        display: flex;
        justify-content: space-between;
        padding-top: 42px;
        text-align: center;
      }
      .certificate_signatures > * {
        width: 200px;
      }
      .certificate_signatures > div {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 40%;
      }

      .certificate_signatures > div hr {
        border: 1px solid #e5e5e5;
        width: 70%;
      }

      .certificate_signatures img {
        width: 77px;
        margin-bottom: 10px;
      }
      .certificate_signatures h2 {
        color: #2a2b2c;
        text-align: center;
        padding-top: 10px;
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
      }
      .certificate_signatures p {
        color: #00296b;
        text-align: center;

        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        letter-spacing: 0.1px;
        margin-top: 7px;
      }
    </style>
  </head>
  <body>
    <div class="certificate">
      <div class="certificate_header">
        <div class="certificate_bordered">
          <div>
            {{-- <img src="/logo-light.png" alt="" /> --}}
          </div>
          <h2>Certification of completion</h2>
        </div>
      </div>
      <div class="certificate_content">
        <h6>This is to certify that</h6>
        {{-- <h3>{{ $to_name }}</h3> --}}
       
        <p>
          Has completed an intensive training on during
          their training in codelandcs academy online training
        </p>
        <div class="certificate_signatures">
          <div>
            <span>
              <img
                src="https://res.cloudinary.com/nonsoalbert/image/upload/v1707733736/signature_gvgq7o.svg"
                alt=""
              />
            </span>
            <hr />
            <h2>Ugwu Somto</h2>
            <p>Program Instructor</p>
          </div>
          <div>
            <span>
              <img
                src="https://res.cloudinary.com/nonsoalbert/image/upload/v1707733736/signature_gvgq7o.svg"
                alt=""
              />
            </span>
            <hr />
            <h2>Onuigbo Clinton</h2>
            <p>program coordinator</p>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
