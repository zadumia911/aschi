@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title','Dashboard')
@section('content')
<section  class="section-padding dashboard-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
      <div class="stats-reportList-inner">
        <div class="row">
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-info">
              <div class="stats-per-item">
                <h5>Total Parcel</h5>
                <h3>{{$placepercel}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-secondary">
              <div class="stats-per-item">
                <h5>Total Pending</h5>
                <h3>{{$pendingparcel}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-danger">
              <div class="stats-per-item">
                <h5>In Transit</h5>
                <h3>{{$intransitparcel}}</h3>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-success">
              <div class="stats-per-item">
                <h5>Total Delivered</h5>
                <h3>{{$deliverd}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-danger">
              <div class="stats-per-item">
                <h5>Total Cancelled</h5>
                <h3>{{$cancelparcel}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-warning">
              <div class="stats-per-item">
                <h5>Returned To Merchant</h5>
                <h3>{{$parcelreturn}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-info">
              <div class="stats-per-item">
                <h5>Total Hold</h5>
                <h3>{{$totalhold}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-primary">
              <div class="stats-per-item">
                <h5>Collected Amount From DA</h5>
                <h3>{{$collectamount}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-success">
              <div class="stats-per-item">
                <h5>Total Paid</h5>
                <h3>{{$totalpaid}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
        </div>
      </div>
      <!-- dashboard parcel -->
      <div class="dashboard-payment-info">
        <div class="row">
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-dark">
              <div class="stats-per-item">
                <h5>Total Amount</h5>
                <h3>{{$totalamount}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-success">
              <div class="stats-per-item">
                <h5>Paid Amount</h5>
                <h3>{{$merchantPaid}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-danger">
              <div class="stats-per-item">
                <h5>Unpaid Amount</h5>
                <h3>{{$merchantUnPaid}}</h3>
              </div>
            </div>
          </div>
          
        </div>
        <div class="row">
          <div class="col-sm-12">
           <div class="card">
             <div class="card-header">
               <h3>Parcel Statistics</h3>
             </div>
             <div class="card-body">
               <canvas id="myChart"></canvas>
             </div>
           </div>
          </div>
        </div>
      </div>
      <!-- dashboard payment -->
      </div>
    </div>
  </div>
</section>
<script>
   var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'pie',
    margin: 250,

    // The data for our dataset
    data: {
        labels: [@foreach($parceltypes as $parceltype)'{{$parceltype->title}}',@endforeach],
        datasets: [{
            label: 'Parcel Statistics',
            backgroundColor:['#1D2941','#5F45DA','#670A91','#096709','#FFAC0E','#AAB809','#2094A0','#9A8309','#C21010'],
            borderColor:['#1D2941','#5F45DA','#670A91','#096709','#FFAC0E','#AAB809','#2094A0','#9A8309','#C21010'],
             data: [@foreach($parceltypes as $parceltype)
             @php
             $parcelcount = App\Parcel::where(['status'=>$parceltype->id,'merchantId'=>Session::get('merchantId')])->count();
             @endphp {{$parcelcount}}, @endforeach]
        }]
    },

    // Configuration options go here
        options: {
            plugins: {
                title: {
                    display: true,
                    padding: {
                        top: 50,
                        bottom: 50
                    }
                }
            }
        },
});
 </script>
@endsection