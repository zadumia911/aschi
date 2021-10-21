@extends('backEnd.layouts.master')
@section('title','Manage Deliveryman')
@section('content')
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
                          <h5>Manage Deliveryman</h5>
                        </div>
                        <div class="quick-button">
                          <a href="{{url('admin/deliveryman/add')}}" class="btn btn-primary btn-actions btn-create">
                          <i class="fa fa-plus"></i> Add New
                          </a>
                        </div>
                      </div>
                    </div>
                  <div class="card-body">
                    <table id="example" class="table table-bordered table-striped custom-table">
                      <thead>
                      <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($show_datas as $key=>$value)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$value->name}}</td>
                          <td>{{$value->email}}</td>
                          <td>{{$value->phone}}</td>
                          <td>{{$value->status==1? "Active":"Inactive"}}</td>
                          <td>
                            <ul class="action_buttons dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action Button
                                <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                <li>
                                  @if($value->status==1)
                                  <form action="{{url('admin/deliveryman/inactive')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                    <button type="submit" class="thumbs_down" title="unpublished"><i class="fa fa-thumbs-down"></i> Inactive</button>
                                  </form>
                                  @else
                                    <form action="{{url('admin/deliveryman/active')}}" method="POST">
                                      @csrf
                                      <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                      <button type="submit" class="thumbs_up" title="published"><i class="fa fa-thumbs-up"></i> Active</button>
                                    </form>
                                  @endif
                                </li>
                                 <li>
                                    <a class="thumbs_up" href="{{url('admin/deliveryman/edit/'.$value->id)}}" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                  </li>
                                  <li>
                                      <a class="edit_icon" href="{{url('admin/deliveryman/view/'.$value->id)}}" title="View"><i class="fa fa-eye"></i> View</a>
                                  </li>
                                  <li>
                                      <a class="edit_icon" href="{{url('admin/deliveryman/payment/invoice/'.$value->id)}}" title="View"><i class="fa fa-list"></i> Payment</a>
                                  </li>
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