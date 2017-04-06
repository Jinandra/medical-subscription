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
      @endif
    </div>
  </div>
  <div class="clearfix"></div>

  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>All User</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        @include('admin.searchBar', [
          'action' => Url::to('admin/user'),
          'placeholder' => 'Search for name/email...'
        ]);
        <table class="table table-striped responsive-utilities jambo_table bulk_action">
        <thead>
          <tr class="headings">
            <th class="column-title" width="25%">Name</th>
            <th class="column-title" width="20%">Email</th>
            <th class="column-title" width="5%">Role</th>
            <th class="column-title" width="10%">Status</th>
            <th class="column-title" width="15%">Last Modified</th>
            <th class="column-title no-link last"><span class="nobr">Action</span></th>                           
          </tr>
        </thead>

        <tbody>
          @foreach ($users as $user)
            @if ( Auth::user()->isMasterAdministrator() || !$user->isMasterAdministrator())
              <tr class="pointer">
                <td class=" ">{{$user->fullnameWithScreenName()}}</td>
                <td class=" "><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>
                <td class=" ">{{ $user->getDisplayRoleName() }}</td>
                <td class="status {{ $user->isStatusActive() ? 'activated' : $user->user_status }}">
                  @if ($user->isStatusPendingActivation())
                    pending activation
                  @elseif ($user->isStatusPendingVerification())
                    pending verification
                  @else
                    {{ $user->user_status }}
                  @endif
                </td>
                <td class=" ">
                  <?php
                    $date = new Date($user->updated_at);
                    echo "<div title='".$user->updated_at."'>".$date->ago()."</div>";
                  ?>
                </td>
                <td class="last">
                  <div class="btn-group">
                    <button type="button" class="btn btn-info">Action</button>
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                      <span class="caret"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <?php
                        $isUserAdmin = $user->isAdministrator() || $user->isMasterAdministrator();
                        $isCreatedBySameAdmin = $user->created_by === Auth::user()->id;
                      ?>

                      @if (Auth::user()->isMasterAdministrator() || $isCreatedBySameAdmin)
                        <li>
                          <a
                            href="{{ route('admin::user::delete', ['id' => $user->id]) }}"
                            data-method="DELETE"
                            data-alert="confirm"
                            data-alert-text="Are you sure want to delete '{{ $user->screen_name }}'?"
                          >
                            <i class="fa fa-remove"></i> Delete
                          </a>
                        </li>
                      @endif

                      @if (Auth::user()->isMasterAdministrator() || !$isUserAdmin)
                        <li><a href="{{Url::to('admin/user/change-password?id='.$user->id)}}"><i class='fa fa-exchange'></i> Change Password</a></li>
                      @endif

                      <li><a href="{{Url::to('admin/user/'.$user->id)}}"><i class="fa fa-user"></i> View</a>

                      @if ($user->isStatusActive())
                        @if (Auth::user()->isMasterAdministrator())
                          <li>
                            <a
                              href="{{ route('admin::user::patch', ['id' => $user->id]) }}"
                              data-alert="confirm"
                              data-method="PATCH"
                              data-inputs="action,role"
                              data-input-action="toggle-role"
                              data-input-role="{{ $role_administrator->id }}"
                              data-alert-text="Are you sure you want to {{ $user->isAdministrator() ? 'remove from admin' : 'set as admin' }} for '{{ $user->screen_name }}'?"
                            >
                              @if ($user->isAdministrator())
                                <i class='fa fa-level-down'></i> Remove from Admin
                              @else
                                <i class='fa fa-level-up'></i> Set as Admin
                              @endif
                            </a>
                          </li>
                        @endif

                        @if ( !$isUserAdmin )
                          <li>
                            <a
                              href="{{ route('admin::user::patch', ['id' => $user->id]) }}"
                              data-alert="confirm"
                              data-method="PATCH"
                              data-inputs="action,role"
                              data-input-action="toggle-role"
                              data-input-role="{{ $role_paid_user->id }}"
                              data-alert-text="Are you sure you want to set as {{ $user->isPaidUser() ? 'regular user' : 'paid user' }} for '{{ $user->screen_name }}'?"
                            >
                              @if ($user->isPaidUser())
                                <i class="fa fa-user-o"></i> Set as Regular User
                              @else
                                <i class="fa fa-user-plus"></i> Set as Paid User
                              @endif
                            </a>
                          </li>
                        @endif

                        @if (Auth::user()->isMasterAdministrator() || $isCreatedBySameAdmin)
                          <li>
                            <a
                              href="{{ route('admin::user::patch', ['id' => $user->id]) }}"
                              data-alert="confirm"
                              data-method="PATCH"
                              data-inputs="action"
                              data-input-action="toggle-block"
                              data-alert-text="Are you sure you want to block '{{ $user->screen_name }}'?"
                            >
                              <i class='fa fa-thumbs-down'></i> Block
                            </a>
                          </li>
                        @endif
                      @elseif ($user->isStatusBlocked())
                        @if (Auth::user()->isMasterAdministrator() || $isCreatedBySameAdmin)
                          <li>
                            <a
                              href="{{ route('admin::user::patch', ['id' => $user->id]) }}"
                              data-alert="confirm"
                              data-method="PATCH"
                              data-inputs="action"
                              data-input-action="toggle-block"
                              data-alert-text="Are you sure you want to unblock '{{ $user->screen_name }}'?"
                            >
                              <i class='fa fa-thumbs-up'></i> Un-Block
                            </a>
                          </li>
                        @endif
                      @endif
                    </ul>
                  </div>
                </td>
              </tr>
            @endif
          @endforeach
        </tbody>
        </table>
        <div align="center">
          <?php 
            //$users->appends(array('s' => Input::get('s')))->links();
            //echo $users->links(); 
            echo $users->appends(['s' => Input::get('s')])->render(); 
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div id='dialog-form'></div>

@include('admin.partial.footerjs')

<style>
  .status.activated { font-weight: bold; }
  .status.pending { font-style: italic; }
  .status.declined { color: red; }
  .status.need_verification { color: green; font-weight: bold; }
</style>
<script>
  function view_user(url) {
    var options = {
      title: 'View User',
      size: eModal.size.lg,
      url: url,
      buttons: [
        {text: 'CLOSE', style: 'info',   close: true },
      ],
    };
    return eModal.iframe(options);
  }

  $(document).ready(function () {
  });
</script>

@include('admin.partial.footer')
