<div id="folder_{{ $folder->id }}" data-original="{{ $folder->original_id }}" class="col-sm-4 col-md-3 col-xs-12 column-box col-reset folder-block">
    <?php
        if(property_exists($folder, 'first_media_id')) {
          $folder_media = App\Models\Media::find($folder->first_media_id);
        } else {
          $folder_media = App\Models\CollectionDetail::getFirstMediaFolder($folder->id);
        }
    ?>
    @if ($folder_media)
    @include('beta.partials.media.startThumbnail', ['media' => $folder_media])
    @else
    <div class="video-wrap">
    @endif
        <ul class="view-listing">
            <li>
                <div class="tooltip">
                    <i class="fa fa-info-circle"></i>
                    <div class="tooltiptext tooltip-bottom @if(isset($rowBreak) && $rowBreak) pos-right @endif">
                        <div class="limit-text">
                            {{ description($folder->description) }}
                        </div>
                    </div>
                </div>
            </li>
            <li>
              <?php
              if(property_exists($folder, 'media_count') && property_exists($folder, 'bundle_count') && $folder->media_count == $folder->bundle_count && $folder->media_count > 0) {
                  $isInBundle = true;
              } elseif(property_exists($folder, 'media_count') && property_exists($folder, 'bundle_count') && $folder->media_count != $folder->bundle_count) {
                  $isInBundle = false;
              } else {
                  $isInBundle = App\Models\Collection::isInBundle($folder->id);
              } ?>
              <a
                href="{{ url('collection/bundle/'.$folder->id.($isInBundle?'/remove':'/add')) }}"
                class="bundle bundleFolder {{ $isInBundle ? 'active' : '' }}"
                data-toggle="popover"
                data-content="{{ $isInBundle ? 'Remove from bundle' : 'Add to bundle' }}"
              >
                <img src="{{config('app.assets_path')}}/images/bundle.png" alt="">
              </a>
            </li>
            <li>
              @if($folder->user_id == Auth::user()->id)
                <a data-toggle="popover" style="background-color:#bbb; color:#fff" data-content="Folder is already in your My Collection">
                    <i class="fa fa-folder"></i>
                </a>
              @else
                <?php
                    if($folder->original_id) {
                      $checkId = $folder->original_id;
                    } else {
                      $checkId = $folder->id;
                    }
                    $hasCollection = App\Models\Collection::isFolderAvailableForCopy($checkId) > 0; 
                ?>
                <a data-toggle="popover" data-id="{{ $folder->id }}" data-content="{{ $hasCollection ? 'Folder is already in your My Collection' : 'Save collection' }}" href="{{ url('folder/'.$folder->id) }}" class="set-folder {{ $hasCollection ? 'active' : '' }}">
                    <i class="fa fa-folder"></i>
                </a>
              @endif
              <input class="hiddenToken" type="hidden" name="_token" value="{{ csrf_token() }}">
            </li>
        </ul>
        <a href="{{ url('folder/'.$folder->id) }}" class="video-wrap-link"></a>
    </div>
    <p>
        <a href="{{ url('folder/'.$folder->id) }}" class="media-title">
            <span class="folder-name">{{ $folder->name }}</span>
        </a>
    </p>
    <div class="clearfix">
        <div class="txt-teal dib">{{ ($folder->user_screen_name)?$folder->user_screen_name:App\User::find($folder->user_id)->screen_name }}</div> - Folder
    </div>
</div>
