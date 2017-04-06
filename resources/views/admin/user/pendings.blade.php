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
        <h2>Pending Verification Users</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <div class="title_left">
          <div class="col-md-5 col-sm-5 col-xs-12 form-group">
            @if(Input::get('s'))
              <h4>Filter by : <span style="background:#DBF500;padding:2px 3px">{{Input::get('s')}}</span></h4>
            @endif
          </div>
        </div>
        <div class="title_right">
          <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
            <form name="f1" method="get" action="{{Url::to('admin/user/pendings')}}">
              <div class="input-group">
                <input type="text" name="s" class="form-control" placeholder="Search for Name/email ...">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="submit">Go!</button>
                </span>
              </div>
            </form>
          </div>
        </div>

        <table class="table table-striped responsive-utilities jambo_table bulk_action">
        <thead>
          <tr class="headings">
            <th class="column-title" width="20%">Username</th>
            <th class="column-title" width="35%">Name</th>
            <th class="column-title" width="15%">Email</th>
            <th class="column-title no-link last" width="10%"><span class="nobr">Action</span></th>
          </tr>
        </thead>

        <tbody>
          @foreach ($users as $user)
            <tr class="pointer">
              <td>{{ $user->screen_name }}</td>
              <td>{{ $user->fullname() }}</td>
              <td>{{ $user->email }}</td>
              <td class=" last">
                <a class="btn btn-default" href="{{ url('admin/user/'.$user->id) }}">Review</a>
              </td>
            </tr>
          @endforeach
        </tbody>
        </table>
        <div align="center">
          <?php echo $users->appends(['s' => Input::get('s')])->render(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div id='dialog-form'></div>


@include('admin.partial.footerjs')
@include('admin.partial.footer')
