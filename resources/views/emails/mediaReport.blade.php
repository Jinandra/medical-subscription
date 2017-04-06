<p>
  Hi {{config('app.REVIEWER_NAME')}},<br /><br />
  There's a media requested for ban:<br />
</p>
<p>
  <a href='{{url('media/'.$report->media->id)}}'>{{$report->media->title}}</a>
</p>
<p>
  Reason : {{App\Models\MediaReport::getReasonTextByNumber($report->reason)}}.
  <br /><br />
  Comment : {{$report->comment}}
</p>
<br /><br />
-- Enfolink Team --
