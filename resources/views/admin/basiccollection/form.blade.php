{{--
  -- PARAMS:
  -- $collection => isset for edit mode
  -- $media => array of all media
  --}}

<div class="page-title">
  <div class="title_left">
    <h3>{{ isset($collection) ? 'Edit' : 'Add New' }} Basic Collection</h3>
      @if (Session::has('message'))
        <div role="alert" class="alert alert-success">
          {{ Session::get('message') }}
        </div>
      @endif

      @if($errors->has())
        <div role="alert" class="alert alert-danger">
          @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      @endif
  </div>
</div>
<div class="clearfix"></div>

<form id="f1"
  method="post"
  action="{{ URL::to('admin/basiccollections'.(isset($collection) ? '/'.$collection->id : '')) }}"
  class="form-horizontal form-label-left"
>
  @if (isset($collection))
    <input type="hidden" name="_method" value="PATCH" />
  @endif
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Basic Collection</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              {!! Form::text('name',
                is_null(old('name')) ? ( isset($collection) ? $collection->name : null) : old('name'),
                [
                  'placeholder' => 'Collection name',
                  'class' => 'form-control col-md-7 col-xs-12',
                  'required'=> true
                ]
              ) !!}
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Description</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              {!! Form::text('description',
                is_null(old('description')) ? ( isset($collection) ? $collection->description : null) : old('description'),
                [
                  'placeholder' => 'Collection description',
                  'class' => 'form-control col-md-7 col-xs-12'
                ]
              ) !!}
            </div>
          </div>
          <div class="form-group" style="margin-top:4em;">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Media</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="autocomplete" class="form-control" placeholder="type 2 chars for search, select to add..." />
              <br />
              <ul class="list-group manage-media">
                @if ( !is_null(old('media')) )
                  @foreach (old('media') as $medium_id)
                    <?php $medium = $media->where('id', intval($medium_id))->first(); ?>
                    <li class="list-group-item"><input type="hidden" name="media[]" value="{{ $medium_id }}" />{{ $medium->title }} <i class="glyphicon glyphicon-remove"></i></li>
                  @endforeach
                @elseif (isset($collection))
                  @foreach ($collection->media as $medium)
                    <li class="list-group-item"><input type="hidden" name="media[]" value="{{ $medium->id }}" />{{ $medium->title }} <i class="glyphicon glyphicon-remove"></i></li>
                  @endforeach
                @endif
              </ul>
            </div>
          </div>
          <div class="ln_solid"></div>
        </div>
      </div>
    </div>
  </div>

  <br/>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
      &nbsp;&nbsp;
      <button type="reset" class="btn btn-danger">Cancel</button>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <input type="submit" class="btn btn-primary" name="submit" value="Submit">
    </div>
  </div>
  {{ csrf_field() }}
  <div class="form-group">
    <div class="col-xs-12">
      <a class="btn btn-default" href="{{ url('admin/basiccollections') }}">Back</a>
    </div>
  </div>
</form>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-confirm-remove">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger">Remove</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  window.BASE_MEDIA_URL = '{{ url("media") }}';
  window.ALL_MEDIA = [
    @foreach ($media as $medium)
      { id: '{{ $medium->id }}', title: '{{ $medium->title }}' },
    @endforeach
  ];
  @if (isset($collection))
    window.COLLECTION_MEDIA = [
      @foreach ($collection->media as $medium)
        { id: '{{ $medium->id }}', title: '{{ $medium->title }}' },
      @endforeach
    ];
  @endif
</script>
<script src="{{ URL::asset('resources/assets/js/admin/basiccollections.js') }}"></script>

