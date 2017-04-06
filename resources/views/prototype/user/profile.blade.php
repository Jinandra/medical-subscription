@extends('prototype.userLayout')

@section('title')
  User Profile Page
@stop

@section('sidebar')
  @include('prototype.sidebar')
@stop

@section('content')
    <br/>
    <div class="container-fluid">
    <form class="form-horizontal" method="post" action="{{ url('user/profile') }}">
        <div class="row">
            <h4>Edit Profile</h4>            
        </div>
        <div class="row">
                @if (count($errors) > 0)                    
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (Session::has('message'))                    
                    <div class="alert alert-success">
                        <ul>                        
                            <li>Profile successfully updated.</li>
                        </ul>
                    </div>
                @endif
            
                <div class="form-group">
                    <label for="screenName" class="col-sm-3 control-label">Screen Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="screenName" name="screen_name" placeholder="Screen Name" readonly disabled value="{{ $user->screen_name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="first_name" class="col-sm-3 control-label">First Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="{{ $user->first_name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="last_name" class="col-sm-3 control-label">Last Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="{{ $user->last_name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{ $user->email }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="col-sm-3 control-label">Address</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" id="address" name="address" placeholder="Address" cols="20" rows="5">{{ $user->address }}</textarea>                        
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="col-sm-3 control-label"></label>
                    <div class="col-sm-8">
                        <input value="Update" class="btn btn-primary text-center" type="submit" id="update" />
                    </div>
                </div>
                <input type="hidden" name="id" value="{{ $user->id }}" readonly>
                {{ csrf_field() }}
            
        </div>

        <div class="row">
            <h4>Change Password</h4>            
        </div>
        <div class="row">
            <div class="form-group">
                <label for="password" class="col-sm-3 control-label">New Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="col-sm-3 control-label">Password Confirmation</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Password Confirmation">
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-sm-3 control-label"></label>
                <div class="col-sm-8">
                    <input value="Change Password" class="btn btn-primary text-center" type="submit" id="update2" />
                </div>
            </div>
        </div>
    </form>

    </div>
@stop
