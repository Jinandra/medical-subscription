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
            <th class="column-title" width="25%">Media</th>
            <th class="column-title" width="20%">Submitted by User</th>
            <th class="column-title" width="5%">Reported by User</th>
            <th class="column-title" width="10%">Reason</th>
            <th class="column-title" width="15%">Sent</th>
            <th class="column-title no-link last"><span class="nobr">Actions</span></th>                           
          </tr>
        </thead>

        <tbody>
          @foreach ($reports as $report)
          <tr class="pointer">
            <td class="">
                <a href="{{url('media/'.$report->media->id)}}" target="_blank">{{$report->media->title}}</a>
            </td>
            <td class="">
                <a href="{{url('admin/user/'.$report->media->user->id)}}" target="_blank">{{$report->media->user->screen_name}}</a>
            </td>
            <td class="">
              <a href="{{url('admin/user/'.$report->user->id)}}" target="_blank">{{$report->user->screen_name}}</a>
            </td>
            <td class="">{{App\Models\MediaReport::getReasonTextByNumber($report->reason)}}</td>
            <td class="">
              <?php
                $date = new Date($report->created_at);
                echo "<div title='".$report->created_at."'>".$date->ago()."</div>";
              ?>
            </td>
            <td class="last">
              <div class="btn-group">
                <button type="button" class="btn btn-info">Actions</button>
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <span class="caret"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li>
                    <a href="{{Url::to('admin/media/report/delete/'.$report->id.'?_token='.csrf_token())}}" class="confirm-ui" onclick="return confirm('Are you sure you want to remove this report?')">
                      <i class='fa fa-remove'></i> Delete Report
                    </a>
                  </li>
                  <li>
                    <a href="{{Url::to('admin/media/report/show/'.$report->id)}}" class="confirm-ui">
                      <i class='fa fa-eye'></i> See Report
                    </a>
                  </li>
                  <li>
                    @if($report->media->private == App\Models\Media::STATUS_PUBLIC)
                    <a href="{{Url::to('admin/media/make-private/'.$report->media->id)}}" onclick="return confirm('Are you sure you want to remove media from public view?')">
                      <i class='fa fa-eye-slash'></i> Remove media from public view
                    </a>
                    @elseif($report->media->private == App\Models\Media::STATUS_PRIVATE)
                    <a href="{{Url::to('admin/media/make-public/'.$report->media->id)}}" onclick="return confirm('Are you sure you want to make media public?')">
                      <i class='fa fa-eye-slash'></i> Make media public
                    </a>
                    @endif
                  </li>
                  <li>
                    <a href="{{Url::to('admin/media/delete/'.$report->media->id.'?_token='.csrf_token())}}" onclick="return confirm('Are you sure you want to delete the media?')">
                      <i class="fa fa-remove"></i> Delete Media
                    </a>
                  </li>
                  @if($report->media->web_link)
                  <li>
                    <a href="{{Url::to('admin/media/ban/'.$report->media->id.'?_token='.csrf_token().'&reason='.$report->reason)}}" onclick="return confirm('Are you sure you want to delete and ban the media?')">
                      <i class='fa fa-thumbs-down'></i> Delete Media & Ban Media
                    </a>
                  </li>
                  @endif
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

@include('admin.partial.footerjs')
@include('admin.partial.footer')
