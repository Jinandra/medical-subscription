{{--
  -- PARAMS:
  -- $category => isset for edit mode
  --}}

<div class="page-title">
  <div class="title_left">
    <h3>{{ isset($category) ? 'Edit' : 'Add New' }} Category</h3>
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
  action="{{ isset($category) ? route('admin::categories::patch', ['id' => $category->id]) : route('admin::categories::create') }}"
  class="form-horizontal form-label-left"
>
  @if (isset($category))
    <input type="hidden" name="_method" value="PATCH" />
  @endif
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Category</h2>
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
                is_null(old('name')) ? ( isset($category) ? $category->name : null) : old('name'),
                [
                  'placeholder' => 'Category name',
                  'class' => 'form-control col-md-7 col-xs-12',
                  'required'=> true
                ]
              ) !!}
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Description</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              {!! Form::textarea('description',
                is_null(old('description')) ? ( isset($category) ? $category->description : null) : old('description'),
                [
                  'placeholder' => 'Category description',
                  'class' => 'form-control col-md-7 col-xs-12',
                  'size' => '50x3'
                ]
              ) !!}
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
      <a class="btn btn-default" href="{{ route('admin::categories::index') }}">Back</a>
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
