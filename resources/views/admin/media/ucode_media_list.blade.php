{{--
  -- display list of media of a ucode (used on ucode page)
  -- PARAMS:
  -- $ucode => a ucode code
  -- $media => array of media of a ucode
  --}}

<table class="table horizontal-column table-striped responsive-utilities jambo_table bulk_action">
    <thead>
      <tr class="headings">
          <th class="column-title" colspan="3" width="15%">{{ $ucode }}</th>
      </tr>
    </thead>

    <tbody>
    @if(!empty($media))
        @foreach($media as $row)
        <tr>
            <td>
                <div class="column-box">
                  <div><img width="50px" height="50px" src="{{ $row->thumbnail_url }}" /></div>
                </div>
            </td>
            <td>
                <a href="{{ url('/media/'.$row->id_media) }}">
                    <span>{{ $row->title }}</span>
                </a>
            </td>
            <td width="120">
                <ul class="listing clearfix">
                    <li data-toggle="tooltip" data-trigger="hover" data-placement="bottom" title="Likes">
                        <i class="fa fa-thumbs-up"></i> {{ $row->likePercent }}%
                    </li>
                    <li data-toggle="tooltip" data-trigger="hover" data-placement="bottom" title="Times Collected">
                        <i class="fa fa-list-ul"></i> {{ $row->count_cd }}
                    </li>
                </ul>
            </td>
        </tr>
        @endforeach
    @else
        <tr><td colspan="3">Sorry, Media not available</td></tr>
    @endif
    </tbody>
</table>
