<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Agent;
use App\Parcel;
use App\Pickup;
use App\Deliveryman;
use App\Merchant;
use App\Parcelnote;
use App\Parceltype;
use App\Exports\AgentParcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Session;
use DB;
class AgentController extends Controller
{
    public function loginform(){
        return view('frontEnd.layouts.pages.agent.login');
    }
    public function login(Request $request){
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',
        ]);
       $checkAuth =Agent::where('email',$request->email)
       ->first();
        if($checkAuth){
          if($checkAuth->status == 0){
             Toastr::warning('warning', 'Opps! your account has been suspends');
             return redirect()->back();
         }else{
          if(password_verify($request->password,$checkAuth->password)){
              $agentId = $checkAuth->id;
               Session::put('agentId',$agentId);
               Toastr::success('success', 'Thanks , You are login successfully');
              return redirect('/agent/dashboard');
            
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
    	  $totalparcel=Parcel::where(['agentId'=>Session::get('agentId')])->count();
          $totaldelivery=Parcel::where(['agentId'=>Session::get('agentId'),'status'=>4])->count();
          $totalhold=Parcel::where(['agentId'=>Session::get('agentId'),'status'=>5])->count();
          $totalcancel=Parcel::where(['agentId'=>Session::get('agentId'),'status'=>9])->count();
          $returnpendin=Parcel::where(['agentId'=>Session::get('agentId'),'status'=>6])->count();
          $returnmerchant=Parcel::where(['agentId'=>Session::get('agentId'),'status'=>8])->count();
          return view('frontEnd.layouts.pages.agent.dashboard',compact('totalparcel','totaldelivery','totalhold','totalcancel','returnpendin','returnmerchant'));
    }
    public function parcels(Request $request){
       $filter = $request->filter_id;
       if($request->trackId!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.trackingCode',$request->trackId)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->phoneNumber!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->phoneNumber!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }else{
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->select('parcels.*','merchants.companyName','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress')
        ->orderBy('parcels.id','DESC')
        ->get();
       }
       $aparceltypes = Parceltype::limit(3)->get();
      return view('frontEnd.layouts.pages.agent.parcels',compact('allparcel','aparceltypes'));
  }
   public function invoice($id){
    $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where('parcels.agentId',Session::get('agentId'))
    ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
    ->where('parcels.id',$id)
    ->select('parcels.*','nearestzones.zonename','merchants.companyName','merchants.phoneNumber','merchants.emailAddress')
    ->first();
        if($show_data!=NULL){
        	return view('frontEnd.layouts.pages.agent.invoice',compact('show_data'));
        }else{
          Toastr::error('Opps!', 'Your process wrong');
          return redirect()->back();
        }
    }
  public function delivermanasiagn(Request $request){
      $this->validate($request,[
        'deliverymanId'=>'required',
      ]);
      $parcel = Parcel::find($request->hidden_id);
      $parcel->deliverymanId = $request->deliverymanId;
      $parcel->save();

      Toastr::success('message', 'A deliveryman asign successfully!');
      return redirect()->back();
      $deliverymanInfo = Deliveryman::find($parcel->deliverymanId);
      $merchantinfo =Agent::find($parcel->merchantId);
      $data = array(
       'contact_mail' => $merchantinfo->email,
       'ridername' => $deliverymanInfo->name,
       'riderphone' => $deliverymanInfo->phone,
       'codprice' => $parcel->cod,
       'trackingCode' => $parcel->trackingCode,
      );
      $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){
       $textmsg->from('info@sparkdelivery.com.bd');
       $textmsg->to($data['contact_mail']);
       $textmsg->subject('Percel Assign Notification');
      });
        
  }
  
  public function statusupdate(Request $request){
    //   return $request->all();
      $this->validate($request,[
        'status'=>'required',
      ]); 
      $parcel = Parcel::find($request->hidden_id);
      $parcel->status = $request->status;
      $parcel->save();

       $pnote = Parceltype::find($request->status);
        $note = new Parcelnote();
        $note->parcelId = $request->hidden_id;
        $note->note = "Your parcel ".$pnote->title;
        $note->save();
    
    
        
        $deliverymanInfo =Deliveryman::where(['id'=>$parcel->deliverymanId])->first();
         if($request->status==2 && $deliverymanInfo!=NULL){
            $merchantinfo =Agent::find($parcel->merchantId);
            $data = array(
             'contact_mail' => $merchantinfo->email,
             'ridername' => $deliverymanInfo->name,
             'riderphone' => $deliverymanInfo->phone,
             'codprice' => $parcel->cod,
             'trackingCode' => $parcel->trackingCode,
            );
            $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){
             $textmsg->from('info@sparkdelivery.com.bd');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Percel Assign Notification');
            });
        }
        if($request->status==3){
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            $parcel->codCharge=$codcharge;
            $parcel->save();
        }elseif($request->status==4){
            $merchantinfo = Merchant::find($parcel->merchantId);
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
  public function logout(){
      Session::flush();
      Toastr::success('Success!', 'Thanks! you are logout successfully');
      return redirect('agent/logout');
  }
 public function pickup(){
      $show_data = DB::table('pickups')
      ->where('pickups.agent',Session::get('agentId'))
      ->orderBy('pickups.id','DESC')
      ->select('pickups.*')
      ->get();
      $deliverymen = Deliveryman::where('status',1)->get();
      return view('frontEnd.layouts.pages.agent.pickup',compact('show_data','deliverymen'));
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
    
        if($request->status==2){
            $deliverymanInfo =Deliveryman::where(['id'=>$pickup->deliveryman])->first();
            // $data = array(
            //  'name' => $deliverymanInfo->name,
            //  'companyname' => $merchantInfo->companyName,
            //  'phone' => $deliverymanInfo->phone,
            //  'address' => $merchantInfo->pickLocation,
            // );
            // $send = Mail::send('frontEnd.emails.pickupdeliveryman', $data, function($textmsg) use ($data){
            //  $textmsg->from('info@sparkdelivery.com.bd');
            //  $textmsg->to($data['contact_mail']);
            //  $textmsg->subject('Pickup request update');
            // });
        }
      Toastr::success('message', 'Pickup status update successfully!');
      return redirect()->back();
    }
   public function passreset(){
      return view('frontEnd.layouts.pages.agent.passreset');
    }
    public function passfromreset(Request $request){
      $this->validate($request,[
            'email' => 'required',
        ]);
        $validAgent =Agent::Where('email',$request->email)
       ->first();
        if($validAgent){
             $verifyToken=rand(111111,999999);
             $validAgent->passwordReset  = $verifyToken;
             $validAgent->save();
             Session::put('resetAgentId',$validAgent->id);
             
             $data = array(
             'contact_mail' => $validAgent->email,
             'verifyToken' => $verifyToken,
            );
            $send = Mail::send('frontEnd.layouts.pages.agent.forgetemail', $data, function($textmsg) use ($data){
             $textmsg->from('support@packenmove.com');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Forget password token');
            });
          return redirect('agent/resetpassword/verify');
        }else{
              Toastr::error('Sorry! You have no account', 'warning!');
             return redirect()->back();
        }
    }
    public function saveResetPassword(Request $request){
       $validAgent =Agent::find(Session::get('resetAgentId'));
        if($validAgent->passwordReset==$request->verifyPin){
           $validAgent->password   = bcrypt(request('newPassword'));
           $validAgent->passwordReset  = NULL;
             $validAgent->save();
             
             Session::forget('resetAgentId');
             Session::put('agentId',$validAgent->id);
             Toastr::success('Wow! Your password reset successfully', 'success!');
             return redirect('agent/dashboard');
        }else{
            Toastr::error('Sorry! Your process something wrong', 'warning!');
             return redirect()->back();
        }
       
    }
    public function resetpasswordverify(){
        if(Session::get('resetAgentId')){
        return view('frontEnd.layouts.pages.agent.passwordresetverify');
        }else{
            Toastr::error('Sorry! Your process something wrong', 'warning!');
            return redirect('forget/password');
        }
    }
    public function export( Request $request ) {
        return Excel::download( new AgentParcelExport(), 'parcel.xlsx') ;
    
    }
}
