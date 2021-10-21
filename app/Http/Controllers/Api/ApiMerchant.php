<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Merchant;
use App\Nearestzone;
use App\Deliverycharge;
use App\Codcharge;
use App\Parcel;
use App\Imports\ParcelImport;
use App\Employee;
use App\Price;
use App\Pickup;
use App\Merchantpayment;
use App\Merchantcharge;
use App\Parcelnote;
use App\Parceltype;
use App\Deliveryman;
use App\Agent;
use Session;
use DB;
use Mail;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ApiMerchant extends Controller{

  function register(Request $request){
    // $this->validate($request,[
    //   'companyName'=>'required',
    //   'phoneNumber'=>'required|unique:merchants',
    //   'emailAddress'=>'required|unique:merchants',
    //   'username'=>'required|unique:merchants',
    //   'password'=>'required|same:confirmed',
    //   'confirmed'=>'required',
    // ]);
    
    $marchentEmail=Merchant::where('emailAddress',$request->emailAddress)->first();
    $marchentUsername=Merchant::where('username',$request->username)->first();
    $marchentPhoneNumber=Merchant::where('phoneNumber',$request->phoneNumber)->first();
    if($marchentEmail){
      return ["success"=>false, "message"=>"Opps! your email address already exist", "data"=>null];
    } else if ($marchentUsername){
      return ["success"=>false, "message"=>"Opps! your username already exist", "data"=>null];
    } else if ($marchentPhoneNumber){
      return ["success"=>false, "message"=>"Opps! your phone number already exist", "data"=>null];
    } else{
      $store_data				         =   new Merchant();
      $store_data->companyName   =   $request->companyName;
      $store_data->firstName     =   $request->firstName;
      $store_data->phoneNumber   =	 $request->phoneNumber;
      $store_data->emailAddress  =   $request->emailAddress;
      $store_data->username      =   $request->username;
      $store_data->pickLocation  =	 $request->pickLocation;
      $store_data->socialLink    =   $request->socialLink;
      $store_data->status        =   1;
      $store_data->agree         =   1;
      $store_data->verifyToken   =   1;
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

      return ["success"=>true, "message"=>"Thanks for registration, Your account has been activate", "data"=>null];
    }
  }
  
  public function login(Request $request){
      $merchantInfo = Merchant::where('phoneNumber',$request->phoneNumber)->first();
      if($merchantInfo!=NULL){
          $verifyToken=rand(1111,9999);
          $merchantInfo->verifyToken   = $verifyToken;
          $merchantInfo->status        = 0;
          $merchantInfo->save();
          
          $url = "http://premium.mdlsms.com/smsapi";
          $data = [
              "api_key" => "C2001024611342bf45b268.77561331",
              "type" => "Text",
              "contacts" => $request->phoneNumber,
              "senderid" => "8809612441444",
              "msg" => "Your verify token is $verifyToken, Thanks for using Spark Delivery",
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            
            return ["success"=>true, "message"=>"We send a OTP in your phone"];
          
      }else{
          return ["success"=>false, "message"=>"Opps, You have no account"];
      }
  }
  
  public function phoneVerify(Request $request){
      $verified=Merchant::where('phoneNumber',$request->phoneNumber)->first();
      
      $verifydbtoken = $verified->verifyToken;
      $verifyformtoken= $request->verifyToken;
      if($verifydbtoken==$verifyformtoken){
          $verified->verifyToken = 1;
          $verified->status = 1;
          $verified->save();
          
          return ["success"=>true, "message"=>"Your account is verified", "data"=>$verified];
      }else{
          return ["success"=>false, "message"=>"Sorry your verify token wrong", "data"=>null];
      }
  }

  function dashboard(Request $request){
    $id = $request->header('id');

    $placepercel=Parcel::where(['merchantId'=>$id])->count();
    $pendingparcel=Parcel::where(['merchantId'=>$id,'status'=>1])->count();
    $intransitparcel=Parcel::where(['merchantId'=>$id,'status'=>1])->count();
    $deliverd=Parcel::where(['merchantId'=>$id,'status'=>4])->count();
    $cancelparcel=Parcel::where(['merchantId'=>$id,'status'=>9])->count();
    $collectamount=Parcel::where(['merchantId'=>$id,'status'=>10])->count();
    $totalpaid=Parcel::where(['merchantId'=>$id,'status'=>11])->count();
    $parcelreturn=Parcel::where(['merchantId'=>$id,'status'=>8])->count();
    $totalhold=Parcel::where(['merchantId'=>$id,'status'=>5])->count();
    $totalamount=Parcel::where(['merchantId'=>$id,'status'=>4])->sum('merchantAmount');
    $merchantUnPaid=Parcel::where(['merchantId'=>$id,'status'=>4])->whereNull('merchantpayStatus')->sum('merchantAmount');
    $merchantPaid=Parcel::where(['merchantId'=>$id,'merchantpayStatus'=>1])->sum('merchantAmount');

    return ['placeParcel'=>(int)$placepercel, 'pendingParcel'=>(int)$pendingparcel, 'deliveredParcel'=>(int)$deliverd, 'cancelParcel'=>(int)$cancelparcel, 'collectAmount'=>(int)$collectamount, 'totalPaid'=>(int)$totalpaid, 'parcelReturn'=>(int)$parcelreturn, 'totalHold'=>(int)$totalhold, 'totalAmount'=>(int)$totalamount, 'merchantUnpaid'=>(int)$merchantUnPaid, 'merchantPaid'=>(int)$merchantPaid, 'inTransitParcel'=>(int)$intransitparcel,];
  }
    
  function profile(Request $request){
    $id = $request->header('id');

    $profileinfos = Merchant::where('id',$id)->first();
    return $profileinfos;
  }

  function nearestZone(){
    $nearestzones = Nearestzone::where('status',1)->get();
    return $nearestzones;
  }
  
  function parcelType(){
    $parcelTypes = Parceltype::all();
    return $parcelTypes;
  }

  function profileUpdate(Request $request){
    $id = $request->header('id');
    $update_merchant = Merchant::find($id);
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
    $update_merchant->roketNumber = $request->rocketNumber;
    $update_merchant->nogodNumber = $request->nogodNumber;
    $update_merchant->save();
    return ["success"=>true, "message"=>"Your account update successfully", "data"=>Merchant::find($id)];
  }
 
  function chooseservice(){
    return Deliverycharge::where('status',1)->get();
  }

  function getServiceBySlug(Request $request){
    $slug = $request->header('slug');

    return Deliverycharge::where('slug',$slug)->first();
  }

  function getCodCharge(){
    return Codcharge::where('status',1)->orderBy('id','DESC')->first();
  }
  
  function getPackageCharges($id){
    return Merchantcharge::where('merchantId',$id)->orderBy('id','DESC')->get();
  }

  function createParcel(Request $request){
    $merchantId = $request->header('id');

    // $this->validate($request,[
    //   'cod'=>'required',
    //   'percelType'=>'required',
    //   'name'=>'required',
    //   'address'=>'required',
    //   'phoneNumber'=>'required',
    // ]);
    // fixed delivery charge
    if($request->weight > 1 || $request->weight !=NULL){
      $extraweight = $request->weight-1;
      $deliverycharge = ($request->deliveryCharge*1)+($extraweight*$request->extraDeliveryCharge);
      $weight = $request->weight;
    }else{
      $deliverycharge = ($request->deliveryCharge);
      $weight = 1;
    }
    // fixed cod charge
    if($request->cod > 100){
      $extracod=$request->cod -100;
      $extracodcharge = $extracod/100;
      $extracodcharge = 0;
      $codcharge = $request->codCharge+$extracodcharge;
    }else{
      $codcharge= $request->codCharge;
    }
    $store_parcel = new Parcel;
    $store_parcel->invoiceNo = $request->invoiceNo;
    $store_parcel->merchantId = $merchantId;
    $store_parcel->cod = $request->cod;
    $store_parcel->percelType = $request->percelType;
    $store_parcel->recipientName = $request->name;
    $store_parcel->recipientAddress = $request->address;
    $store_parcel->recipientPhone = $request->phoneNumber;
    $store_parcel->productWeight = $weight;
    $store_parcel->trackingCode = 'SP'.mt_rand(111111,999999);
    $store_parcel->note = $request->note;
    $store_parcel->deliveryCharge = $deliverycharge;
    $store_parcel->codCharge = $codcharge;
    $store_parcel->reciveZone = $request->reciveZone;
    $store_parcel->productPrice = $request->productPrice;
    $store_parcel->merchantAmount = ($request->cod)-($deliverycharge+$codcharge);
    $store_parcel->merchantDue = ($request->cod)-($deliverycharge+$codcharge);
    $store_parcel->orderType = $request->package;
    $store_parcel->codType = 1;
    $store_parcel->status = 1;
    $store_parcel->save();
    
    $note = new Parcelnote();
    $note->parcelId = $store_parcel->id;
    $note->note = 'Parcel Create Successfully';
    // $note->save();

    $data = array(
      'trackingCode' =>  $store_parcel->trackingCode,
      'subject' => 'New Parcel Place',
    );

    $send = Mail::send('frontEnd.emails.parcelplace', $data, function($textmsg) use ($data){
      $textmsg->to('contact@sparkdelivery.com.bd');
      $textmsg->subject($data['subject']);
    });

    return ["success"=>true, "message"=>"Thanks! your parcel add successfully"];
  } 
  
  function pickupRequest(Request $request){
    $merchantId = $request->header('id');
    
    $this->validate($request,[
      'pickupAddress'=>'required',
    ]);
  
    $date = date('Y-m-d');
    
    $findpickup = Pickup::where('date',$date)->Where('merchantId', $merchantId)->count();
    if($findpickup){
      return ["success"=>false, "message"=>"Sorry! your pickup request already pending"];
    }else{
      $store_pickup = new Pickup;
      $store_pickup->merchantId = $merchantId;
      $store_pickup->pickuptype = $request->pickuptype;
      $store_pickup->area  = $request->area;
      $store_pickup->pickupAddress = $request->pickupAddress;
      $store_pickup->note = $request->note;
      $store_pickup->date = $date;
      $store_pickup->estimedparcel = $request->estimedparcel;
      $store_pickup->save();

      return ["success"=>true, "message"=>"Thanks! your pickup request send successfully"];
    }
  } 

  function pickup(Request $request, $startFrom){
    $merchantId = $request->header('id');

    $show_data = DB::table('pickups')
    ->where('pickups.merchantId',$merchantId)
    ->orderBy('pickups.id','DESC')
    ->select('pickups.*')
    ->skip($startFrom)
    ->take(20)
    ->get();

    return $show_data;
  }

  function deliveryman($id){
    $deliverymen = Deliveryman::where('id',$id)->first();
    return $deliverymen;
  }

  function parcels(Request $request, $startFrom){
    $merchantId = $request->header('id');

    $filter = $request->filter_id;
    if($request->trackId!=NULL){
      $allparcel = DB::table('parcels')
      ->join('merchants', 'merchants.id','=','parcels.merchantId')
      ->where('parcels.merchantId',$merchantId)
      ->where('parcels.trackingCode',$request->trackId)
      ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->orderBy('id','DESC')
      ->skip($startFrom)
      ->take(20)
      ->get();
    }elseif($request->phoneNumber!=NULL){
      $allparcel = DB::table('parcels')
      ->join('merchants', 'merchants.id','=','parcels.merchantId')
      ->where('parcels.merchantId',$merchantId)
      ->where('parcels.recipientPhone',$request->phoneNumber)
      ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->orderBy('id','DESC')
      ->skip($startFrom)
      ->take(20)
      ->get();
    }elseif($request->startDate!=NULL && $request->endDate!=NULL){
      $allparcel = DB::table('parcels')
      ->join('merchants', 'merchants.id','=','parcels.merchantId')
      ->where('parcels.merchantId',$merchantId)
      ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
      ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->orderBy('id','DESC')
      ->skip($startFrom)
      ->take(20)
      ->get();
    }elseif($request->phoneNumber!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
      $allparcel = DB::table('parcels')
      ->join('merchants', 'merchants.id','=','parcels.merchantId')
      ->where('parcels.merchantId',$merchantId)
      ->where('parcels.recipientPhone',$request->phoneNumber)
      ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
      ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->orderBy('id','DESC')
      ->skip($startFrom)
      ->take(20)
      ->get();
    }else{
      $allparcel = DB::table('parcels')
      ->join('merchants', 'merchants.id','=','parcels.merchantId')
      ->where('parcels.merchantId',$merchantId)
      ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->orderBy('id','DESC')
    //   ->skip($startFrom)
    //   ->take(20)
      ->get();
    }
    return $allparcel;
  }

  function parceldetails($id, Request $request){
    $merchantId = $request->header('id');

    $parceldetails= DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    // ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
    ->where(['parcels.merchantId'=>$merchantId,'parcels.id'=>$id])
    ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
    // ->select('parcels.*','nearestzones.zonename')
    ->first();
    
    return response()->json($parceldetails);
  }

  function getServiceById($id){
    return Deliverycharge::where('id',$id)->first();
  }
  
  function parcelupdate(Request $request){
    $merchantId = $request->header('id');

    $this->validate($request,[
      'cod'=>'required',
      'name'=>'required',
      'address'=>'required',
      'phoneNumber'=>'required',
    ]);
    // fixed delivery charge
    if($request->weight > 1 || $request->weight !=NULL){
      $extraweight = $request->weight-1;
      $deliverycharge = ($request->deliveryCharge*1)+($extraweight*$request->extraDeliveryCharge);
      $weight = $request->weight;
    }else{
      $deliverycharge = ($request->deliveryCharge);
      $weight = 1;
    }

    // fixed cod charge
    if($request->cod > 100){
         $extracod=$request->cod -100;
         $extracodcharge = $extracod/100;
      $extracodcharge = 0;
      $codcharge = $request->codCharge+$extracodcharge;
    }else{
      $codcharge= $request->codCharge;
    }
    $update_parcel = Parcel::find($request->hidden_id);
    $update_parcel->invoiceNo = $request->invoiceNo;
    $update_parcel->merchantId = $merchantId;
    $update_parcel->cod = $request->cod;
    $update_parcel->percelType = $request->percelType;
    $update_parcel->recipientName = $request->name;
    $update_parcel->recipientAddress = $request->address;
    $update_parcel->recipientPhone = $request->phoneNumber;
    $update_parcel->productWeight = $weight;
    $update_parcel->note = $request->note;
    $update_parcel->reciveZone = $request->reciveZone;
    $update_parcel->deliveryCharge = $deliverycharge;
    $update_parcel->codCharge = $codcharge;
    $update_parcel->orderType = $request->package;
    $update_parcel->merchantAmount = ($request->cod)-($deliverycharge+$codcharge);
    $update_parcel->merchantDue = ($request->cod)-($deliverycharge+$codcharge);
    $update_parcel->codType = 1;
    $update_parcel->save();

    return ["success"=>true, "message"=>"Thanks! your parcel update successfully"];
  }

  function payments(Request $request, $startFrom){
    $merchantId = $request->header('id');
    $merchantInvoice = Merchantpayment::where('merchantId',$merchantId)
    ->skip($startFrom)
    ->take(20)
    ->get();
    return $merchantInvoice;
  }

  function parcelPayments($id){
    return Parcel::where('paymentInvoice',$id)->get();
  }
  
  function parceltrack($trackid){
    $trackparcel = DB::table('parcels')
    ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
    ->where('parcels.trackingCode','LIKE','%'.$trackid."%")
    ->select('parcels.*','nearestzones.zonename')
    ->orderBy('id','DESC')
    ->first();
    
    if($trackparcel){
      $trackInfos = Parcelnote::where('parcelId',$trackparcel->id)->orderBy('id','ASC')->get();
      
      return ["success"=>true, "message"=>"Parcel Found", "data"=>$trackInfos];
    }else{
      return ["success"=>false, "message"=>"Parcel not found", "data"=>null];
    }
  }

//   public function inovicedetails($id){
//         $invoiceInfo = Merchantpayment::find($id);
//         $inovicedetails = Parcel::where('paymentInvoice',$id)->get();
//         return view('frontEnd.layouts.pages.merchant.inovicedetails',compact('inovicedetails','invoiceInfo'));
//     }

  public function merchantSupport(Request $request){
    $merchantId = $request->header('id');
    
    $this->validate($request, [
      'subject'=>'required',
      'description'=>'required',
    ]);
    $findMerchant = Merchant::find($merchantId);
    
    $data = array(
      'contact_email' => $findMerchant->emailAddress,
      'description' => $request->description,
    );
    
    $send = Mail::send('frontEnd.emails.support', $data, function($textmsg) use ($data){
      $textmsg->from($data['contact_email']);
      $textmsg->to('contact@sparkdelivery.com');
      $textmsg->subject($data['description']);
    });
    
    if($send){
      return ["success"=>true, "message"=>"Message sent successfully!"];
    }else{
      return ["success"=>false, "message"=>"Message sent failed"];
    }
  }
}