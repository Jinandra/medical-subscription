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
      </div>

      <div class="x_content">
        <table class="table table-striped">
          <tr class="pointer">
            <td class="labelInfo">Media</td>
            <td><a href="{{ url('media/'.$report->media->id) }}" target="_blank">{{ $report->media->title }}</a></td>
          </tr>
          <tr class="pointer">
            <td class="labelInfo">Link</td>
            <td>{{ url('media/'.$report->media->id) }}</td>
          </tr>
          <tr class="pointer">
            <td class="labelInfo">Submitted by User</td>
            <td><a href="{{url('admin/user/'.$report->media->user->id)}}" target="_blank">{{$report->media->user->screen_name}}</a></td>
          </tr>
          <tr class="pointer">
            <td class="labelInfo">Reported by User</td>
            <td>
              <a href="{{url('admin/user/'.$report->user->id)}}" target="_blank">{{ $report->user->screen_name }}</a>
            </td>
          </tr>
          <tr class="pointer">
            <td class="labelInfo">Reason</td>
            <td>{{ App\Models\MediaReport::getReasonTextByNumber($report->reason) }}</td>
          </tr>
          <tr class="pointer">
            <td class="labelInfo">Comment</td>
            <td>{{ $report->comment }}</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td class="labelInfo" style="font-weight: strong; font-style: normal;">
              Actions
            </td>
            <td>
              <a href="{{Url::to('admin/media/report/delete/'.$report->id.'?_token='.csrf_token())}}" class="confirm-ui" onclick="return confirm('Are you sure you want to remove this report?')">
                <button class="btn btn-warning">Delete Report</button>
              </a>
              <br />
              @if($report->media->private == App\Models\Media::STATUS_PUBLIC)
              <a href="{{Url::to('admin/media/make-private/'.$report->media->id)}}" onclick="return confirm('Are you sure you want to remove media from public view?')">
                <button class="btn btn-warning">Remove media from public view</button>
              </a>
              @elseif($report->media->private == App\Models\Media::STATUS_PRIVATE)
              <a href="{{Url::to('admin/media/make-public/'.$report->media->id)}}" onclick="return confirm('Are you sure you want to make media public?')">
                <button class="btn btn-info">Make media public</button>
              </a>
              @endif
              <br />
              <a href="{{Url::to('admin/media/delete/'.$report->media->id.'?_token='.csrf_token())}}" onclick="return confirm('Are you sure you want to delete the media?')">
                <button class="btn btn-danger">Delete Media</button>
              </a>
              <br />
              <a href="{{Url::to('admin/media/ban/'.$report->media->id.'?_token='.csrf_token().'&reason='.$report->reason)}}" onclick="return confirm('Are you sure you want to delete and ban the media?')">
                <button class="btn btn-danger">Delete Media & Ban Media</button>
              </a>
            </td>
          </tr>
        </table>
        <p>
          @if(Input::get('mode') == 0)
            <a href="{{ url('admin/media/reports') }}">Back to all reports</a>
          @elseif(Input::get('mode') == 1)
            <a href="{{ url('admin/media') }}">Back to all media</a>
          @endif
        </p>
      </div>
    </div>   
  </div>
</div>
<style>
  .labelInfo { width: 15%; text-align: right; font-style: italic; }
</style>

@include('admin.partial.footerjs')
@include('admin.partial.footer')
