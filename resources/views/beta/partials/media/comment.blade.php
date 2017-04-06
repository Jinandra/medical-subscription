{{--
  -- PARAMS:
  -- $media => a media object
  -- $auth  => true if user authenticated, default to false
  --}}

<div class="comments">
  <div class="row">
    <div class="col-md-6">
      <div class="user-details">
        <div class="user-wrap">
          <div class="user">
            <i class="fa fa-user"></i>
          </div>
        </div>
        <div class="user-description">
          <div class="des">
            <div class="txt-teal dib">{{ $media->screen_name }}</div>
            <p>{{ $media->created_at }}</p>
          </div>
        </div>
      </div>
    </div>
    @if (isset($auth) && $auth)
      <div class="col-md-6">
        <ul class="users-panel pull-right txt-default">
          <li>
          	<span class="mr10" data-toggle="popover" data-content="Likes">
                <i class="fa fa-thumbs-up mr5"></i>
                <span class="likePercent">{{$media->likePercent}}</span> %
            </span>
          </li>
          <!--<li><a href="#"><i class="fa fa-check-square mr5"></i> 99%</a></li>-->
          <li>
          	<span class="mr10" data-toggle="popover" data-content="Times Collected">
                <i class="fa fa-list-ul mr5"></i>
                @if($media->count_cd==0)
                  {{0}}
                @elseif($media->count_cd>0)
                  {{$media->count_cd}}
                @else
                  {{0}}
                @endif
            </span>
          </li>
          <li>
          	<span class="mr10" data-toggle="popover" data-content="Date Added">
                <i class="fa fa-calendar mr5"></i>
                @if(isset($media->created_at))
                  {{$media->created_at}}
                @endif
            </span>
          </li>
          <li>
            {{$media->view_count}} views
          </li>
        </ul>
      </div>
    @endif
  </div>
  <div class="commented">
    <div class="limit-text">
      {{ description($media->description) }}
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('.commented .limit-text').limitText({
      limitChar: 800
    });
  });
</script>
