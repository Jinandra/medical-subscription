{{--
  -- Show media stats
  -- PARAMS:
  -- $media => array of media stats
  --}}
<div class="dashboard-stat-section">
  <h2>Media</h2>
  <div class="row tile_count">
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-file"></i> Total Media</span>
        <div class="count">{{ $media['total_all'] }}</div>
      </div>
    </div>
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-file-video-o"></i> Video</span>
        <div class="count">{{ $media['total_video'] }}</div>
      </div>
    </div>
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-file-picture-o"></i> Image</span>
        <div class="count">{{ $media['total_image'] }}</div>
      </div>
    </div>
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-file-text"></i> Document</span>
        <div class="count">{{ $media['total_document'] }}</div>
      </div>
    </div>
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-wikipedia-w"></i> Website</span>
        <div class="count">{{ $media['total_website'] }}</div>
      </div>
    </div>
  </div>
</div>
