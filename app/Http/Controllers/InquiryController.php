<?php

namespace App\Http\Controllers;

use App\Mail\GMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
//use App\Models\Mail\InquiryEmail;

class InquiryController extends Controller
{
   public function ajaxInquiry(Request $request) {

       if ($request->ajax()) {

        //    $data = array(
        //         'body'=>$request['message'],
        //         'fullname'=>$request['fullname'],
        //         'email' => $request['email'],
        //         'product' => $request['product'],
        //         'productsize' => $request['productsize'],
        //         'mobile'=> $request['mobile']
        //     );

           $validationRules=[
                'fullname' => 'required',
                'email' => 'required|email',
            ];

            $validator = \Validator::make($request->all(),$validationRules);

            \Validator::extend('captcha', function($attribute, $value, $parameters, $validator) use($request){
                return captcha_check($value);
            });

            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }


            $forbiddenWods = ['OR', 'AND', 'XOR','sleep','sysdate','%','concat','union','select','insert','update','delete','drop','truncate','exec','declare','--','#'];
            $parts = preg_split('/[ ,;|]/', $request['message']);

            foreach ($parts as $word) {
                if (in_array($word, $forbiddenWods)) {
                    return response()->json(['message' => 'Your message contains forbidden words. Please remove them and try again.']);
                }
            }

            $response = $request["g-recaptcha-response"];
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = array(
                'secret' => config('recapcha.secret_v2'),
                'response' => $response
            );

            $options = array(
                'http' => array (
                    'method' => 'POST',
                    'content' => http_build_query($data),
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                    "User-Agent:MyAgent/1.0\r\n",
                )
            );
            $context  = stream_context_create($options);
            $verify = file_get_contents($url, false, $context);
            $captcha_success=json_decode($verify);

            if ($captcha_success->success == false)
                return response()->json(array('message'=>$captcha_success->error-codes));

            //Mail::to(config('mail.from.address'))->queue(new InquiryEmail($data));

            $data = array(
                'to' => 'info@berdvaye.com',
                'body'=>$request['message'],
                'from_name' => $request['fullname'],
                'fullname'=>$request['fullname'],
                'email' => $request['email'],
                'product' => $request['product'],
                'productsize' => $request['productsize'],
                'mobile'=> $request['mobile'],
                'subject'=>'BerdVaye Request Pricing',
                'template' => 'emails.pricerequest',
            );
            //return response()->json($data);
            // Mail::to('info@swissmadecorp.com')->queue(new InquiryEmail($data));

            $gmail = new Gmailer($data);
            $gmail->send();

           return response()->json(array('message' => 'Price request form was successfully submitted. Thank you, We will get back to you soon!'));
        }

    }
}
