<div class="col-sm-4 col-md-3 col-xs-12 column-box col-reset">
    @include('beta.partials.media.startThumbnail', ['media' => $media])
    <ul class="view-listing">
        <li>
            <div class="tooltip">
                <i class="fa fa-info-circle"></i>
                <div class="tooltiptext tooltip-bottom @if(isset($rowBreak) && $rowBreak) pos-right @endif">
                    <div class="limit-text">
                        {{ description($media->description) }}
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="bundle">
                <?php // $isInBundle = App\Models\BundleCart::isInBundle($media->id) > 0; ?>
                <a href="{{ url('bundle/'.$media->id.'/add') }}" class="bundle bundleMedia {{ $media->bundle_id ? 'active' : '' }}" data-toggle="popover" data-content="{{ $media->bundle_id ? 'Remove from bundle' : 'Add to bundle' }}">
                    <img src="{{config('app.assets_path')}}/images/bundle.png" alt="Bundle">
                </a>
            </div>
        </li>
        <li>
            <a href="{{ url('user/'.$media->id.'/fav') }}" data-toggle="popover" data-content="{{ $media->fav ? 'Remove from bookmark' : 'Add to bookmark' }}" class="set-bookmark {{ $media->fav ? 'active' : '' }}">
                <i class="fa fa-bookmark"></i>
            </a>
        </li>
    </ul>
    <a href="{{ url('/media/'.$media->id) }}" class="video-wrap-link"></a>
    @include('beta.partials.media.endThumbnail')
    <p>
        <a href="{{ url('/media/'.$media->id) }}" class="media-title">
            <span>{{ $media->title }}</span>
        </a>
    </p>
    <div class="clearfix">
        <div class="txt-teal dib">{{ $media->user_screen_name}}</div> - {{ ucfirst($media->type) }}
    </div>
    <ul class="listing clearfix">
        <li class="tooltip"><i class="fa fa-thumbs-up"></i>{{ $media->likePercent?$media->likePercent:'0' }}% <span class="tooltiptext tooltip-bottom tooltip-small">Likes</span></li>
        <li class="tooltip"><i class="fa fa-list-ul"></i>{{ $media->collected?$media->collected:'0' }} <span class="tooltiptext tooltip-bottom tooltip-small">Times Collected</span></li>
        <li class="calendar tooltip pull-right">{{ App\Util::ago($media->created_at, false)}}</li>
    </ul>
</div>
