@extends('prototype.userLayout')

@section('title')
  Contribute Page
@stop

@section('sidebar')
  @include('prototype.sidebar')
@stop

@section('content')
<script type="text/javascript">
$(document).ready(function(){
    $('input[type="radio"]').click(function(){
        if($(this).attr("value")=="video"){
            $(".upload").hide();
						$(".link").show();
        }
        if($(this).attr("value")=="image"){
            $(".upload").show();
						$(".link").hide();
        }
        if($(this).attr("value")=="text"){
            $(".upload").show();
        		$(".link").hide();
				}
    });
});
</script>

	<div class="col-sm-offset-2 col-sm-8">
		<h2>Media Submission</h2>
		<form method="POST" class="form-horizontal" action="{{ url('/contribute/add') }}" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
				<label for="title" class="col-sm-2 control-label">Title</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="title" id="title" placeholder="Media Title">
				</div>
			</div>
			<div class="form-group">
        <label for="description" class="col-sm-2 control-label">Description</label>
        <div class="col-sm-10">
					<textarea id="description" name="description" class="form-control" rows="3" placeholder="Description"></textarea>
        </div>
      </div>
			<div class="form-group">
        <label for="type" class="col-sm-2 control-label">Select Type</label>
        <div class="radio col-sm-10">
					<label class="radio-inline">
            <input type="radio" id="type" name="type" id="inlineRadio1" value="video" checked>Video
          </label>
          <label class="radio-inline">
            <input type="radio" id="type" name="type" id="inlineRadio2" value="image">Image
          </label>
          <label class="radio-inline">
            <input type="radio" id="type" name="type" id="inlineRadio3" value="text">Text
          </label>
        </div>
      </div>
			<div class="form-group upload" style="display:none">
				<label for="file_upload" class="col-sm-2 control-label">File Upload</label>
        <div class="col-sm-10">
          <input type="file" class="form-control" name="file_upload" id="file_upload" placeholder="File Upload">
        </div>
			</div>
			<div class="form-group link">
        <label for="web_link" class="col-sm-2 control-label">Web Link</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="web_link" id="web_link" placeholder="Web Link">
        </div>
      </div>
			<div class="form-group">
        <label for="tag" class="col-sm-2 control-label">Tag</label>
        <div class="col-sm-2">
        	<div class="checkbox">
						<label>
							<input type="checkbox" id="tag" name="tag[]" value="treatment">Treatment
						</label>
					</div>
					<div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="simple">Simple
            </label>
          </div>
					<div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="complex">Complex
            </label>
          </div>
					<div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="funny">Funny
            </label>
          </div>
					<div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="animation">Animation
            </label>
          </div>
				</div>
				<div class="col-sm-2">
          <div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="pathology">Pathology
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="detailed">Detailed
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="short">Short
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="long">Long
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="creative">Creative
            </label>
          </div>
        </div>
				<div class="col-sm-2">
          <div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="graphic">Graphic
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="anatomy">Anatomy
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="symptoms">Symptoms
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="physiology">Physiology
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" id="tag" name="tag[]" value="procedure">Procedure
            </label>
          </div>
        </div>
      </div>
			<div class="form-group">
        <label for="customTag" class="col-sm-2 control-label">Type Tag</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="customTag" id="customTag" placeholder="Type Tag">
					<span id="help" class="help-block">Separate new tag with comma. Example: tag1,tag2</span>
        </div>
      </div>
			<div>
				<label for="private"	 class="col-sm-2 control-label">Private</label>
				<div class="radio col-sm-10">
          <label class="radio-inline">
            <input type="radio" id="private" name="private" id="inlineRadio1" value="1" checked>Yes
          </label>
          <label class="radio-inline">
            <input type="radio" id="private" name="private" id="inlineRadio2" value="0">No
          </label>
        </div>				
			</div>		
			<br/>	
			<button type="submit">SUBMIT</button>
		</form>

		<br/><br/>
		<table class="table table-bordered" style="width: auto;">
      <tr>
				<th>Title</th>
				<th>Submission date</th>
<!--		<th>Likes</th>
				<th>Verified</th>
				<th>List</th>
-->			<th>View</th>
				<th>Info</th>
				<th>Action</th>
			</tr>
			@foreach($media as $row)
			<tr>
				<td>
					<a href="{{ url('/media/'.$row->id) }}">{{ $row->title }}</a>
				</td>
				<td>{{ $row->created_at }}</td>
<!--		<td>98%</td>
				<td>50%</td>
				<td></td>
-->			<td>{{ $row->view_count }}</td>
				<td><span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ $row->description }}"></span>
				</td>
				<td>
					<a href="{{ url('/contribute/'.$row->id.'/edit') }}  ">
						<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
					</a>
					&nbsp;&nbsp;
					<a href="{{ url('/contribute/'.$row->id.'/delete') }} ">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
					</a>
				</td>
			</tr>
			@endforeach
		</table>
	</div>
@stop
