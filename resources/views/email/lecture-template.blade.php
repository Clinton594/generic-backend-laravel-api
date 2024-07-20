@extends('email.layouts.master')
@section('content')
    <tr style="display: flex;">
        <td style="margin-top: 26px;">
            <div style="font-weight: 300; font-size:16px;">
               <div>
                  <img max-height="350px" width="100%" src={{$course_image}} alt="course banner" srcset="" />
               </div>
                <br />
            </div>
        </td>
    </tr>


    <tr style="display: flex; padding:0 1.2rem">

        <td style="margin-top:10px;">
            <div style="font-weight: 500; font-family: {{config('mail.font')}}; font-size:22px;">
              Dear {{ $to_name }},
            </div>
           <div style="font-weight: 300; font-size:18px;">
              <p style="font-weight: 200; font-family: {{config('mail.font')}}; font-size:18px;">
           
                This email is to let you know that you've just scheduled a live session on {{  $course_name }} Below are the details:

               </p>
            </div>
  
         <tr style="display: flex; padding:0 1.2rem">
           <td style="margin-top:10px;">
                <div style="font-weight: 300; font-size:18px; font-family: {{config('mail.font')}};">
                 Below are the details of your session
               </div>
          </td>
        </tr>

          <tr style="display: flex; padding:0 1.2rem">
           <td>
             <div style="font-size:18px;">
              <ul style="padding:0 1.1rem; font-weight:200">
                <li style="margin-bottom:7px; font-family: {{config('mail.font')}};"><samp style="font-weight: 400">Topic: </samp> {{$lecture_topic}}</li>
                <li style="margin-bottom:7px; font-family: {{config('mail.font')}};"> <samp style="font-weight: 400">Lecture date:</samp> {{$lecture_date}}</li>
                <li style="margin-bottom:7px; font-family: {{config('mail.font')}};"><samp style="font-weight: 400"> Lecture time: </samp>  {{$lecture_time}}</li>
                <li style="margin-bottom:7px; font-family: {{config('mail.font')}};"> <samp style="font-weight: 400">
                 Lecture link: </samp>  <a href="{{$lecture_link}}" style="color:{{config('app.color.primary')}};  text-decoration: none">{{$lecture_link}}  </a>
                </li>
                </ul>
             </div>
           </td>
         </tr>

        <tr style="display: flex; padding:0 1.2rem">
           <td>
            <p style="font-weight:200; font-family: {{config('mail.font')}}; font-size:18px;">
              You can login to your dashboard to view your scheduled session, past sessions and courses.
             </p>
           </td>
         </tr>
        </td>
    </tr>

    </tr>
@endsection
