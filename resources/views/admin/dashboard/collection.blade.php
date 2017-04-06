{{--
  -- Show collection stats
  -- PARAMS:
  -- $collection => array of collection stats
  --}}
<div class="dashboard-stat-section">
  <h2>Collection</h2>
  <div class="row tile_count">
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-folder"></i> Total Collection</span>
        <div class="count">{{ $collection['total_all'] }}</div>
      </div>
    </div>
  </div>
</div>
