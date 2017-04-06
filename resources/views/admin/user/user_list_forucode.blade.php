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
        <h2>Ucode User</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        @include('admin.searchBar', [
          'action' => route('admin::user::ucodes'),
          'placeholder' => 'Search by username / firstname / fullname...'
        ]);
        <table class="table table-striped responsive-utilities jambo_table bulk_action">
        <thead>
          <tr class="headings">
            <th class="column-title" width="25%">Full Name</th>
            <th class="column-title" width="10%">User Name</th>
            <th class="column-title" width="15%">UCode Count</th>
            <th class="column-title" width="15%">Date Created</th>
          </tr>
        </thead>

        <tbody>
          @if(count($users) > 0)
            <?php $i = 0; ?>
            @foreach($users as $row)
            <?php
              $link = route('admin::user::userUcodes', ['id' => $row->id]);
              $i++;
            ?>
          <tr class="pointer">
            <td class=" "><a href="{{ $link }}">{{ fullname($row) }}</a></td>
            <td class=" "><a href="{{ $link }}">{{ $row->screen_name }}</a></td>
            <td class=" "><a href="{{ $link }}">{{ $row->ucodeCount }}</a></td>
            <td class="">{{ date('m/d/Y', strtotime($row->created_at)) }}</td>
          </tr>
          @endforeach
        @endif
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
