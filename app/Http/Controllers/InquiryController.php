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
            
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());  
            }
            
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
