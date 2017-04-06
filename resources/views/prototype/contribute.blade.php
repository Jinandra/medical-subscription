@extends('prototype.userLayout')

@section('title')
  Contribute Page
@stop

@section('sidebar')
  <li><a href="/user">HOME</a></li>
  <li><a href="/user/profile">PROFILE</a></li>
  <li><a href="/list">LIST</a></li>
  <li><a href="/bundle">BUNDLE</a></li>
  <li class="active"><a href="/contribute">CONTRIBUTE</a></li>
  <li><a href="/account">ACCOUNT</a></li>
  <li><a href="/aboutus">ABOUT US</a></li>
@stop

@section('content')
	<div class="col-sm-offset-2 col-sm-8">
    <table class="table table-bordered" style="width: auto;">
      <tr>
				<th colspan="2">Media Submission</th>
			</tr>
			<tr>
				<td>Title</td>
				<td>Asthma Treatment</td>
			</tr>
			<tr>
        <td>Web Link</td>
        <td>https://www.youtube.com/watch?v=ZSMNXPlp9Xc</td>
      </tr>
			<tr>
        <td>Description</td>
        <td>This is a simple animated outline of the various treatment for asthma from albuterol inhaler, steroid inhaler, and nasal steroid</td>
      </tr>
			<tr>
        <td>Select Type</td>
        <td>
					<label class="radio-inline">
            <input type="radio" name="type" id="inlineRadio1" selected="true" checked>Video 
          </label>
          <label class="radio-inline">
            <input type="radio" name="type" id="inlineRadio2" value="off">Image
          </label>
					<label class="radio-inline">
            <input type="radio" name="type" id="inlineRadio2" value="off">Text
          </label>
				</td>
      </tr>
			<tr>
        <td>Select Tag</td>
        <td>Treatment Simple Complex Funny Animation Pathology Detailed Short Long Creative Graphic Anatomy Symptoms Physiology Procedure</td>
      </tr>
			<tr>
        <td>Type Tags</td>
				<td>Albuterol Dat Yellow Nasal 2016</td>
      </tr>
		</table>
		<button type="button">SUBMIT</button>
		<br/><br/>
		<table class="table table-bordered" style="width: auto;">
      <tr>
				<th>Title</th>
				<th>Submission date</th>
				<th>Likes</th>
				<th>Verified</th>
				<th>List</th>
				<th>View</th>
				<th>Info</th>
				<th>Delete</th>
			</tr>
			@for($i=0;$i<5;$i++)
			<tr>
				<td>Hypertension for old people</td>
				<td>10/20/2014</td>
				<td>98%</td>
				<td>50%</td>
				<td>10</td>
				<td>3000</td>
				<td>I</td>
				<td>X</td>
			</tr>
			@endfor
		</table>
	</div>
@stop
