{{--
  -- Show user stats
  -- PARAMS:
  -- $user => array of user stats
  --}}
<div class="dashboard-stat-section">
  <h2>Users</h2>
  <div class="row tile_count">
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-users"></i> Total Users</span>
        <div class="count">{{ $user['total_all'] }}</div>
      </div>
    </div>
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-lock"></i> Pending Verification Users</span>
        <div class="count">
          @if ($user['total_unverified'] > 0)
            <a href="{{ route('admin::user::pendings') }}" class="status need-verification">{{ $user['total_unverified'] }}</a>
          @else
            {{ $user['total_unverified'] }}
          @endif
        </div>
      </div>
    </div>
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-user"></i> Regular Users</span>
        <div class="count">{{ $user['total_regular_user'] }}</div>
      </div>
    </div>
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-dollar"></i> Paid Users</span>
        <div class="count">{{ $user['total_paid_user'] }}</div>
      </div>
    </div>
    <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <div class="left"></div>
      <div class="right">
        <span class="count_top"><i class="fa fa-key"></i> Total Administrator</span>
        <div class="count">{{ $user['total_admin'] }}</div>
      </div>
    </div>
  </div>
</div>
