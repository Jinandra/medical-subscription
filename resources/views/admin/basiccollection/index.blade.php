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
        <h2>Basic Collections</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        @include('admin.searchBar', [
          'placeholder' => 'Search for title or description...'
        ])
        <form style="clear:both; margin:20px 0;">
          <div class="form-group">
            <label class="control-label" for="name">Add Media</label>
            <input id="autocomplete" class="form-control" placeholder="type 2 chars to search media, select the item to add..." />
          </div>
        </form>
        <table class="table table-striped responsive-utilities jambo_table bulk_action">
        <thead>
          <tr class="headings">
            <th class="column-title" width="25%">Title</th>
            <th class="column-title" width="10%">Type</th>
            <th class="column-title" width="">Description</th>
            <th class="column-title" width="15%">Last Modified</th>
            <th class="column-title" width="10%">Modified By</th>
            <th class="column-title no-link last" width="10%"><span class="nobr">Action</span></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($media as $record)
            <?php
              $medium = $record->media;
              if (is_null($medium)) {
                continue;
              }
            ?>
            <tr class="pointer" data-id="{{ $record->id }}" data-media-id="{{ $medium->id }}">
              <td width="25%"><a href="{{ url('media/'.$medium->id) }}" target="_blank">{{ $medium->title }}</a></td>
              <td width="10%">{{ formatMediaType($medium->type) }}</td>
              <td width=""><span style="font-size: smaller;">{{ limitString($medium->description) }}</span></td>
              <td width="15%" class="last_modified">{{ time_ago($record->updated_at) }}</td>
              <td width="10%" class="screen_name">{{ $record->user->screen_name }}</td>
              <td class="last" width="10%">
                <button class="btn btn-danger btn-delete"  data-alert-text="Do you want to remove '{{ $medium->title }}' from basic collection?"><i class="fa fa-remove"></i> Delete</button>
              </td>
            </tr>
          @endforeach
        </tbody>
        </table>
        <?php
        /*
        <div align="center">
          {!! $categories->appends(['s' => Input::get('s')])->render() !!}
        </div>
        */
        ?>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function () {
    $(document.body).on('click', 'tbody .btn-delete', function (e) {
      e.preventDefault();
      var $button = $(e.target);
      var $tr     = $button.parents('tr');
      if (confirm($($.parseHTML($button.data('alert-text'))).text())) {
        $.ajax({
          url: "{{ url('admin/basiccollection') }}/"+$tr.data('id'),
          type: 'DELETE',
          data: {
            _token: "{{ csrf_token() }}"
          },
          success: function () {
            $tr.remove();
          }
        });
      }
    });

    // return all media ids that listed on table
    function getIds (type) {
      var d = typeof type === 'undefined' ? 'id' : type;
      return $("tbody tr.ui-sortable-handle:visible").map(function (_i, tr) { return $(tr).data(d) }).toArray();
    }

    function initSortable () {
      $('tbody').sortable({
        tolerance: 'pointer',
        helper: function (e, ui) {
          ui.children().each(function() {
            $(this).width($(this).width());
          });
          return ui;
        },
        update: function (e, ui) {
          var sortOrder = getIds();
          $.ajax({
            type: 'PATCH',
            url:  "{{ url('admin/basiccollection') }}",
            data: {
              action: 'sort',
              media_ids: sortOrder,
              _token: "{{ csrf_token() }}"
            },
            success: function (data) {
              $("tbody tr .last_modified").html(data.last_modified);
              $("tbody tr .screen_name").html(data.screen_name);
            }
          });
        }
      }).disableSelection();
    }

    // Autocomplete
    $('#autocomplete').autocomplete({
      minLength: 2,
      source: function (request, response) {
        $.ajax({
          type: 'GET',
          url: "{{ route('media::search') }}",
          data: {
            q: request.term,
            except_ids: getIds('media-id'),
            _token: "{{ csrf_token() }}"
          },
          success: function (data) {
            response(data);
          }
        });
      },
      select: function (ul, item) {
        $.ajax({
          type: "POST",
          url: "{{ url('admin/basiccollection') }}",
          data: {
            media_id: item.item.id,
            _token: "{{ csrf_token() }}"
          },
          success: function (data) {
            var media = data.media;
            $('<tr class="pointer ui-sortable-handle" data-id="'+data.id+'" data-media-id="'+media.id+'">')
              .append('<td width="25%"><a href="{{ url("media") }}/'+media.id+'" target="_blank">'+media.title+'</a></td>')
              .append('<td width="10%">'+media.type_formatted+'</td>')
              .append('<td><span style="font-size: smaller;">'+media.description_formatted+'</span></td>')
              .append('<td width="15%" class="last_modified">'+data.updated_at_formatted+'</td>')
              .append('<td width="10%" class="screen_name">'+data.user.screen_name+'</td>')
              .append('<td class="last" width="10%"><button class="btn btn-danger btn-delete"  data-alert-text="Do you want to remove \''+media.title+'\' from basic collection?"><i class="fa fa-remove"></i> Delete</button>')
              .appendTo('tbody');
            initSortable();
          }
        });
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      var l = function (s) {
        return s.length >= 100 ? s.substring(0, 97)+'...' : s;
      };
      return $( "<li>" )
        .append( "<div><b>" + item.title + "</b><br/>" + l(item.description)  + "<br/></div>" )
        .appendTo( ul );
    };
    $('#autocomplete').on('keypress', function (e) {
      if (e.which === 13) {
        e.preventDefault();
        alert('Please select media from list');
      }
    });
    initSortable();
  });
</script>

@include('admin.partial.footerjs')
