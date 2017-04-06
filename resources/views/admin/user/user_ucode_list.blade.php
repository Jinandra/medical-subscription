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
        <h2>User {{ $user->fullnameWithScreenName() }} Ucodes</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        @include('admin.searchBar', [
          'action' => route('admin::user::userUcodes', ['id' => $user->id]),
          'placeholder' => 'Search by ucode...'
        ]);
        <table  class="table" cellpadding="0" border="0" cellspacing="0">
            <tr>
                <td width="60%">
                    <table class="table table-striped responsive-utilities jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title" width="15%">UCode</th>
                            <th class="column-title" width="15%">Date Created</th>
                            <th class="column-title" width="10%">Accessed</th>
                            <th class="column-title" width="10%">Count</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php $i = 0; ?>
                          @foreach($ucodes as $row)
                            <?php $i++; ?>
                          <tr class="pointer" bundle-target="#bundle{{$i}}"  id="idTr{{ $row->ucode }}" onclick="showBundle('{{ $row->ucode }}')">
                            <td class=" "><a href="{{ url('/ucode/'.$row->ucode.'') }}" onclick="event.cancelBubble = true;" target="_blank">{{ $row->ucode }}</a></td>
                            <td class="">{{ $row->created_at }}</td>
                            @if(isset($row->countUcodeHistory))
                                <td width="100">{{ $row->uCodeHistoryCreatedAt }}</td>
                            @else
                                <td>&nbsp;</td>
                            @endif
                            <td>{{ $row->countUcodeHistory }}</td>
                          </tr>
                          @endforeach
                        </tbody>
                    </table>
                    <div>
                      <a href="{{ url(route('admin::user::ucodes')) }}">Back</a>
                    </div>
                </td>
                <td width="40%" id="ucodeAjax">
                    <table class="table horizontal-column table-striped responsive-utilities jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                              <th class="column-title" colspan="3" width="15%">{{ $ucode }}</th>
                          </tr>
                        </thead>
                        
                        <tbody>
                        @if(!empty($medias))
                            @foreach($medias as $row)
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
                </td>
            </tr>
        </table>
        
        <div align="center">
          <?php 
            //$users->appends(array('s' => Input::get('s')))->links();
            //echo $users->links(); 
            echo $ucodes->appends(['s' => $s])->render(); 
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div id='dialog-form'></div>

@include('admin.partial.footerjs')

<style>
    .pointer{ cursor: pointer; }
  .status.activated { font-weight: bold; }
  .status.pending { font-style: italic; }
  .status.declined { color: red; }
  .status.need_verification { color: green; font-weight: bold; }
</style>
<script>
    function showBundle(ucode) {
        $(document).on('click', "#idTr" + ucode, function (e) {
            $('.table-bundle').removeClass('active');
            $("#idTr" + ucode).addClass('active');
            e.stopImmediatePropagation(); e.stopPropagation(); e.preventDefault();
            $.ajax({
            url: "{{ url('admin/user/ucode') }}" + "/" + ucode + "/",
                    success: function(response) {
                    $("#ucodeAjax").html(response);
                    }
            })
        });
    }
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
