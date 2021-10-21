<?php
namespace App\Http\Controllers\FrontEnd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Blog;
use App\Banner;
use App\Price;
use App\Parcel;
use App\Service;
use App\District;
use App\Feature;
use App\Deliverycharge;
use App\Codcharge;
use App\Partner;
use App\Parcelnote;
use App\About;
use App\Counter;
use App\Clientfeedback;
use App\Merchantcharge;
use App\Career;
use App\Faq;
use App\Gallery;
use App\Notice;
use DB;
use Session;
class FrontEndController extends Controller
{
    public function index(){
        $banner = Banner::where('status',1)->orderBy('id','DESC')->limit(1)->get();
        $about = About::where('status',1)->limit(1)->orderBy('id','DESC')->get();
        $prices = Price::where('status',1)->limit(4)->orderBy('id','ASC')->get();
        $features = Feature::where('status',1)->orderBy('id','ASC')->get();
        $clientsfeedback = Clientfeedback::where('status',1)->orderBy('id','DESC')->get();
        $blogs = Blog::where('status',1)->limit(3)->orderBy('id','DESC')->get();
        $features = Feature::where('status',1)->limit(15)->orderBy('id','DESC')->get();
        return view('frontEnd.index',compact('banner','about','prices','features','clientsfeedback','blogs','features'));
    }
    public function login(){
        return view('backEnd.setting.login');
    }
    public function register(){
       
        return view('frontEnd.layouts.pages.register');
    }
    public function marchentlogin(){
        if(Session::get('merchantId')){
           return redirect('merchant/dashboard');
        }else{    
          return view('frontEnd.layouts.pages.marchentlogin');
        }
    }
    public function parceltrack(Request $request){
         $trackparcel = DB::table('parcels')
        ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         ->where('parcels.trackingCode',$request->trackparcel)
         ->select('parcels.*','nearestzones.zonename')
         ->first();
        if($trackparcel){
            $trackInfos = Parcelnote::where('parcelId',$trackparcel->id)->orderBy('id','ASC')->get();

             return view('frontEnd.layouts.pages.trackparcel',compact('trackparcel','trackInfos'));
        }else{
            return redirect()->back();
        }
    }
    public function parceltrackget($id){
         $trackparcel = DB::table('parcels')
        ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         ->where('parcels.trackingCode',$id)
         ->select('parcels.*','nearestzones.zonename')
         ->orderBy('id','DESC')
         ->first();
         
         
        if($trackparcel){
            $trackInfos = Parcelnote::where('parcelId',$trackparcel->id)->orderBy('id','ASC')->get();
                        // return $trackInfos;
             return view('frontEnd.layouts.pages.trackparcel',compact('trackparcel','trackInfos'));
        }else{
            return redirect()->back();
        }
    }
   
    public function aboutus(){
         $aboutus = About::where('status',1)->limit(1)->orderBy('id','DESC')->get();
        return view('frontEnd.layouts.pages.aboutus',compact('aboutus'));
    }
   
    public function blog(){
        $blogs = Blog::where('status',1)->paginate(12);
        return view('frontEnd.layouts.pages.blog',compact('blogs'));
    }
    public function blogdetails($id){
        $blogdetails = Blog::where(['status'=>1,'id'=>$id])->first();
        if($blogdetails){
            return view('frontEnd.layouts.pages.blogdetails',compact('blogdetails'));
        }else{
            return redirect()->back();
        }
    }
    public function price(){
        $prices = Price::where('status',1)->orderBy('id','ASC')->get();
        return view('frontEnd.layouts.pages.price',compact('prices'));
    }

    public function features(){
        $features = Feature::where('status',1)->orderBy('id','ASC')->get();
        return view('frontEnd.layouts.pages.features',compact('features'));
    }

    public function featuredetails($id){
        $feature = Feature::where(['status'=>1,'id'=>$id])->first();
        if($feature){
            return view('frontEnd.layouts.pages.featuredetails',compact('feature'));
        }else{
            return redirect()->back();
        }
    }
    
    public function contact(){
        return view('frontEnd.layouts.pages.contact');
    }
    public function termscondition(){
        return view('frontEnd.layouts.pages.termscondition');
    }
    public function faq(){
        return view('frontEnd.layouts.pages.faq');
    }
    public function privacy(){
        return view('frontEnd.layouts.pages.privacy');
    }

    public function delivryCharge($id){
        $charge = Merchantcharge::where(['merchantId'=>Session::get('merchantId'),'packageId'=>$id])->first();
        Session::put('deliverycharge',$charge->delivery);
        Session::put('exdeliverycharge',$charge->extradelivery);
        Session::put('codcharge',$charge->cod);
        return response()->json(compact('charge'));
    }
    public function costCalculate($cod,$weight){
        if($weight > 1){
          $extraweight = $weight-1;
          $deliverycharge = (Session::get('deliverycharge')*1)+($extraweight*Session::get('exdeliverycharge'));
         }else{
          $deliverycharge = (Session::get('deliverycharge'));
         }
        
         if($cod > 1000){
          $extracod=$cod -1000;
          $extracodcharge = $extracod/100;
          $codcharge = Session::get('codcharge')+$extracodcharge;
         }else{
          $codcharge = Session::get('codcharge');
         }
         Session::put('codpay',$cod);
         Session::put('pdeliverycharge',$deliverycharge);
         Session::put('pcodecharge',$codcharge);
        return response()->json($deliverycharge);
        
    }
    
    public function costCalculateResult(){
        return view('frontEnd.layouts.pages.costcalculate');
    }

   
     
}
