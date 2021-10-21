<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Deliveryman;
use App\Merchant;
use App\Parcel;
use App\Pickup;
use App\Parcelnote;
use App\Parceltype;
use Session;
use DB;
use Mail;
class ApiDeliveryman extends Controller
{
  
  public function login(Request $request){
    $this->validate($request,[
      'email' => 'required',
      'password' => 'required',
    ]);
    $checkAuth = Deliveryman::where('email',$request->email)->first();
    
    if($checkAuth){
      if($checkAuth->status == 0){
        return ["success"=>false, "message"=>"Opps! your account has been suspends", "data"=>null];
      }else{
        if(password_verify($request->password,$checkAuth->password)){
          return ["success"=>true, "message"=>"Thanks, You are login successfully", "data"=>$checkAuth];
        }else{
          return ["success"=>false, "message"=>"Sorry! your password wrong", "data"=>null];
        }
      }
    }else{
      return ["success"=>false, "message"=>"Opps! you have no account", "data"=>null];
    } 
  }

  public function passwordReset(Request $request){
    $this->validate($request,[
      'email' => 'required',
    ]);
    $validDeliveryman =Deliveryman::Where('email',$request->email)->first();

    if($validDeliveryman){
      $verifyToken=rand(111111,999999);
      $validDeliveryman->passwordReset  = $verifyToken;
      $validDeliveryman->save();
      
      $data = array(
        'contact_mail' => $validDeliveryman->email,
        'verifyToken' => $verifyToken,
      );

      $send = Mail::send('frontEnd.layouts.pages.deliveryman.forgetemail', $data, function($textmsg) use ($data){
        $textmsg->from('info@gexcourier.com');
        $textmsg->to($data['contact_mail']);
        $textmsg->subject('Forget password token');
      });
      
      return ["success"=>true, "message"=>"Verification code sent into your email", "resetDeliverymanId"=>$validDeliveryman->id];
    }else{
      return ["success"=>false, "message"=>"Sorry! You have no account", "resetDeliverymanId"=>null];
    }
  }
  
  public function verifyAndChangePassword(Request $request){
    $id = $request->header('id');

    $validDeliveryman = Deliveryman::find($id);
    if($validDeliveryman->passwordReset==$request->verifyPin){
      $validDeliveryman->password   = bcrypt(request('newPassword'));
      $validDeliveryman->passwordReset  = NULL;
      $validDeliveryman->save();
      
      return ["success"=>true, "message"=>"Wow! Your password reset successfully"];
    }else{
      return ["success"=>false, "message"=>"Sorry! Your process something wrong"];
    }
  }
  
  public function dashboard(Request $request){
    $id = $request->header('id');

    $totalparcel=Parcel::where(['deliverymanId'=>$id])->count();
    $totaldelivery=Parcel::where(['deliverymanId'=>$id,'status'=>4])->count();
    $totalhold=Parcel::where(['deliverymanId'=>$id,'status'=>5])->count();
    $totalcancel=Parcel::where(['deliverymanId'=>$id,'status'=>9])->count();
    $returnpendin=Parcel::where(['deliverymanId'=>$id,'status'=>6])->count();
    $returnmerchant=Parcel::where(['deliverymanId'=>$id,'status'=>8])->count();
    
    // return $merchantPaid;

    return ['totalParcel'=>(int)$totalparcel, 'totalDelivery'=>(int)$totaldelivery, 'totalHold'=>(int)$totalhold, 'totalCancel'=>(int)$totalcancel, 'returnPending'=>(int)$returnpendin, 'returnMerchant'=>(int)$returnmerchant,];
  }
  
  public function parcels(Request $request, $startFrom){
    $id = $request->header('id');

    $filter = $request->filter_id;

    if($request->trackId!=NULL){
      $allparcel = DB::table('parcels')
      ->join('merchants', 'merchants.id','=','parcels.merchantId')
      ->where('parcels.deliverymanId',$id)
      ->where('parcels.trackingCode',$request->trackId)
      ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->orderBy('id','DESC')
      ->skip($startFrom)
      ->take(20)
      ->get();
    }elseif($request->phoneNumber!=NULL){
      $allparcel = DB::table('parcels')
      ->join('merchants', 'merchants.id','=','parcels.merchantId')
      ->where('parcels.deliverymanId',$id)
      ->where('parcels.recipientPhone',$request->phoneNumber)
      ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->orderBy('id','DESC')
      ->skip($startFrom)
      ->take(20)
      ->get();
    }elseif($request->startDate!=NULL && $request->endDate!=NULL){
      $allparcel = DB::table('parcels')
      ->join('merchants', 'merchants.id','=','parcels.merchantId')
      ->where('parcels.deliverymanId',$id)
      ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
      ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->orderBy('id','DESC')
      ->skip($startFrom)
      ->take(20)
      ->get();
    }elseif($request->phoneNumber!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
      $allparcel = DB::table('parcels')
      ->join('merchants', 'merchants.id','=','parcels.merchantId')
      ->where('parcels.deliverymanId',$id)
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
      ->where('parcels.deliverymanId',$id)
      ->select('parcels.*','merchants.companyName','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress')
      ->orderBy('id','DESC')
      ->skip($startFrom)
      ->take(20)
      ->get();
    }
    return $allparcel;
  }

  public function parcel(Request $request, $parcelId){
    $id = $request->header('id');

    $allparcel = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where('parcels.deliverymanId',$id)
    ->where('parcels.id',$parcelId)
    ->select('parcels.*','merchants.companyName','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress')
    ->orderBy('id','DESC')
    ->get();
    
    return $allparcel;
  }

  public function parcelStatusUpdate(Request $request){
    $this->validate($request,[
      'status'=>'required',
    ]); 

    $parcel = Parcel::find($request->hidden_id);
    
    if($parcel->status == 4){
        return ["success"=>false, "message"=>"You can't update parcel information"];
    } else {
        $parcel->status = $request->status;
        $parcel->save();
        
        // if($request->note){
        //   $note = new Parcelnote();
        //   $note->parcelId = $request->hidden_id;
        //   $note->note = $request->note;
        //   $note->save();
        // }
        
        $pnote = Parceltype::find($request->status);
        $note = new Parcelnote();
        $note->parcelId = $request->hidden_id;
        $note->note = "Your parcel ".$pnote->title;
        $note->save();
        
        if($request->status==3){
          $parcel = Parcel::find($request->hidden_id);
          $parcel->status = $request->status;
          $parcel->save();
          
          $codcharge=0;
          $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
          $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
          $parcel->codCharge=$codcharge;
          $parcel->save();
    
        }elseif($request->status==4){
          $parcel = Parcel::find($request->hidden_id);
          $parcel->status = $request->status;
          $parcel->save();
          
          if($request->note != null){
            $note = new Parcelnote();
            $note->parcelId = $request->hidden_id;
            $note->note = 'Parcel delivered successfully';
            $note->save();
          }
          
          $merchantinfo =Merchant::find($parcel->merchantId);
          $data = array(
            'contact_mail' => $merchantinfo->emailAddress,
            'trackingCode' => $parcel->trackingCode,
          );
          $send = Mail::send('frontEnd.emails.percelcancel', $data, function($textmsg) use ($data){
            $textmsg->from('info@sparkdelivery.com.bd');
            $textmsg->to($data['contact_mail']);
            $textmsg->subject('Percel Cancelled Notification');
          });
        }
    
        return ["success"=>true, "message"=>"Parcel information update successfully!"];
    }
  }
  
  public function pickups(Request $request, $startFrom){
    $id = $request->header('id');
    $show_data = DB::table('pickups')
    ->join('merchants', 'merchants.id','=','pickups.merchantId')
    ->where('pickups.deliveryman',$id)
    ->orderBy('pickups.id','DESC')
    ->select('pickups.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
    ->skip($startFrom)
    ->take(20)
    ->get();
    return $show_data;
  }

  public function pickup($parcelId, Request $request){
    $id = $request->header('id');
    $show_data = DB::table('pickups')
    ->join('merchants', 'merchants.id','=','pickups.merchantId')
    ->where('pickups.deliveryman',$id)
    ->where('pickups.id',$parcelId)
    ->orderBy('pickups.id','DESC')
    ->select('pickups.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
    ->get();
    return $show_data;
  }
  
  public function pickupStatusUpdate(Request $request){
    $this->validate($request,[
      'status'=>'required',
    ]);
    $pickup = Pickup::find($request->hidden_id);
    $pickup->status = $request->status;
    $pickup->save();
    
    return ["success"=>true, "message"=>"Pickup status update successfully!"];
  }
}