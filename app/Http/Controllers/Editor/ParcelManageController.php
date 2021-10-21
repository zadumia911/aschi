<?php

namespace App\Http\Controllers\Editor;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Parcel;
use App\Codcharge;
use App\Deliveryman;
use App\Deliverycharge;
use App\Merchant;
use DB;
use Auth;
use App\Post;
use App\Parcelnote;
use App\Parceltype;
use App\Merchantcharge;
use Mail;
use Exception;
class ParcelManageController extends Controller
{
    public function parcel(Request $request){
       $parceltype = Parceltype::where('slug',$request->slug)->first();
        if($request->trackId!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->where('parcels.trackingCode',$request->trackId)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
          ->get();
       }elseif($request->merchantId!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->where('parcels.merchantId',$request->merchantId)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
       }elseif($request->phoneNumber!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->where('parcels.recipientPhone',$request->phoneNumber)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
       }elseif($request->phoneNumber!=NULL  && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->where('parcels.recipientPhone',$request->phoneNumber)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
       }elseif($request->merchantId!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->where('parcels.merchantId',$request->merchantId)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
       }else{
        $show_data = DB::table('parcels')
         ->join('merchants', 'merchants.id','=','parcels.merchantId')
         ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         ->where('parcels.status',$parceltype->id)
         ->orderBy('id','DESC')
         ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
        }
    	return view('backEnd.parcel.parcel',compact('show_data','parceltype'));
    }


    public function invoice($id){
    	    $show_data = DB::table('parcels')
    	    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    	    ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
    	    ->where('parcels.id',$id)
    	    ->select('parcels.*','nearestzones.zonename','merchants.companyName','merchants.phoneNumber','merchants.emailAddress')
            ->first();
    	return view('backEnd.parcel.invoice',compact('show_data'));
    }

       public function agentasign(Request $request){
    	$this->validate($request,[
    		'agentId'=>'required',
    	]);
    	$parcel = Parcel::find($request->hidden_id);
    	$parcel->agentId = $request->agentId;
    	$parcel->save();

        if($request->note){
            $note = new Parcelnote();
            $note->parcelId = $request->hidden_id;
            $note->note = $request->note;
            $note->save();
        }

    	Toastr::success('message', 'A agent asign successfully!');
    	return redirect()->back();
    }
    public function deliverymanasign(Request $request){
      $this->validate($request,[
        'deliverymanId'=>'required',
      ]);
      $parcel = Parcel::find($request->hidden_id);
      $parcel->deliverymanId = $request->deliverymanId;
      $parcel->save();

      if($request->note){
            $note = new Parcelnote();
            $note->parcelId = $request->hidden_id;
            $note->note = $request->note;
            $note->save();
        }

      Toastr::success('message', 'A deliveryman asign successfully!');
      return redirect()->back();
      $deliverymanInfo = Deliveryman::find($parcel->deliverymanId);
      $merchantinfo =Merchant::find($parcel->merchantId);
      $data = array(
       'contact_mail' => $merchantinfo->emailAddress,
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
        if($request->status==2){
            $deliverymanInfo =Deliveryman::where(['id'=>$parcel->deliverymanId])->first();
            $merchantinfo =Merchant::find($parcel->merchantId);
            
            $url = "http://premium.mdlsms.com/smsapi";
              $data = [
                "api_key" => "C2001024611342bf45b268.77561331",
                "type" => "text",
                "contacts" => "0$parcel->recipientPhone",
                "senderid" => "8809612441444",
                "msg" => "Dear $parcel->recipientName, We have received your parcel from $merchantinfo->companyName. Your Tracking ID is $parcel->trackingCode. Please click the link to track your parcel:" .url('track/parcel/').'/'.$parcel->trackingCode. " Thanks for being with Spark delivery."
              ];
            //   return $data;
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              $response = curl_exec($ch);
              curl_close($ch);
          
        }elseif($request->status==3){
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            $parcel->codCharge=$codcharge;
            $parcel->save();
            
            $validMerchant =Merchant::find($parcel->merchantId);
            $deliveryMan = Deliveryman::find($parcel->deliverymanId);
            $readytaka = $parcel->cod;
            $url = "http://premium.mdlsms.com/smsapi";
              $data = [
                "api_key" => "C2001024611342bf45b268.77561331",
                "type" => "text",
                "contacts" => "0$parcel->recipientPhone",
                "senderid" => "8809612441444",
                "msg" => "Dear $parcel->recipientName \r\n your parcel is being delivered by $deliveryMan->name phone number 0$deliveryMan->phone.  Please get ready with the cash amount of $readytaka. \r\n Thanks for being with Spark delivery."
              ];
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              $response = curl_exec($ch);
              curl_close($ch);
         }elseif($request->status==4){
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            $parcel->codCharge=$codcharge;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
            $url = "http://premium.mdlsms.com/smsapi";
              $data = [
                "api_key" => "C2001024611342bf45b268.77561331",
                "type" => "text",
                "contacts" => "0$validMerchant->phoneNumber",
                "senderid" => "8809612441444",
                "msg" => "Dear $validMerchant->firstName, Your Parcel ID $parcel->trackingCode has been delivered successfully to the customer.\r\n Thanks for being with Spark delivery",
              ];
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              $response = curl_exec($ch);
              curl_close($ch);
        }elseif($request->status==5){
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            $parcel->codCharge=$codcharge;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
            $url = "http://premium.mdlsms.com/smsapi";
          $data = [
            "api_key" => "C2001024611342bf45b268.77561331",
            "type" => "text",
            "contacts" => "0$validMerchant->phoneNumber",
            "senderid" => "8809612441444",
            "msg" => "Dear $validMerchant->firstName, Your Parcel ID $parcel->trackingCode is on hold. Another attempt will be taken the next day. \r\n Thanks for being with Spark delivery.",
          ];
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          $response = curl_exec($ch);
          curl_close($ch);
        }elseif($request->status==6){
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            $parcel->codCharge=$codcharge;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
            $url = "http://premium.mdlsms.com/smsapi";
          $data = [
            "api_key" => "C2001024611342bf45b268.77561331",
            "type" => "text",
            "contacts" => "0$validMerchant->phoneNumber",
            "senderid" => "8809612441444",
            "msg" => "Dear $validMerchant->firstName, Your Parcel ID $parcel->trackingCode will be return within 48 hours. \r\n Thanks for being with Spark delivery",
          ];
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          $response = curl_exec($ch);
          curl_close($ch);
        }elseif($request->status==8){
            $returncharge = $parcel->deliveryCharge/2;
            $parcel->merchantAmount=$parcel->merchantAmount-$returncharge;
            $parcel->merchantDue=$parcel->merchantAmount-$returncharge;
            $parcel->deliveryCharge= $parcel->deliveryCharge+$returncharge;
            $parcel->save();
        }elseif($request->status==9){
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
    	Toastr::success('message', 'Parcel information update successfully!');
    	return redirect()->back();
    }

    public function create(){
        $merchants = Merchant::orderBy('id','DESC')->get();
        $delivery=Deliverycharge::where('status',1)->get();
        return view('backEnd.addparcel.create',compact('merchants','delivery'));
    }
    
    public function parcelstore(Request $request){
      $this->validate($request,[
        'cod'=>'required',
        'name'=>'required',
        'address'=>'required',
        'phonenumber'=>'required',
      ]);
      
     $charge = Merchantcharge::where(['merchantId'=>$request->merchantId,'packageId'=>$request->orderType])->first();

     if($request->weight > 1 || $request->weight !=NULL){
      $extraweight = $request->weight-1;
       $deliverycharge = ($charge->delivery*1)+($extraweight*$charge->extradelivery);
       $weight = $request->weight;
     }else{
      $deliverycharge = $charge->delivery;
      $weight = 1;
     }
     if($request->cod > 100){
      // $extracod=$request->cod -100;
      // $extracodcharge = $extracod/100;
      // $codcharge = $charge->cod+$extracodcharge;
      $codcharge= $charge->cod;
     }else{
      $codcharge= $charge->cod;
     }
     $store_parcel                    = new Parcel;
     $store_parcel->invoiceNo         = $request->invoiceno;
     $store_parcel->merchantId        = $request->merchantId;
     $store_parcel->percelType        = $request->parcelType;
     $store_parcel->cod               = $request->cod;
     $store_parcel->recipientName     = $request->name;
     $store_parcel->recipientAddress  = $request->address;
     $store_parcel->recipientPhone    = $request->phonenumber;
     $store_parcel->productWeight     = $weight;
     $store_parcel->trackingCode      = 'sp-'.mt_rand(111111,999999);
     $store_parcel->note              = $request->note;
     $store_parcel->deliveryCharge    = $deliverycharge;
     $store_parcel->codCharge         = $codcharge;
     $store_parcel->reciveZone        = $request->reciveZone;
     $store_parcel->merchantAmount    = ($request->cod)-($deliverycharge+$codcharge);
     $store_parcel->merchantDue       = ($request->cod)-($deliverycharge+$codcharge);
     $store_parcel->orderType         = $request->orderType;
     $store_parcel->codType           = 1;
     $store_parcel->status = 1;
     $store_parcel->save();
     Toastr::success('Success!', 'Thanks! your parcel add successfully');
     return redirect('editor/parcel/create');
  } 

  public function parceledit($id){
    $edit_data = Parcel::find($id);
    $merchants = Merchant::orderBy('id','DESC')->get();
    $delivery=Deliverycharge::where('status',1)->get();
    return view('backEnd.addparcel.edit',compact('edit_data','merchants','delivery'));
  }
  public function parcelupdate(Request $request){
        $this->validate($request,[
            'cod'=>'required',
            'name'=>'required',
            'percelType'=>'required',
            'address'=>'required',
            'weight'=>'required',
            'phonenumber'=>'required',
          ]);
         $update_parcel                    = Parcel::find($request->hidden_id);
         $update_parcel->invoiceNo         = $request->invoiceno;
         $update_parcel->merchantId        = $request->merchantId;
         $update_parcel->cod               = $request->cod;
         $update_parcel->percelType        = $request->percelType;
         $update_parcel->recipientName     = $request->name;
         $update_parcel->recipientAddress  = $request->address;
         $update_parcel->recipientPhone    = $request->phonenumber;
         $update_parcel->productWeight     = $request->weight;
         $update_parcel->note              = $request->note;
         $update_parcel->deliveryCharge    = $request->deliveryCharge;
         $update_parcel->codCharge         = $request->codCharge;
         $update_parcel->merchantAmount    = ($request->cod)-($request->deliverycharge+$request->codCharge);
         $update_parcel->merchantDue       = ($request->cod)-($request->deliveryCharge+$request->codCharge);
         $update_parcel->orderType         = $request->orderType;
         $update_parcel->save();
         Toastr::success('Success!', 'Thanks! your parcel update successfully');
         return redirect()->back();
  }
    
}
