@extends('prototype.userLayout')

@section('title')
  Edit Media Page
@stop

@section('sidebar')
  @include('prototype.sidebar')
@stop

@section('content')
	<div class="col-sm-offset-2 col-sm-8">
		<h2>Media Update</h2>
		<form method="POST" class="form-horizontal" action="{{ url('/contribute/'.$media->id.'/update') }}  ">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
				<label for="title" class="col-sm-2 control-label">Title</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="title" id="title" placeholder="Media Title" size="255" value="{{ $media->title }}">
				</div>
			</div>
      <div class="form-group">
        <label for="web_link" class="col-sm-2 control-label">Web Link</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="web_link" id="web_link" placeholder="Web Link" value="{{ $media->web_link }}">
        </div>
      </div>
			<div class="form-group">
        <label for="description" class="col-sm-2 control-label">Description</label>
        <div class="col-sm-10">
					<textarea id="description" name="description" class="form-control" rows="3" placeholder="Description">{{ $media->description }}</textarea>
        </div>
      </div>
			<div class="form-group">
        <label for="type" class="col-sm-2 control-label">Select Type</label>
        <div class="radio col-sm-10">
					<label class="radio-inline">
            <input type="radio" id="type" name="type" id="inlineRadio1" value="video" @if($media->type == "video") checked @endif>
						Video
          </label>
          <label class="radio-inline">
            <input type="radio" id="type" name="type" id="inlineRadio2" value="image" @if($media->type == "image") checked @endif>
						Image
          </label>
          <label class="radio-inline">
						<input type="radio" id="type" name="type" id="inlineRadio3" value="text" @if($media->type == "text") checked @endif>
						Text
          </label>
        </div>
      </div>
			<?php
			$count =0;
			?>
			<div class="form-group">
        <label for="tag" class="col-sm-2 control-label">Tag</label>
			@foreach($tagSequence as $tag)
					@if($count%5 ==0)
						@if($count!=0)
							</div>
						@endif
						@if($count!=15)
							<div class="col-sm-2">
						@endif
					@endif
					<div class="checkbox">
            <label>
							<?php $condition = false; ?>
							@if(isset($mTags))
								@foreach($mTags as $mTag)
									@if($mTag == $tag)
										<?php $condition = true ?>
									@endif
								@endforeach
							@endif
							<input type="checkbox" id="tag" name="tag[]" value="{{ $tag }}" @if($condition) checked @endif>{{ $tag }}
						</label>
          </div>
				<?php
				$count++;
				?>
			@endforeach
      </div></div>
			<div class="form-group">
        <label for="tag2" class="col-sm-2 control-label">Type Tag</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="customTag" id="customTag" placeholder="Type Tag" value="{{ $customTags }}">
					<span id="help" class="help-block">Separate new tag with comma. Example: tag1,tag2</span>
        </div>
      </div>
			<div class="form-group">
				<label for="private" class="col-sm-2 control-label">Private</label>
				<div class="radio col-sm-10">
          <label class="radio-inline">
            <input type="radio" id="private" name="private" id="inlineRadio1" value="yes" @if($media->private == "yes") checked @endif>
            Yes
          </label>
          <label class="radio-inline">
            <input type="radio" id="private" name="private" id="inlineRadio2" value="no" @if($media->private == "no") checked @endif>
            No
          </label>
				</div>				
			</div> 			
			<button type="submit">UPDATE</button>
		</form>
	</div>
@stop
