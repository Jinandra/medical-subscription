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
      @elseif (count($errors) > 0)
        <div role="alert" class="alert alert-error">
          @foreach ($errors->all() as $error)
            {{ $error }}<br />
          @endforeach
        </div>
      @endif
    </div>
  </div>
  <div class="clearfix"></div>

  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{$page_title}}</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        @include('admin.searchBar', [
          'placeholder' => 'Search for title...'
        ]);
        <table class="table table-striped responsive-utilities jambo_table bulk_action">
        <thead>
          <tr class="headings">
            <th class="column-title" width="22%">Title</th>
            <th class="column-title" width="15%">Submitted by User</th>
            <th class="column-title" width="10%">Media Type</th>
            <th class="column-title" width="10%">Created At</th>
            <th class="column-title" width="10%">View Count</th>
            <th class="column-title" width="15%">Folder Count</th>
            <th class="column-title" width="5%">&nbsp;</th>
            <th class="column-title no-link last"><span class="nobr">Actions</span></th>                           
          </tr>
        </thead>

        <tbody>
        @if(count($medialist) > 0)
          @foreach ($medialist as $medialistval)
          
          <tr class="pointer">
            <td class="">
                <a href="{{url('media/'.$medialistval->id)}}" target="_blank">{{$medialistval->title}}</a>
            </td>
            <td class="">
                <a href="{{url('admin/user/'.$medialistval->user_id)}}" target="_blank">{{$medialistval->screen_name}}</a>
            </td>
            <td class="">
                {{ ucwords($medialistval->type) }}
            </td>
            <td class="">
              <?php
                $date = new Date($medialistval->created_at);
                echo "<div title='".$medialistval->created_at."'>".$date->ago()."</div>";
              ?>
            </td>
            <td class="">
              {{ $medialistval->view_count }}
            </td>
            <td class="">
              {{ $medialistval->count_cd }}
            </td>
            <td>
                <i class="fa fa-question-circle" data-toggle="popover" data-content="{{ $medialistval->description }}"></i>
            </td>
            <td class="last">
              <div class="btn-group">
                <button type="button" class="btn btn-info">Actions</button>
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <span class="caret"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  @if($medialistval->mr_id)
                  <li>
                    <a href="{{Url::to('admin/media/report/delete/'.$medialistval->mr_id.'?_token='.csrf_token().'&mode=1')}}" class="confirm-ui" onclick="return confirm('Are you sure you want to remove this report?')">
                      <i class='fa fa-remove'></i> Delete Report
                    </a>
                  </li>                  
                  <li>
                    <a href="{{Url::to('admin/media/report/show/'.$medialistval->mr_id.'?mode=1')}}" class="confirm-ui">
                      <i class='fa fa-eye'></i> See Report
                    </a>
                  </li>
                  @endif
                  <li>
                    <a
                      href="{{ route('admin::media::delete', ['id' => $medialistval->id]) }}"
                      data-method="delete"
                      data-alert="confirm"
                      data-alert-text="Are you sure you want to delete the media '{{ $medialistval->title }}'?"
                      <i class="fa fa-remove"></i> Delete Media
                    </a>
                  </li>
                  <li>
                    @if($medialistval->private == App\Models\Media::STATUS_PUBLIC)
                    <a href="{{Url::to('admin/media/make-private/'.$medialistval->id.'?mode=1')}}" onclick="return confirm('Are you sure you want to remove media from public view?')">
                      <i class='fa fa-eye-slash'></i> Remove media from public view
                    </a>
                    @elseif($medialistval->private == App\Models\Media::STATUS_PRIVATE)
                    <a href="{{Url::to('admin/media/make-public/'.$medialistval->id.'?mode=1')}}" onclick="return confirm('Are you sure you want to make media public?')">
                      <i class='fa fa-eye-slash'></i> Make media public
                    </a>
                    @endif
                  </li>
                  @if($medialistval->mr_id)
                  <li>
                    <a href="{{Url::to('admin/media/ban/'.$medialistval->id.'?_token='.csrf_token().'&reason='.$medialistval->reason.'&mode=1')}}" onclick="return confirm('Are you sure you want to delete and ban the media?')">
                      <i class='fa fa-thumbs-down'></i> Delete Media & Ban Media
                    </a>
                  </li>
                  @endif
                </ul>
              </div>
            </td>
          </tr>
          @endforeach
        @endif
        </tbody>
        </table>
        <div align="center">
          <?php 
            //$users->appends(array('s' => Input::get('s')))->links();
            //echo $users->links(); 
            echo $medialist->appends(['s' => Input::get('s')])->render(); 
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div id='dialog-form'></div>

@include('admin.partial.footerjs')
@include('admin.partial.footer')
