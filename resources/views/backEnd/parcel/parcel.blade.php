@extends('backEnd.layouts.master')
@section('title',$parceltype->title)
@section('content')
<style>
@media screen {
  #printSection {
      display: none;
  }
}

@media print {
  body * {
    visibility:hidden;
  }
  #printSection, #printSection * {
    visibility:visible !important;
  }
  #printSection {
    position:absolute !important;
    left:0;
    top:0;
  }
}
</style>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="box-content">
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card custom-card">
                    <div class="col-sm-12">
                      <div class="manage-button">
                        <div class="body-title">
                          <h5>{{$parceltype->title}} Parcel</h5>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                      <form action="" class="filte-form">
                        @csrf
                        <div class="row">
                          <input type="hidden" value="1" name="filter_id">
                          <div class="col-sm-2">
                            <input type="text" class="form-control" placeholder="Track Id" name="trackId">
                          </div>
                          <!-- col end -->
                          <div class="col-sm-2">
                            <input type="number" class="form-control" placeholder="Phone Number" name="phoneNumber">
                          </div>
                          <!-- col end -->
                          <div class="col-sm-2">
                            <input type="number" class="form-control" placeholder="Merchant Id" name="merchantId">
                          </div>
                          <!-- col end -->
                          <div class="col-sm-2">
                            <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate">
                          </div>
                          <!-- col end -->
                          <div class="col-sm-2">
                            <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate">
                          </div>
                          <!-- col end -->
                          <div class="col-sm-2">
                            <button type="submit" class="btn btn-success">Submit </button>
                          </div>
                          <!-- col end -->
                        </div>
                      </form>
                    </div>
                  <div class="card-body">
                    <table id="example" class="table table-bordered table-striped custom-table table-responsive">
                      <thead>
                      <tr>
                        <th>Id</th>
                        <th>Company Name</th>
                        <th>Ricipient</th>
                        <th>Tracking ID</th>
                        <th>Area</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Rider</th>
                        <th>Agent</th>
                        <th>L. Update</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Charge</th>
                        <th>Sub Total</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($show_data as $key=>$value)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          @php
                            $merchant = App\Merchant::find($value->merchantId);
                            $agentInfo = App\Agent::find($value->agentId);
                            $deliverymanInfo = App\Deliveryman::find($value->deliverymanId);
                          @endphp
                           <td>{{$merchant->companyName}}</td>
                          <td>{{$value->recipientName}}</td>
                          <td>{{$value->trackingCode}}</td>
                          <td>{{$value->zonename}}</td>
                          <td>{{$value->recipientAddress}}</td>
                          <td>{{$value->recipientPhone}}</td>
                          <td>@if($value->deliverymanId) {{$deliverymanInfo->name}} @else <button class="btn btn-primary" data-toggle="modal" data-target="#asignModal{{$value->id}}">Assign</button> @endif</td>
                          <!-- Modal -->
                          <div id="asignModal{{$value->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Deliveryman Assign</h5>
                                </div>
                                <div class="modal-body">
                                  <form action="{{url('editor/deliveryman/asign')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                    <input type="hidden" name="merchant_phone" value="{{$merchant->phoneNumber}}">
                                    <div class="form-group">
                                      <select name="deliverymanId" class="form-control" id="">
                                        <option value="">Select</option>
                                        @foreach($deliverymen as $key=>$deliveryman)
                                        <option value="{{$deliveryman->id}}">{{$deliveryman->name}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <!-- form group end -->
                                    <div class="form-group">
                                      <textarea name="note" class="form-control" ></textarea>
                                    </div>
                                    <!-- form group end -->
                                    <div class="form-group">
                                      <button class="btn btn-success">Update</button>
                                    </div>
                                    <!-- form group end -->
                                  </form>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Modal end -->
                          <td>@if($value->agentId) {{$agentInfo->name}} @else <button class="btn btn-primary" data-toggle="modal" data-target="#agentModal{{$value->id}}">Asign</button> @endif</td>
                          <!-- Modal -->
                          <div id="agentModal{{$value->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Agent Asign</h5>
                                </div>
                                <div class="modal-body">
                                  <form action="{{url('editor/agent/asign')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                    <input type="hidden" name="merchant_phone" value="{{$merchant->phoneNumber}}">
                                    <div class="form-group">
                                      <select name="agentId" class="form-control" id="">
                                        <option value="">Select</option>
                                        @foreach($agents as $key=>$agent)
                                        <option value="{{$agent->id}}">{{$agent->name}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    
                                    <div class="form-group">
                                      <textarea name="note" class="form-control" ></textarea>
                                    </div>
                                    
                                    <!-- form group end -->
                                    <div class="form-group">
                                      <button class="btn btn-success">Update</button>
                                    </div>
                                    <!-- form group end -->
                                  </form>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Modal end -->
                          <td>{{date('F d, Y', strtotime($value->updated_at))}}</td>
                          <td>{{$parceltype->title}}</td>
                          <td> {{$value->cod}}</td>
                          <td> {{$value->deliveryCharge+$value->codCharge}}</td>
                          <td> {{$value->cod-($value->deliveryCharge+$value->codCharge)}}</td>
                          <td>
                            <ul class="action_buttons">
                                 <li><a href="{{url('editor/parcel/edit/'.$value->id)}}" class="edit_icon"><i class="fa fa-edit"></i></a></li>
                                  <li>
                                  @if(Auth::user()->role_id <= 2 )
                                  <li>
                                      <button class="thumbs_up" title="Action" data-toggle="modal" data-target="#sUpdateModal{{$value->id}}"><i class="fa fa-sync-alt"></i></button>
                                      <!-- Modal -->
                                      <div id="sUpdateModal{{$value->id}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                          <!-- Modal content-->
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title">Parcel Status Update</h5>
                                            </div>
                                            <div class="modal-body">
                                              <form action="{{url('editor/parcel/status-update')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                                <input type="hidden" name="customer_phone" value="{{$value->recipientPhone}}">
                                                <div class="form-group">
                                                    <select name="status"  onchange="percelDelivery(this)" class="form-control" id="">
                                                      @foreach($parceltypes as $key=>$ptvalue)
                                                        <option value="{{$ptvalue->id}}"@if($value->status==$ptvalue->id) selected="selected" @endif>{{$ptvalue->title}}</option>
                                                        @endforeach
                                                  </select>
                                                </div>
                                                <div class="form-group mrt-15">
                                                  <textarea name="note" class="form-control" cols="30" placeholder="Note" required=""></textarea>
                                                </div>
                                                <!-- form group end -->
                                                <div class="form-group">
                                                  <div class="customerpaid" style="display: none;">
                                                      <input type="text" class="form-control" value="{{old('customerpay')}}" id="customerpay" name="customerpay"  placeholder="customer pay" /><br />
                                                  </div>
                                                </div>
                                                <!-- form group end -->
                                                <div class="form-group">
                                                  <button class="btn btn-success">Update</button>
                                                </div>
                                                <!-- form group end -->
                                              </form>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <!-- Modal end -->
                                  </li>
                                  @endif
                                 <li>
                                      <button class="edit_icon" href="#"  data-toggle="modal" data-target="#merchantParcel{{$value->id}}" title="View"><i class="fa fa-eye"></i></button>
                                      <div id="merchantParcel{{$value->id}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                          <!-- Modal content-->
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title">Parcel Details</h5>
                                            </div>
                                            <div class="modal-body">
                                              <table class="table table-bordered">
                                                <tr>
                                                  <td>Merchant Name</td>
                                                  <td>{{$value->firstName}} {{$value->lastName}}</td>
                                                </tr>
                                                <tr>
                                                  <td>Merchant Phone</td>
                                                  <td>{{$value->phoneNumber}}</td>
                                                </tr>
                                                <tr>
                                                  <td>Merchant Email</td>
                                                  <td>{{$value->emailAddress}}</td>
                                                </tr>
                                                <tr>
                                                  <td>Company</td>
                                                  <td>{{$value->companyName}}</td>
                                                </tr>
                                                  <td>Recipient Name</td>
                                                  <td>{{$value->recipientName}}</td>
                                                </tr>
                                                <tr>
                                                  <td>Recipient Address</td>
                                                  <td>{{$value->recipientAddress}}</td>
                                                </tr>
                                                <tr>
                                                  <td>COD</td>
                                                  <td>{{$value->cod}}</td>
                                                </tr>
                                                <tr>
                                                  <td>C. Charge</td>
                                                  <td>{{$value->codCharge}}</td>
                                                </tr>
                                                <tr>
                                                  <td>D. Charge</td>
                                                  <td>{{$value->deliveryCharge}}</td>
                                                </tr>
                                                <tr>
                                                  <td>Sub Total</td>
                                                  <td>{{$value->merchantAmount}}</td>
                                                </tr>
                                                <tr>
                                                  <td>Paid</td>
                                                  <td>{{$value->merchantPaid}}</td>
                                                </tr>
                                                <tr>
                                                  <td>Due</td>
                                                  <td>{{$value->merchantDue}}</td>
                                                </tr>
                                                <tr>
			                                  		<td>Last Update</td>
			                                  		<td>{{date('F d, Y', strtotime($value->updated_at))}}</td>
			                                  	</tr>
                                              </table>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <!-- Modal end -->
                                </li>
                                @if($value->status > 1)
                                
                                  <li>
                                    <a class="edit_icon anchor" a href="{{url('editor/parcel/invoice/'.$value->id)}}" title="Invoice"><i class="fa fa-list"></i></a>
                                 </li>
                                @endif
                              </ul>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
          </div>
        </div>
    </div>
  </section>

<!-- Modal Section  -->
@endsection
