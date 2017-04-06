@extends(isset($auth) && $auth ? 'beta.userLayout' : 'beta.layout')

@section('title')
  Search Result Page for '{{ $s }}' | Enfolink
@stop

@section('content')
<?php extract($data)?>
@if (isset($auth) && $auth)
<div class="col-sm-9 col-md-10 col-xs-9 right-column">
@else
<div class=" col-xs-12 col-sm-10 col-sm-offset-1 right-column unregisterd">
@endif
  <div class="content">
    <div class="headings nomarge">
      <h1>Search Result : {{ Input::get('s') }}</h1>
      <p></p>
    </div>
    <div class="container container-listing-vertical">
      <!--SECTION - FOLDERS-->
      @if(isset($folders) && count($folders) > 0)
        <div class="row">
          @foreach($folders as $folders_val)
            <?php $folder_media = App\Models\CollectionDetail::getFirstMediaFolder($folders_val->id); ?>
            <div class="col-sm-3 col-md-3 col-xs-12 column-box">
              @if (isset($folder_media))
                @include('beta.partials.media.startThumbnail', ['media' => $folder_media])
              @else
                <div class="video-wrap">
              @endif
                <ul class="view-listing">
                  <li>
                    <div class="tooltip tooltip-toggle">
                      <i class="fa fa-info-circle"></i>
                      <div class="tooltiptext tooltip-bottom">
                      <?php /*?><h3>{{ $folders_val->name }}</h3><?php */?>
                        <div class="limit-text">
                          {{ description($folders_val->description) }}
                        </div>
                      </div>
                    </div>
                  </li>
                  @if (isset($auth) && $auth)
                    <li>
                      <a href="{{ url('collection/bundle/'.$folders_val->id) }}" class='bundleFolder'>
                        <div id="bundle-{{ $folders_val->id }}" class="bundle"><img src="{{ config('app.assets_path') }}/images/bundle.png" alt=""></div>
                      </a>
                    </li>
                    <?php
                      $auth_user_id = "";
                      $cat_addf = 0;
                      if(isset($auth) && $auth) {
                        $auth_user_id = Auth::user()->id;
                        $cat_addf = App\Models\Collection::isFolderAvailable($folders_val->name);
                      }
                    ?>
                    @if($folders_val->user_id==$auth_user_id)
                      <li>
                        <?php /*?><a class="set-folder active"><i class="fa fa-folder"></i></a><?php */?>
                        <div class="tooltip" style="background-color:#17baa3; color:#fff">
                          <i class="fa fa-folder"></i>
                          <div class="tooltiptext tooltip-bottom">
                            <div class="limit-text">
                              You can't copy your own collection
                            </div>
                          </div>
                        </div>
                      </li>
                    @else
                      @if($cat_addf > 0 || $folders_val->user_id==$auth_user_id)
                        <li>
                          <a href="{{ url('folder/add/'.$folders_val->id) }}" class="set-folder active"><i class="fa fa-folder"></i></a>
                        </li>
                      @else
                        <li><a href="{{ url('folder/add/'.$folders_val->id) }}" class="set-folder"><i class="fa fa-folder"></i></a></li>
                      @endif
                    @endif
                  @endif
                </ul>
                <a href="{{ url('folder/'.$folders_val->id) }}" class="video-wrap-link"></a>
              @include('beta.partials.media.endThumbnail')
              <p>
                <a href="{{ url('folder/'.$folders_val->id) }}" class="media-title">
                <!--<strong>{{ $folders_val->name }}: </strong><span>{{ $folders_val->description }}</span>-->
                  {{ $folders_val->name }}
                </a>
              </p>
              <?php $user_arr = App\Models\User::getSingleUser($folders_val->user_id); ?>
              <div class="txt-teal dib">{{ $user_arr->screen_name }}</div> - Folder 
              <ul class="listing clearfix">
                <li class="calendar tooltip pull-right">{{ App\Util::ago($folders_val->created_at, false)}}</li>
                <?php /*?><li class="calendar tooltip">{{ date("m/d/Y", strtotime($folders_val->created_at)) }}</li><?php */?>
              </ul>
            </div>
          @endforeach
        </div>
      @endif


      <!--SECTION - MEDIAS-->
      @if(isset($media) && count($media) > 0)
        <div class="row">
          @foreach($media as $media_val)
            <div class="col-sm-3 col-md-3 col-xs-12 column-box">
              @include('beta.partials.media.startThumbnail', ['media' => $media_val])
                <ul class="view-listing">
                  <li>
                    <div class="tooltip tooltip-toggle">
                      <i class="fa fa-info-circle"></i>
                      <div class="tooltiptext tooltip-bottom">
                        <?php /*?><h3>{{ $media_val->title}}</h3><?php */?>
                        <div class="limit-text">
                          {{ description($media_val->description) }}
                        </div>
                      </div>
                    </div>
                  </li>
                  @if (isset($auth) && $auth)
                    <li>
                      <?php $cnt_bundle = App\Models\BundleCart::isInBundle($media_val->id)?>
                      <a href="{{ url('bundle/'.$media_val->id.'/add') }}" class="bundle @if($cnt_bundle > 0) active @endif">
                        <img src="{{ config('app.assets_path') }}/images/bundle.png" alt=""/>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('user/'.$media_val->id.'/fav') }}" class="set-bookmark set-bookmark-listing @if($media_val->fav) active @endif"><i class="fa fa-bookmark" ></i></a>
                    </li>
                  @endif
                </ul>
                <a href="{{ url('/media/'.$media_val->id) }}" class="video-wrap-link"></a>
              @include('beta.partials.media.endThumbnail')
              <p>
                <a href="{{ url('/media/'.$media_val->id) }}" class="media-title">
                  {{ $media_val->title}}
                </a>
              </p>
              <div class="txt-teal dib">{{ $media_val->screen_name}}</div> - {{ ucfirst($media_val->type) }}
              <ul class="listing clearfix">
                <li class="tooltip"><i class="fa fa-thumbs-up"></i>{{ $media_val->likePercent}}% <span class="tooltiptext tooltip-bottom tooltip-small">Likes</span></li>
                <li class="tooltip"><i class="fa fa-list-ul"></i>{{ App\Models\CollectionDetail::addedToList($media_val->id)}} <span class="tooltiptext tooltip-bottom tooltip-small">Times Collected</span></li>
                <li class="calendar tooltip pull-right">{{ App\Util::ago($media_val->created_at, false)}}</li>
                <?php /*?><li class="calendar tooltip">{{ date("m/d/Y", strtotime($media_val->created_at)) }}</li><?php */?>
              </ul>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</div>
<script>
  $(document).ready(function () {
    $('.limit-text').limitText();
    $('.set-bookmark').bookmarkButton();
    $('.bundle').bundleButton();

    $('.bundleFolder').on('click', function (e) {
      e.preventDefault();
      var url = $(this).attr('href');
      $.ajax({
        url: url,
        success: function (data) {
          /* $('#alertHeading').html('Added to bundle successfully'); */
          $('#add-bundle-number').html(data.countBundleCart);
        }
      });
    });

    $('.set-folder').on('click', function (e) {
      e.preventDefault();
      var $button = $(this);
      var url = $button.attr('href');
      $.ajax({
        url: url,
        success: function (data) {
          if (data === 'copied') {
            /* $('#alertHeading').html('Folder added to collection'); */
            $button.addClass('active');
          } else if (data === 'removed') {
            /* $('#alertHeading').html('Folder removed to collection'); */
            $button.removeClass('active');
          }
        }
      });
    });
  });
</script>
@stop
