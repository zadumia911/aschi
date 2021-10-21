<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Deliveryman;
use App\Merchant;
use App\Parcel;
use App\Parcelnote;
use App\Parceltype;
use App\Exports\RiderParcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use DB;
use Mail;
class DeliverymanController extends Controller
{
    public function loginform(){
        return view('frontEnd.layouts.pages.deliveryman.login');
    }
    public function login(Request $request){
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',
        ]);
       $checkAuth = Deliveryman::where('email',$request->email)
       ->first();
        if($checkAuth){
          if($checkAuth->status == 0){
             Toastr::warning('warning', 'Opps! your account has been suspends');
             return redirect()->back();
         }else{
          if(password_verify($request->password,$checkAuth->password)){
              $deliverymanId = $checkAuth->id;
               Session::put('deliverymanId',$deliverymanId);
               Toastr::success('success', 'Thanks , You are login successfully');
              return redirect('deliveryman/dashboard');
            
          }else{
              Toastr::error('Opps!', 'Sorry! your password wrong');
              return redirect()->back();
          }

           }
        }else{
          Toastr::error('Opps!', 'Opps! you have no account');
          return redirect()->back();
        } 
    }
    public function dashboard(){
      $totalparcel=Parcel::where(['deliverymanId'=>Session::get('deliverymanId')])->count();
          $totaldelivery=Parcel::where(['deliverymanId'=>Session::get('deliverymanId'),'status'=>4])->count();
          $totalhold=Parcel::where(['deliverymanId'=>Session::get('deliverymanId'),'status'=>5])->count();
          $totalcancel=Parcel::where(['deliverymanId'=>Session::get('deliverymanId'),'status'=>9])->count();
          $returnpendin=Parcel::where(['deliverymanId'=>Session::get('deliverymanId'),'status'=>6])->count();
          $returnmerchant=Parcel::where(['deliverymanId'=>Session::get('deliverymanId'),'status'=>8])->count();
          return view('frontEnd.layouts.pages.deliveryman.dashboard',compact('totalparcel','totaldelivery','totalhold','totalcancel','returnpendin','returnmerchant'));
    }
    public function parcels(Request $request){
       $filter = $request->filter_id;
       if($request->trackId!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.deliverymanId',Session::get('deliverymanId'))
        ->where('parcels.trackingCode',$request->trackId)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->phoneNumber!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.deliverymanId',Session::get('deliverymanId'))
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.deliverymanId',Session::get('deliverymanId'))
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->phoneNumber!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.deliverymanId',Session::get('deliverymanId'))
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }else{
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.deliverymanId',Session::get('deliverymanId'))
        ->select('parcels.*','merchants.companyName','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress')
        ->orderBy('id','DESC')
        ->get();
       }
      return view('frontEnd.layouts.pages.deliveryman.parcels',compact('allparcel'));
  }
   public function invoice($id){
    $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where('parcels.deliverymanId',Session::get('deliverymanId'))
    ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
    ->where('parcels.id',$id)
    ->select('parcels.*','nearestzones.zonename','merchants.companyName','merchants.phoneNumber','merchants.emailAddress')
    ->first();
        if($show_data!=NULL){
        	return view('frontEnd.layouts.pages.deliveryman.invoice',compact('show_data'));
        }else{
          Toastr::error('Opps!', 'Your process wrong');
          return redirect()->back();
        }
    }
  public function statusupdate(Request $request){
      $this->validate($request,[
        'status'=>'required',
      ]); 
            $parcel = Parcel::find($request->hidden_id);
            $parcel->status = $request->status;
            $parcel->save();
            
            if($request->note){
                $note = new Parcelnote();
                $note->parcelId = $request->hidden_id;
                $note->note = $request->note;
                $note->save();
            }
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
            if($request->note!=NULL){
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
        }elseif($request->status==8){
            $parcel = Parcel::find($request->hidden_id);
            $returncharge = $parcel->deliveryCharge/2;
            $parcel->merchantAmount=$parcel->merchantAmount-$returncharge;
            $parcel->merchantDue=$parcel->merchantAmount-$returncharge;
            $parcel->deliveryCharge= $parcel->deliveryCharge+$returncharge;
            $parcel->save();
        }
      Toastr::success('message', 'Parcel information update successfully!');
      return redirect()->back();
    }
    public function pickup(){
      $show_data = DB::table('pickups')
      ->where('pickups.deliveryman',Session::get('deliverymanId'))
      ->orderBy('pickups.id','DESC')
      ->select('pickups.*')
      ->get();
      $deliverymen = Deliveryman::where('status',1)->get();
      return view('frontEnd.layouts.pages.deliveryman.pickup',compact('show_data','deliverymen'));
    }
    public function pickupdeliverman(Request $request){
        $this->validate($request,[
          'deliveryman'=>'required',
        ]);
        $pickup = Pickup::find($request->hidden_id);
        $pickup->deliveryman = $request->deliveryman;
        $pickup->save();

        Toastr::success('message', 'A deliveryman asign successfully!');
        return redirect()->back();
        $deliverymanInfo = Deliveryman::find($parcel->deliverymanId);
        $agentInfo =Agent::find($parcel->merchantId);
        $data = array(
         'contact_mail' => $agentInfo->email,
         'ridername' => $deliverymanInfo->name,
         'riderphone' => $deliverymanInfo->phone,
         'codprice' => $pickup->cod,
        );
        $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){
         $textmsg->from('info@sparkdelivery.com.bd');
         $textmsg->to($data['contact_mail']);
         $textmsg->subject('Pickup Assign Notification');
        });
          
    }
    public function pickupstatus(Request $request){
      $this->validate($request,[
        'status'=>'required',
      ]);
      $pickup = Pickup::find($request->hidden_id);
      $pickup->status = $request->status;
      $pickup->save();
    
      Toastr::success('message', 'Pickup status update successfully!');
      return redirect()->back();
    }
    public function passreset(){
      return view('frontEnd.layouts.pages.deliveryman.passreset');
    }
    public function passfromreset(Request $request){
      $this->validate($request,[
            'email' => 'required',
        ]);
        $validDeliveryman =Deliveryman::Where('email',$request->email)
       ->first();
        if($validDeliveryman){
             $verifyToken=rand(111111,999999);
             $validDeliveryman->passwordReset  = $verifyToken;
             $validDeliveryman->save();
             Session::put('resetDeliverymanId',$validDeliveryman->id);
             
             $data = array(
             'contact_mail' => $validDeliveryman->email,
             'verifyToken' => $verifyToken,
            );
            $send = Mail::send('frontEnd.layouts.pages.deliveryman.forgetemail', $data, function($textmsg) use ($data){
             $textmsg->from('support@sparkdelivery.com.bd');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Forget password token');
            });
          return redirect('deliveryman/resetpassword/verify');
        }else{
              Toastr::error('Sorry! You have no account', 'warning!');
             return redirect()->back();
        }
    }
    public function saveResetPassword(Request $request){
      // return "okey";
       $validDeliveryman = Deliveryman::find(Session::get('resetDeliverymanId'));
        if($validDeliveryman->passwordReset==$request->verifyPin){
           $validDeliveryman->password   = bcrypt(request('newPassword'));
           $validDeliveryman->passwordReset  = NULL;
             $validDeliveryman->save();
             
             Session::forget('resetDeliverymanId');
             Session::put('deliverymanId',$validDeliveryman->id);
             Toastr::success('Wow! Your password reset successfully', 'success!');
             return redirect('deliveryman/dashboard');
        }else{
          return $request->verifyPin;
            Toastr::error('Sorry! Your process something wrong', 'warning!');
             return redirect()->back();
        }
       
    }
    public function resetpasswordverify(){
        if(Session::get('resetDeliverymanId')){
        return view('frontEnd.layouts.pages.deliveryman.passwordresetverify');
        }else{
            Toastr::error('Sorry! Your process something wrong', 'warning!');
            return redirect('forget/password');
        }
    }
    public function logout(){
        Session::flush();
        Toastr::success('Success!', 'Thanks! you are logout successfully');
        return redirect('deliveryman/logout');
    }
     public function export( Request $request ) {
        return Excel::download( new RiderParcelExport(), 'parcel.xlsx') ;
    
    }
    
}
