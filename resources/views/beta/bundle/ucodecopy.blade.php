UCode: {{ $ucodedetails->ucode }}
@if(!empty($medias))
@foreach($medias as $mrow)
{{ $mrow->title }}<br />
@endforeach
@endif