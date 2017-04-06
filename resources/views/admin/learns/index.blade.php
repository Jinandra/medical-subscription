@include('admin.partial.header')
@include('admin.partial.sidebar')
@include('admin.partial.topnav')

<div class="right_col" role="main">
  <div class="page-title">
    <div class="title_left">            
      @if (Session::has('message'))
        <div role="alert" class="alert alert-success">
          {{ Session::get('message') }}
        </div>
      @endif
    </div>
  </div>
  <div class="clearfix"></div>

  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>All Learn Media</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <!--
        <div class="title_left">
          <div class="col-md-5 col-sm-5 col-xs-12 form-group">
            @if(Input::get('s'))
              <h4>Filter by : <span style="background:#DBF500;padding:2px 3px">{{Input::get('s')}}</span></h4>
            @endif
          </div>
        </div>

        <div class="title_right">
          <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
            <form name="f1" method="get" action="{{Url::to('admin/post')}}">
              <div class="input-group">
                <input type="text" name="s" class="form-control" placeholder="Search for Title/Content ...">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="submit">Go!</button>
                </span>
              </div>
            </form>
          </div>
        </div>
        -->

        <table class="table table-striped responsive-utilities jambo_table bulk_action">
        <thead>
          <tr class="headings">
            <th class="column-title" width="5%">Order</th>
            <th class="column-title" width="30%">Media</th>                            
            <th class="column-title" >Title</th>
            <th class="column-title no-link last" width="15%"><span class="nobr">Action</span></th>                           
          </tr>
        </thead>

        <tbody>
          @foreach ($media as $medium)
            <tr class="pointer">
              <td>{{ $medium->sortOrder }}</td>
              <td>
                @include('beta.partials.media.startThumbnail', ['media' => $medium])
                @include('beta.partials.media.endThumbnail')
              </td>
              <td>{{ $medium->title }}</td>
              <td class=" last">   
                <div class="btn-group">
                  <button type="button" class="btn btn-info">Action</button>
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">                                         
                    <li>
                      <a href="{{Url::to('admin/learns/delete?_token='.csrf_token().'&id='.$medium->id_learn)}}" class="confirm-ui" data-message='are you sure to delete ?'><i class='fa fa-remove'></i> Delete</a>
                    </li>
                    <li>
                      <a href="{{Url::to('admin/learns/edit?_token='.csrf_token().'&id='.$medium->id_learn)}}"><i class='fa fa-pencil'></i> Edit</a>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div id='dialog-form'></div>
</div>
<style>
.video-wrap {
  background: #eeeeee;
  height: 100px;
  position: relative;
  margin-top: 15px;
  margin-bottom: 10px;
  text-align: center;
  -webkit-background-size: ;
  background-size: cover;
  background-position: center;
  display: block;
}
</style>

@include('admin.partial.footerjs')
@include('admin.partial.footer')
