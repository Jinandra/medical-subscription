@extends('prototype.userLayout')

@section('title')
	Create Bundle
@stop

@section('sidebar')
  @include('prototype.sidebar')
@stop

@section('content')
	<div class="col-sm-10">
		<h2>Create Bundle</h2>
		<table class="table table-bordered" style="width: auto;">
			<tr>
				<th>Title</th>
				<th>Info</th>
				<th>Action</th>
			</tr>
			@foreach($media as $row)
			<tr>
				<td><a href="/media/{{ $row->id }}">{{ $row->title }}</a></td>
				<td><span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ $row->description }}"></span>
				</td>
				<td>
					<a href="/bundle/cart/{{ $row->id }}/delete">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
			</tr>
			@endforeach
		</table>
		<form method="POST" class="form-horizontal" action="{{ url('/bundle/cart/sto') }}re">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="ucode2" value="{{ $ucode }}">
			<div class="form-group">
        <label for="ucode" class="col-sm-3 control-label">UCODE</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="ucode" id="ucode" value="{{ $ucode }}" disabled>
        </div>
      </div>
			<div class="form-group">
				<label for="email_ucode" class="col-sm-3 control-label">EMAIL UCODE</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="email_ucode" id="email_ucode" placeholder="Enter Email">
				</div>
			</div>
			<div class="form-group">
        <label for="text_ucode" class="col-sm-3 control-label">TEXT UCODE</label>
        <div class="col-sm-9">
         	<input type="text" class="form-control" name="text_ucode" id="text_ucode" placeholder="Enter Phone Number">
        </div>
      </div>
			<div class="checkbox">
    	<label>
      	<input type="checkbox">Notification
    	</label>
  		</div>
			<br/>
			<button class="btn btn-default" type="submit">SUBMIT</button>"
		</form>	
	</div>
@stop
