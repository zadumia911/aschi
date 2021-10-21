<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Merchant;
use App\Nearestzone;
use App\Deliverycharge;
use App\Codcharge;
use App\Parcel;
use App\Imports\ParcelImport;
use App\Exports\ParcelExport;
use App\Employee;
use App\Price;
use App\Pickup;
use App\Merchantpayment;
use App\Parcelnote;
use App\Deliveryman;
use App\Agent;
use Session;
use DB;
use Mail;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
class MerchantController extends Controller
{
    
    public function registerpage(){
        return view('frontEnd.layouts.pages.register');
    } 

    public function register(Request $request){
  	  	$this->validate($request,[
  		  'companyName'=>'required',
          'phoneNumber'=>'required',
          'emailAddress'=>'required',
          'username'=>'required',
  	    ]);
  	 //   return $request->all();
    	 $marchentCheck=Merchant::orWhere('phoneNumber',$request->phoneNumber)->orWhere('emailAddress',$request->emailAddress)->orWhere('username',$request->username)->first();
    	if($marchentCheck){
    	     Toastr::error('message', 'Opps! your email,phone or username already used');
    	   return redirect()->back();
    	 }else{
      	  	$store_data				   = 	new Merchant();
            $store_data->companyName   =   $request->companyName;
            $store_data->firstName     =   $request->firstName;
    	    $store_data->phoneNumber   =   $request->phoneNumber;
            $store_data->emailAddress  =   $request->emailAddress;
            $store_data->username      =   $request->username;
    	    $store_data->pickLocation  =   $request->pickLocation;
            $store_data->socialLink    =   $request->socialLink;
            $store_data->status        =    1;
            $store_data->agree         =    1;
            $store_data->verifyToken   =    1;
    	    $store_data->save();
    	    
        	$url = "http://premium.mdlsms.com/smsapi";
            $data = [
              "api_key" => "C2001024611342bf45b268.77561331",
              "type" => "Text",
              "contacts" => $request->phoneNumber,
              "senderid" => "8809612441444",
              "msg" => "Dear $request->companyName\r\nSuccessfully boarded your account. Now you can login & enjoy our services.\r\nRegards,\r\nSpark Delivery",
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
    	    Session::put('merchantId',$store_data->id);
            Toastr::success('Thanks for registration, Your account has been active.', 'success!');
            return redirect('/merchant/dashboard');
            
    	 }
    }
    public function loginpage(){
        return view('frontEnd.layouts.pages.login');
    }
    public function login(Request $request){
       $merchantInfo = Merchant::where('phoneNumber',$request->phoneNumber)->first();
       if($merchantInfo!=NULL){
           $verifyToken=rand(1111,9999);
           $merchantInfo->verifyToken   = $verifyToken;
           $merchantInfo->status        = 0;
           $merchantInfo->save();
    
           Session::put('phoneverify',$request->phoneNumber);
           $url = "http://premium.mdlsms.com//smsapi";
            $data = [
              "api_key" => "C2001024611342bf45b268.77561331",
              "type" => "Text",
              "contacts" => $request->phoneNumber,
              "senderid" => "8809612441444",
              "msg" => "Your verify Token is $verifyToken ,Thanks for using Spark Delivery",
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            Toastr::success('!Done', 'We send a OTP in your phone');
             return redirect('merchant/phone-verify');
        }else{
             Toastr::error('!Opps', 'You have no account');
              return redirect()->back();
        }
    }
    public function phoneVerifyForm(){
        $phoneverify = Session::get('phoneverify');
        if($phoneverify==!NULL){
        return view('frontEnd.layouts.pages.merchant.verify');
        }else{
          Toastr::error('!Opps', 'Your process is invalid');
          return redirect('/');
        }
    }
     public function phoneresendcode(Request $request){
       $merchantInfo = Merchant::where('phoneNumber',Session::get('phoneverify'))->first();
       $verifyToken=rand(1111,9999);
       $merchantInfo->verifyToken= $verifyToken;
       $merchantInfo->save();
       $url = "http://premium.mdlsms.com//smsapi";
        $data = [
          "api_key" => "C2001024611342bf45b268.77561331",
          "type" => "Text",
          "contacts" => '0'.$merchantInfo->phoneNumber,
          "senderid" => "8809612441444",
          "msg" => "Your verify Token is $verifyToken ,Thanks for using Spark Delivery",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        Toastr::success('!Done', 'We send a OTP in your phone');
        return redirect('merchant/phone-verify');

    }
    public function phoneVerify(Request $request){
        $this->validate($request,[
            'verifyToken'=>'required',
        ]);
        $verified=Merchant::where('phoneNumber',Session::get('phoneverify'))->first();
        // dd($verified);
        $verifydbtoken = $verified->verifyToken;
        $verifyformtoken= $request->verifyToken;
       if($verifydbtoken==$verifyformtoken){
            $verified->verifyToken = 1;
            $verified->status = 1;
            $verified->save();
            Session::put('merchantId',$verified->id);
            Session::forget('phoneverify');
            Toastr::success('Your account is verified', 'success!');
            return redirect('merchant/dashboard');
       }else{
        Toastr::error('sorry your verify token wrong', 'Opps!');
        return redirect()->back();
       }
    }
    // Merchant Login Function End

    public function dashboard(){
          $placepercel=Parcel::where(['merchantId'=>Session::get('merchantId')])->count();
          $pendingparcel=Parcel::where(['merchantId'=>Session::get('merchantId'),'status'=>1])->count();
          $intransitparcel=Parcel::where(['merchantId'=>Session::get('merchantId'),'status'=>1])->count();
          $deliverd=Parcel::where(['merchantId'=>Session::get('merchantId'),'status'=>4])->count();
          $cancelparcel=Parcel::where(['merchantId'=>Session::get('merchantId'),'status'=>9])->count();
          $collectamount=Parcel::where(['merchantId'=>Session::get('merchantId'),'status'=>10])->count();
          $totalpaid=Parcel::where(['merchantId'=>Session::get('merchantId'),'status'=>11])->count();
          $parcelreturn=Parcel::where(['merchantId'=>Session::get('merchantId'),'status'=>8])->count();
          $totalhold=Parcel::where(['merchantId'=>Session::get('merchantId'),'status'=>5])->count();
          $totalamount=Parcel::where(['merchantId'=>Session::get('merchantId'),'status'=>4])->sum('merchantAmount');
          $merchantUnPaid=Parcel::where(['merchantId'=>Session::get('merchantId'),'status'=>4])->whereNull('merchantpayStatus')->sum('merchantAmount');
          $merchantPaid=Parcel::where(['merchantId'=>Session::get('merchantId'),'merchantpayStatus'=>1])->sum('merchantAmount');
          return view('frontEnd.layouts.pages.merchant.dashboard',compact('placepercel','pendingparcel','deliverd','parcelreturn','cancelparcel','totalhold','totalamount','merchantUnPaid','merchantPaid','collectamount','totalpaid','intransitparcel'));
    }
    // Merchant Dashboard
    public function profile(){
        $profileinfos = Merchant::all();
      return view('frontEnd.layouts.pages.merchant.profile',compact('profileinfos'));
      
    }

    public function profileEdit(){
        $profileinfos = Merchant::all();
        $nearestzones = Nearestzone::where('status',1)->get();
        return view('frontEnd.layouts.pages.merchant.profileedit',compact('nearestzones'));
      
    }
    public function support(){
        return view('frontEnd.layouts.pages.merchant.support');
    }
    // Merchant Profile Edit
        public function profileUpdate(Request $request){
        $update_merchant = Merchant::find(Session::get('merchantId'));
        $update_merchant->phoneNumber = $request->phoneNumber;
        $update_merchant->pickLocation = $request->pickLocation;
        $update_merchant->nearestZone = $request->nearestZone;
        $update_merchant->pickupPreference = $request->pickupPreference;
        $update_merchant->paymentMethod = $request->paymentMethod;
        $update_merchant->withdrawal = $request->withdrawal;
        $update_merchant->nameOfBank = $request->nameOfBank;
        $update_merchant->bankBranch = $request->bankBranch;
        $update_merchant->bankAcHolder = $request->bankAcHolder;
        $update_merchant->bankAcNo = $request->bankAcNo;
        $update_merchant->bkashNumber = $request->bkashNumber;
        $update_merchant->roketNumber = $request->roketNumber;
        $update_merchant->nogodNumber = $request->nogodNumber;
        $update_merchant->save();
        return redirect()->back()->with('success','Your account update successfully');
    }

    // Merchant Profile Update
    public function logout(){
        Session::flush();
        Toastr::success('Success!', 'Thanks! you are logout successfully');
        return redirect('/merchant/login');
    }
    // Merchant Logout

    //Parcel Oparation
    public function parcelcreate(){
        $packages = Deliverycharge::where('status',1)->get();
        Session::forget('codpay');
        Session::forget('pdeliverycharge');
        Session::forget('pcodecharge');
        return view('frontEnd.layouts.pages.merchant.parcelcreate',compact('packages'));
    }
  public function parcelstore(Request $request){
     $this->validate($request,[
        'cod'=>'required',
        'percelType'=>'required',
        'name'=>'required',
        'address'=>'required',
        'phonenumber'=>'required',
      ]);
     if($request->weight > 1 || $request->weight !=NULL){
          $extraweight    = $request->weight-1;
          $deliverycharge = (Session::get('deliverycharge')*1)+($extraweight*Session::get('extradeliverycharge'));
          $weight         = $request->weight;
     }else{
          $deliverycharge = (Session::get('deliverycharge'));
          $weight         = 1;
     }

     if($request->cod > 100){
          $extracod       = $request->cod -100;
          $extracodcharge = $extracod/100;
          $extracodcharge = 0;
          $codcharge      = Session::get('codcharge')+$extracodcharge;
     }else{
      $codcharge          = Session::get('codcharge');
     }

     $store_parcel                   = new Parcel;
     $store_parcel->invoiceNo        = $request->invoiceno;
     $store_parcel->merchantId       = Session::get('merchantId');
     $store_parcel->cod              = $request->cod;
     $store_parcel->percelType       = $request->percelType;
     $store_parcel->recipientName    = $request->name;
     $store_parcel->recipientAddress = $request->address;
     $store_parcel->recipientPhone   = $request->phonenumber;
     $store_parcel->productWeight    = $weight;
     $store_parcel->trackingCode     = 'SP'.mt_rand(111111,999999);
     $store_parcel->note             = $request->note;
     $store_parcel->deliveryCharge   = $deliverycharge;
     $store_parcel->codCharge        = $codcharge;
     $store_parcel->reciveZone       = $request->reciveZone;
     $store_parcel->productPrice     = $request->productPrice;
     $store_parcel->merchantAmount   = ($request->cod)-($deliverycharge+$codcharge);
     $store_parcel->merchantDue      = ($request->cod)-($deliverycharge+$codcharge);
     $store_parcel->orderType        = $request->package;
     $store_parcel->codType          = 1;
     $store_parcel->status           = 1;
     $store_parcel->save();
     
     $note              = new Parcelnote();
     $note->parcelId    = $store_parcel->id;
     $note->note        = 'parcel create successfully';
    //  $note->save();
     
     $data = array(
         'trackingCode' =>  $store_parcel->trackingCode,
         'subject' => 'New Parcel Place',
        );
         // return $data;
         $send = Mail::send('frontEnd.emails.parcelplace', $data, function($textmsg) use ($data){
         $textmsg->to('contact@sparkdelivery.com.bd');
         $textmsg->subject($data['subject']);
        });
     
     Toastr::success('Success!', 'Thanks! your parcel add successfully');
     return redirect()->back();
  } 

 public function pickuprequest(Request $request){
     $this->validate($request,[
        'pickupAddress'=>'required',
      ]);
      
      $date = date('Y-m-d');
      $findpickup = Pickup::where('date',$date)->Where('merchantId',Session::get('merchantId'))->count();
         if($findpickup){
            Toastr::error('Opps!', 'Sorry! your pickup request already pending');
             return redirect()->back();
         }else{
             $store_pickup = new Pickup;
             $store_pickup->merchantId = Session::get('merchantId');
             $store_pickup->pickuptype = $request->pickuptype;
             $store_pickup->area  = $request->area;
             $store_pickup->pickupAddress = $request->pickupAddress;
             $store_pickup->note = $request->note;
             $store_pickup->date = $date;
             $store_pickup->estimedparcel = $request->estimedparcel;
             $store_pickup->save();
             Toastr::success('Success!', 'Thanks! your pickup request send  successfully');
             return redirect()->back();
         }
     
  } 
  public function pickup(){
      $show_data = DB::table('pickups')
      ->where('pickups.merchantId',Session::get('merchantId'))
      ->orderBy('pickups.id','DESC')
      ->select('pickups.*')
      ->get();
      $deliverymen = Deliveryman::where('status',1)->get();
      return view('frontEnd.layouts.pages.merchant.pickup',compact('show_data','deliverymen'));
    }
  public function parcels(Request $request){
       $filter = $request->filter_id;
       if($request->trackId!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->where('parcels.trackingCode',$request->trackId)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->phoneNumber!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->phoneNumber!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }else{
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.merchantId',Session::get('merchantId'))
         ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }
        
      return view('frontEnd.layouts.pages.merchant.parcels',compact('allparcel'));
  }
  public function parceldetails($id){
    $parceldetails= DB::table('parcels')
        ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         ->where(['parcels.merchantId'=>Session::get('merchantId'),'parcels.id'=>$id])
        ->select('parcels.*','nearestzones.zonename')
        ->first();
      $trackInfos = Parcelnote::where('parcelId',$id)->orderBy('id','ASC')->get();
      return view('frontEnd.layouts.pages.merchant.parceldetails',compact('parceldetails','trackInfos'));
  }
   public function invoice($id){
    $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where(['parcels.merchantId'=>Session::get('merchantId'),'parcels.id'=>$id])
    ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
    ->where('parcels.id',$id)
    ->select('parcels.*','nearestzones.zonename','merchants.companyName','merchants.phoneNumber','merchants.emailAddress')
    ->first();
        if($show_data!=NULL){
        	return view('frontEnd.layouts.pages.merchant.invoice',compact('show_data'));
        }else{
          Toastr::error('Opps!', 'Your process wrong');
          return redirect()->back();
        }
    }
  public function parceledit($id){
      $parceledit=Parcel::where(['merchantId'=>Session::get('merchantId'),'id'=>$id])->first();
      if($parceledit !=NULL){
      $ordertype = Deliverycharge::find($parceledit->orderType);
      $codcharge = Codcharge::find($parceledit->codType);
      $areas = Nearestzone::where('status',1)->get();
      Session::put('codpay',$parceledit->cod);
      Session::put('pcodecharge',$parceledit->codCharge);
      Session::put('pdeliverycharge',$parceledit->deliveryCharge);
      return view('frontEnd.layouts.pages.merchant.parceledit',compact('ordertype','codcharge','parceledit','areas'));
      }else{
         Toastr::error('Opps!', 'Your process wrong');
         return redirect()->back();
      }
  }
  
public function parcelupdate(Request $request){
     $this->validate($request,[
        'cod'=>'required',
        'name'=>'required',
        'address'=>'required',
        'phonenumber'=>'required',
      ]);
         // fixed delivery charge
        if($request->weight > 1 || $request->weight !=NULL){
          $extraweight = $request->weight-1;
          $deliverycharge = (Session::get('deliverycharge')*1)+($extraweight*Session::get('extradeliverycharge'));
          $weight = $request->weight;
         }else{
          $deliverycharge = (Session::get('deliverycharge'));
          $weight = 1;
         }

         // fixed cod charge
         if($request->cod > 100){
          $extracod       =$request->cod -100;
          $extracodcharge = $extracod/100;
          $extracodcharge = 0;
          $codcharge      = Session::get('codcharge')+$extracodcharge;
         }else{
          $codcharge      = Session::get('codcharge');
         }

         $update_parcel                     = Parcel::find($request->hidden_id);
         $update_parcel->invoiceNo          = $request->invoiceno;
         $update_parcel->merchantId         = Session::get('merchantId');
         $update_parcel->cod                = $request->cod;
         $update_parcel->percelType         = $request->percelType;
         $update_parcel->recipientName      = $request->name;
         $update_parcel->recipientAddress   = $request->address;
         $update_parcel->recipientPhone     = $request->phonenumber;
         $update_parcel->productWeight      = $weight;
         $update_parcel->note               = $request->note;
         $update_parcel->reciveZone         = $request->reciveZone;
         $update_parcel->deliveryCharge     = $deliverycharge;
         $update_parcel->codCharge          = $codcharge;
         $update_parcel->merchantAmount     = ($request->cod)-($deliverycharge+$codcharge);
         $update_parcel->merchantDue        = ($request->cod)-($deliverycharge+$codcharge);
         $update_parcel->orderType          = $request->package;
         $update_parcel->codType            = 1;
         $update_parcel->save();
         Toastr::success('Success!', 'Thanks! your parcel update successfully');
         return redirect()->back();
  }
  public function singleservice(Request $request){
      $data = array(
              'contact_mail'  => 'info@sparkdelivery.com.bd',
              'address'       => $request->address,
              'area'          => $request->area,
              'note'          => $request->note,
              'estimate'      => $request->estimate,
            );
            $send = Mail::send('frontEnd.emails.singleservice', $data, function($textmsg) use ($data){
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('A Single Service Request');
            });
        Toastr::success('Success!', 'Thanks! your  request send successfully');
        return redirect()->back();
  }
  public function payments(){
      $merchantInvoice = Merchantpayment::where('merchantId',Session::get('merchantId'))->get();

      return view('frontEnd.layouts.pages.merchant.payments',compact('merchantInvoice'));
  }
  public function inovicedetails($id){
        $invoiceInfo = Merchantpayment::find($id);
        $inovicedetails = Parcel::where('paymentInvoice',$id)->get();
        return view('frontEnd.layouts.pages.merchant.inovicedetails',compact('inovicedetails','invoiceInfo'));
    }
   public function passreset(){
      return view('frontEnd.layouts.pages.passreset');
    }
    public function passfromreset(Request $request){
      $this->validate($request,[
            'phoneNumber' => 'required',
        ]);
        $validMerchant = Merchant::Where('phoneNumber',$request->phoneNumber)
       ->first();
        if($validMerchant){
            
             $verifyToken=rand(111111,999999);
    	     $validMerchant->passwordReset 	=	$verifyToken;
             $validMerchant->save();
             Session::put('resetCustomerId',$validMerchant->id);
             
            //  $data = array(
            //  'contact_mail' => $validMerchant->phoneNumber,
            //  'verifyToken' => $verifyToken,
            // );
            // $send = Mail::send('frontEnd.emails.passwordreset', $data, function($textmsg) use ($data){
            //  $textmsg->from('info@sparkdelivery.com.bd');
            //  $textmsg->to($data['contact_mail']);
            //  $textmsg->subject('Forget password token');
            // });
            
  $url = "http://premium.mdlsms.com//smsapi";
  $data = [
    "api_key" => "C2000829604b00d0ccad46.26595828",
    "type" => "text",
    "contacts" => "0$validMerchant->phoneNumber",
    "senderid" => "8809612441280",
    "msg" => "Dear $validMerchant->firstName, \r\n Your password reset token is $verifyToken. Enjoy our services. If any query call us +880 1701-012200\r\nRegards\r\nPackeN Move ",
  ];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $response = curl_exec($ch);
  curl_close($ch);
 
         return redirect('/merchant/resetpassword/verify');
        }else{
              Toastr::error('Sorry! You have no account', 'warning!');
             return redirect()->back();
        }
        
        
        
        
        
        
        
    }
    public function resetpasswordverify(){
        if(Session::get('resetCustomerId')){
        return view('frontEnd.layouts.pages.passwordresetverify');
        }else{
            Toastr::error('Sorry! Your process something wrong', 'warning!');
            return redirect('forget/password');
        }
    }
    public function saveResetPassword(Request $request){
       $validMerchant = Merchant::find(Session::get('resetCustomerId'));
        if($validMerchant->passwordReset==$request->verifyPin){
    	     $validMerchant->password 	=	bcrypt(request('newPassword'));
    	     $validMerchant->passwordReset 	=	NULL;
             $validMerchant->save();
             
             Session::forget('resetCustomerId');
             Session::put('merchantId',$validMerchant->id);
             Toastr::success('Wow! Your password reset successfully', 'success!');
             return redirect('/merchant/dashboard');
        }else{
            Toastr::error('Sorry! Your process something wrong', 'warning!');
             return redirect()->back();
        }
       
    }
    public function parceltrack(Request $request){
         $trackparcel = DB::table('parcels')
        ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         ->where('parcels.trackingCode','LIKE','%'.$request->trackid."%")
         ->select('parcels.*','nearestzones.zonename')
         ->orderBy('id','DESC')
         ->first();
         
        if($trackparcel){
            $trackInfos = Parcelnote::where('parcelId',$trackparcel->id)->orderBy('id','ASC')->get();
            return view('frontEnd.layouts.pages.merchant.trackparcel',compact('trackparcel','trackInfos'));
        }else{
            return redirect()->back();
        }
    }
    public function import(Request $request)
    {
      Excel::import(new ParcelImport,request()->file('excel'));
      Toastr::success('Wow! Bulk uploaded', 'success!');
      return redirect()->back();
    }
    public function export( Request $request ) {
        return Excel::download( new ParcelExport(), 'parcel.xlsx') ;
    
    }

}
