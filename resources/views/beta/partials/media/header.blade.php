{{--
  -- PARAMS:
  -- $media => a media object
  -- $collections => available collections of current logged user, only if auth is true
  -- $auth => true if user authenticated, default to false
  --}}
<h1 class="title-heading">{{ $media->title }}</h1>
<div class="mb20">
  <div class="txt-bold dib">{{ App\Models\Media::sourceText($media) }}:</div>
  <?php $fileUrl = App\Models\Media::fileUrl($media); ?>
  <a href="{{ $media->resolved_web_link }}" target="_blank">{{ $media->resolved_web_link }}</a>
</div>

@if (isset($auth) && $auth)
  <div class="clearfix">
    <ul class="users-panel  pull-left">
      <li><a href="#" class="btnSaveTo"><i class="fa fa-plus mr5"></i> Save to</a></li>
      <li><a id="btn-bundle" class="btn-bundle  @if(isset($media->id_media_bundle_cart)) active @endif" href="{{ url('bundle/'.$media->id.'/add') }}"> Add to Bundle</a></li>
      <li>
        <a id="btn-bookmark" href="{{ url('user/'.$media->id.'/fav') }}" class="set-bookmark btn-bookmark @if($media->fav) active @endif" ><i class="fa fa-bookmark mr5"></i> Bookmark</a>
      </li>
      <li>
        <div class="dropdown">
          <div class="more-action" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-ellipsis-v"></i>
          </div>
          <ul class="dropdown-menu pull-right" aria-labelledby="dLabel">
              <li><a class="open-report" data-toggle="modal" data-target="#modal-report" data-dismiss="modal">Report Media</a></li>
          </ul>
        </div>
      </li>
    </ul>
    <ul class="users-panel pull-right">
      <li>@include('beta.partials.media.like', ['media' => $media])</li>
      <li>@include('beta.partials.media.dislike', ['media' => $media])</li>
    </ul>
  </div>

  <!-- Modal Send to -->
  <div class="modal fade modal-small" id="modal-send-to" tabindex="-1" role="dialog" aria-labelledby="Send to">
    <div class="modal-dialog form-modal" role="document">
      <div class="modal-content">
        <div class="modal-body form-popup">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
          aria-hidden="true">&times;</span></button>
          <div class="row">
            <form method="POST" action="{{ url('media/bulk-send-folder') }}">
              <input type="hidden" name="bulkMedia" value="{{ $media->id }}" />
              {{ csrf_field() }}
              <div class="col-xs-12">
                <h3>Send to</h3>
                <div>
                  <div class="fwb cp mb10" toggle-target=".selected-media">
                    <div class="toggle-caret selected-media dib mr5"></div> View selected media list
                  </div>
                  <div class="hide selected-media">
                    <div class="ul-limit-7 mb10">
                      <ul>
                        <li class="p5">{{ $media->title }}</li>
                      </ul>
                    </div>
                  </div>
                </div>
                <hr class="hr-item mt0">
                <div>
                  <div class="fwb mb10">
                      To folder
                  </div>
                  <div class="mb10">
                    <div id="collections" style="overflow-y:auto; max-height:210px;">
                    @if ( isset($collections) && count($collections) > 0 )
                      @foreach($collections as $collection)
                        <div>
                          <label class="checkbox-default mr5">
                            <input
                              type="checkbox"
                              name="folders[]"
                              value="{{ $collection->id }}"
                              {{ App\Models\CollectionDetail::isCollectionAvailable($media->id, $collection->id) ? 'checked="checked"' : '' }}
                            />
                            <span class="ico-checkbox"></span>
                          </label> {{ $collection->name }}
                          <hr class="hr-item" />
                        </div>
                      @endforeach
                    @endif
                    </div>
                    <div>
                      <button type="button" class="el-btn el-btn-padding-md el-btn-grey full" data-toggle="modal" data-target="#modal-folder" data-dismiss="modal">
                        <i class="fa fa-folder mr10"></i>Create new folder
                      </button>
                    </div>
                  </div>
                  <div class="mb10 mt40 tac">
                    <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-grey mr5" value="Cancel" data-dismiss="modal" />
                    <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-green" value="Save" />
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    a.btn-bundle.active {
      background-image: url("{{ config('app.assets_path') }}/images/bundle-btn-white.png") !important;
      background-repeat: no-repeat !important;
      background-position: 10px center !important;
      background-color: #009688 !important;
      color:#fff !important;
    }

    a.btn-bookmark.active{
      background-repeat: no-repeat !important;
      background-position: 10px center !important;
      background-color: #009688 !important;
      color:#fff !important;
    }
  </style>
  @include('beta.partials.modal')
  <script type="text/javascript">
    $(document).ready(function() {

      $('#btn-bundle').bundleButton();
      $('#btn-bookmark').bookmarkButton();
      $('.send-to-folder').sendToFolderButton();

      var COLLECTIONS_MAPPING = {};
      @include('beta.collection.mapping_to_js', ['collections' => $collections, 'showMedia' => false]);

      var IN_COLLECTIONS = [
        @foreach (App\Models\Media::find($media->id)->collections()->where('collections.user_id', Auth::user()->id)->get() as $collection)
          {{ $collection->id }},
        @endforeach
      ];

      $('.btnSaveTo').on('click', function (e) {
        e.preventDefault();
        var data = {
          media: [{ id: {{ $media->id }}, title: "{{ $media->title }}" }],
          targets: sortByStringField(
            Object.keys(COLLECTIONS_MAPPING).map(function (collectionId) {
              return $.extend(true, {}, COLLECTIONS_MAPPING[collectionId], {
                selected: IN_COLLECTIONS.indexOf(COLLECTIONS_MAPPING[collectionId].id) !== -1
              });
            }), 'name'
          ),
          onCreatedFolder: function (newFolder) {
            COLLECTIONS_MAPPING[newFolder.id] = newFolder;
          },
          onSuccess: function (resp) {
            var toInteger = function (s) {
              return parseInt(s);
            };
            $.uniqueSort($.merge(IN_COLLECTIONS, resp.targets.map(toInteger)));
          }
        };
        ENFOLINK.modal.showSaveTo(data);
      });
    });
  </script>
@endif

