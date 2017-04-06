{{--
  -- Show ucode stats
  -- PARAMS:
  -- $ucode => array of ucode stats
  --}}
<div class="dashboard-stat-section">
  <h2>UCode</h2>
  <div class="row tile_count">
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-magnet"></i> Total UCode</span>
        <div class="count">{{ $ucode['total_all'] }}</div>
      </div>
    </div>
  </div>
</div>
