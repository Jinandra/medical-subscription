
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>General</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Media</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('id_media', $media, isset($id_media) ? $id_media : Input::old('id_media'), array('class' => 'form-control col-md-7 col-xs-12') ) !!}
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Sort Order</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('sortOrder', isset($sortOrder) ? $sortOrder : Input::old('sortOrder'), array('placeholder' => 'Sort Order (i.e 1 or 2 and so on)', 'class' => 'form-control col-md-7 col-xs-12','required'=>'')) !!}
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
        <button type="reset" class="btn btn-primary">Cancel</button>
        <input type="submit" class="btn btn-success" name="submit" value="Submit">
      </div>
    </div>
