{{--
  -- display list of media of a ucode (used on ucode page)
  -- PARAMS:
  -- $ucode => a ucode code
  -- $media => array of media of a ucode
  --}}

<table class="table table-hover mb0">
<thead>
  <tr>
    <th colspan="3">
      <!--
      <div class="dib cp pull-right">
        <i class="fa fa-download" data-toggle="tooltip" data-trigger="hover" data-placement="bottom" title="Download UCode history on PDF"></i>
      </div>
      -->
      {{ $ucode }}
    </th>
  </tr>
</thead>
</table>
<div class="bundle-box-preview-overflow">
  <table class="table table-hover">
  <tbody>
    @if(!empty($media))
      @foreach($media as $row)
        <tr>
          <td>
            <div class="column-box">
              @include('beta.partials.media.startThumbnail', ['media' => $row])
              @include('beta.partials.media.endThumbnail')
            </div>
          </td>
          <td>
            <a href="{{ url('/media/'.$row->id_media) }}" target="_blank">
              <span>{{ limitString($row->title, 40) }}</span>
            </a>
          </td>
          <td width="150">
            <ul class="listing clearfix">
              @include('beta.partials.media.countLiked', ['media' => $row])
              @include('beta.partials.media.countCollected', ['media' => $row])
            </ul>
          </td>
        </tr>
      @endforeach
    @else
      <tr><td colspan="3">Sorry, Media not available</td></tr>
    @endif
  </tbody>
  </table>
</div>
