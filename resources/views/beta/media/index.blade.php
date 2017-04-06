@extends('beta.userLayout')

@section('content')
<div class="col-sm-9 col-md-10 col-xs-9 right-column">
  <div class="headings nomarge">
    <h1>My Contributions</h1>
    <p></p>
  </div>

  <div class="action-row top-section clearfix">
    <div class="row">
      <div class="col-md-6">
        <a href="{{ url('/contribute/addForm') }}" class="btn btn-green">
          <i class="fa fa-plus fz12 mr10"></i> Add Media
        </a>
        <button type="button" class="btn btn-default" id="btnSaveTo">
          <i class="fa fa-plus mr10"></i> Save to
        </button>
        <button type="button" class="btn" id="btnCreateFolder"><i class="fa fa-folder fz12 mr10"></i> Create New Folder</button>
      </div>
      <div class="col-md-6" data-toggle="tooltip" data-placement="bottom" title="{{ round(Auth::user()->getMediaUsedSize() / App\Models\User::ONE_MB, 2) }}MB/{{ round(App\Models\User::MEDIA_TOTAL_SIZE / App\Models\User::ONE_MB, 1) }}MB Used">
        <label class="fwb pull-left form-control-static ml10 mr10">Your upload space</label>
        <div class="panel p5 mb0">
          <div class="progress mb0">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"
                 style="width: {{ Auth::user()->getMediaUsedSizePersentage() }}%">
              <span class="sr-only">{{ Auth::user()->getMediaUsedSizePersentage() }}%</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <ul class="mt20 nav nav-tabs">
      @foreach(App\Models\Media::getAllShowTypes() as $showType => $text)
        <li role="presentation" class="{{ ($type == $showType)?'active':'' }}">
          <a href="{{ url('contribute/').'/'.$showType }}">{{ $text }}</a>
        </li>
      @endforeach
    </ul>
  <!--</div>-->
  <div class="mt20 visible-xs"></div>

  <div class="content">
    <div class="container spacer">
      <div class="row">
        <div class=" col-xs-12 clearfix">
          <div class="table-responsive contribute">
            <table class="table">
            <thead>
              <tr>
                <th width="10"></th>
                <th>Title</th>
                <th width="70">Date</th>
                <th width="50">Likes</th>
                <th width="70">Collection</th>
                <th width="40">View</th>
                <th width="30" class="tac">Info</th>
                <th width="130" class="tac">Add to bundle</th>
                <th width="70" class="tac">Save to</th>
                <th width="50" class="tac">Edit</th>
                <th width="50" class="tac">Delete</th>
              </tr>
            </thead>
            <tbody>
            <?php $i = 0; ?>
            @foreach($media as $row)
              <?php $i++; ?>
              <tr class="media-row">
                <td>
                  <label class="checkbox-default">
                    <input data-title="{{ $row->title }}" type="checkbox" name="media[]" value="{{ $row->id }}" class="bulk-send">
                    <span class="ico-checkbox"></span>
                  </label>
                </td>
                <td>
                  <div class = "col-title">
                    <a href = "{{ url('/media/'.$row->id) }}" class="media-list-title">{{ $row->title }}</a>
                  </div>
                  <span class="hidden media-list-descr">{{ $row->description }}</span>
                </td>
                <td>
                  <?php
                    $date = date_create($row->created_at);
                    echo date_format($date, "m/d/Y");
                  ?>
                </td>
                <td>{{ $row->likePercent  }}%</td>
                <td class="tac">{{ $row->count_cd }}</td>
                <td>{{ $row->view_count }}</td>
                <td class="tac">
                  <img src="{{ config('app.assets_path').'/images/ico-info.png' }}" alt=""  data-container="body" data-toggle="popover" class="info" data-content="{{ $row->description }}">
                  <!--<i class="fa fa-info-circle info" data-trigger="hover" data-toggle="popover" data-placement="bottom" data-content="{{ $row->description }}"></i>-->
                </td>
                <td class="col-inline tac">
                  @if($row->id_media_bundle_cart)
                    <a class="add-to-bundle bundle-added-sm" href="{{ url('bundle/'.$row->id_media.'/add') }}" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Remove from Bundle"><img src="{{ config('app.assets_path') }}/images/bundle-btn-white-sm.png" /></a>
                  @else
                    <a class="add-to-bundle" href="{{ url('bundle/'.$row->id_media.'/add') }}" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Add to Bundle"><img src="{{ config('app.assets_path') }}/images/bundle-btn-sm.png" /></a>
                  @endif
                </td>
                <td class="col-inline tac">
                  <a href="#" data-toggle="popover" data-placement="bottom" data-content="Send to folder" data-trigger="hover" class="ico-send"><i class="fa fa-plus"></i></a>
                </td>
                <td class="col-inline tac">
                  <a href="{{ url('/contribute/'.$row->id.'/edit') }}  " ><i class="fa fa-pencil fa-sm" data-toggle="popover" data-placement="bottom" data-content="Edit" data-trigger="hover"></i></a>
                </td>
                <td class="col-inline tac">
                  <!--<a data-toggle="modal" data-dismiss="modal" data-target="#modal-delete" data-id="{{$row->id}}" data-title="{{$row->title}}" ><i class="fa fa-close fa-sm" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Delete"></i></a>-->
                  <a class="tdn" data-toggle="tooltip" data-placement="bottom" title="Delete">
                    <!--<img src="assets/images/ico-x.png" alt="" data-toggle="modal" data-target="#modal-delete" data-dismiss="modal">-->
                    <img src="{{ url('/') }}/assets/images/ico-x.png" alt="" class='remove-modal'>
                  </a>
                </td>
              </tr>
            @endforeach
            </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="overlay"></div>
  </div>
</div>

@if (isset($_GET['title']))
  <!-- Modal confrmation -->
  <div id="modalNotification" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Success</h4>
        </div>
        <div class="modal-body">
          <p>Media {{$_GET['title']}} Successfully added</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endif

@include('beta.partials.modal')

@stop


@section('additionalScript')
<script type="text/javascript">

  $(document).ready(function () {
    $('.add-to-bundle').bundleButtonSM();
    
    var COLLECTIONS_MAPPING = {};
    @if (isset($collections) && count($collections) > 0)
      @include('beta.collection.mapping_to_js', ['collections' => $collections, 'showMedia' => false]);
    @endif

    var IN_COLLECTIONS = {};
    @if (isset($media) && count($media) > 0)
      @foreach($media as $row)
        IN_COLLECTIONS['medium-'+{{$row->id}}] = [
          @foreach (App\Models\Media::find($row->id)->collections()->where('collections.user_id', Auth::user()->id)->get() as $collection)
            {{ $collection->id }},
          @endforeach
        ];
      @endforeach
    @endif

    function onCreatedFolder (newFolder) {
      COLLECTIONS_MAPPING['collection-'+newFolder.id] = newFolder;
    }

    // Handle create new folder
    $('#btnCreateFolder').on('click', function (e) {
      ENFOLINK.modal.showCreateFolder(onCreatedFolder);
    });

    // Handle bulk save to
    $('#btnSaveTo').on('click', function (e) {
      var media = $('input[type=checkbox].bulk-send:checked').map(function (_i, checkbox) {
        return { id: $(checkbox).val(), title: $(checkbox).data('title') };
      }).toArray();
      if (media.length === 0) {
        ENFOLINK.modal.showEmptySelection();
      } else {
        var data = {
          onCreatedFolder: onCreatedFolder,
          media: media,
          targets: sortByStringField(objectToArray(COLLECTIONS_MAPPING), 'name'),
        };
        ENFOLINK.modal.showSaveTo(data);
      }
    });

    // Handle save to on single media
    $('.ico-send').on('click', function (e) {
      e.preventDefault();
      var $input  = $(e.currentTarget).parents('tr').find('input[name*=media]');
      var id      = $input.val();
      var targets = Object.keys(COLLECTIONS_MAPPING).map(function (collectionId) {
        return $.extend(true, {}, COLLECTIONS_MAPPING[collectionId], {
          selected: IN_COLLECTIONS['medium-'+id].indexOf(COLLECTIONS_MAPPING[collectionId].id) !== -1
        });
      });
      var data = {
        onCreatedFolder: onCreatedFolder,
        media: [{ id: id, title: $input.data('title') }],
        targets: sortByStringField(targets, 'name')
      };
      ENFOLINK.modal.showSaveTo(data);
    });
    
    var removableRow;
    $('body').on('click', '.remove-modal', function() {
      removableRow = $(this).parents('.media-row');
      var mediaId = removableRow.find('.bulk-send').val();
      var mediaTitle = removableRow.find('.media-list-title').html();
      var mediaDescription = removableRow.find('.media-list-descr').html();
      $('.removable-id').html(mediaId);
      $('.removable-title').html(mediaTitle);
      $('.removable-descr').html(mediaDescription);
      $('#modal-delete').modal('show');
    });
    $('body').on('click', '.remove-media', function() {
      var mediaId = $('.removable-id').html();
      $.ajax({
        type: 'DELETE',
        url: "{{url('contribute/')}}" + '/' + mediaId,
        data: {
          _token: "{{ csrf_token() }}"
        },
        success: function (data) {
          if (data.success) {
            removableRow.remove();
            removableRow = '';
            $('#modal-delete').modal('hide');
            $('.removable-descr').html('Lorem ipsum dolor sit amet, consecte...');
            $('.removable-title').html('Selected media');
            $('.removable-id').html('');
          } else {
            alert('Something went wrong.');
          }
        }
      })
    });
  });
</script>
<style>
  .remove-modal {
    cursor: pointer;
  }
</style>
@stop

