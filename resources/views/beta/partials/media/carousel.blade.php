{{--
  -- PARAMS:
  -- $media => array of media object
  -- $fnOnClick  => name of js function callback when media is clicked (param id media)
  --}}
@if ($media != "" && count($media) > 0)
  <link href="{{ config('app.assets_path').'/css/owl.carousel.css' }}" rel="stylesheet" type="text/css"/>
  <div class="row column-box-overflow">
    <?php $count = 0; ?>
    @foreach($media as $row)
      <?php $count++; ?>
      <?php $cbFunc = "$fnOnClick({$row->id_media});"; ?>
      <div class="col-xs-12 column-box @if($count==1) active @endif" onclick="{{ $cbFunc }}">
        @include('beta.partials.media.startThumbnail', ['media' => $row ])
          <ul class="view-listing">
            <li>
              <a href="#" class="tooltip tooltip-toggle">
                <i class="fa fa-info-circle"></i>
                <div class="tooltiptext tooltip-bottom @if($count%5 == 0) pos-right @endif">
                  <div class="limit-text">{{ description($row->description) }}</div>
                </div>
              </a>
            </li>
          </ul>
        @include('beta.partials.media.endThumbnail')
        <p><a href="{{ url('/media/'.$row->id_media) }}">{{ $row->title }}</a></p>
      </div>
    @endforeach
  </div>
  <script src="{{ config('app.assets_path') }}/js/owl.carousel.min.js"></script>
  <script>
    $(document).ready(function() {
      // Compatibility with older jquery (current owl version is pretty old)
      $.fn.andSelf = function() {
        return this.addBack.apply(this, arguments);
      };

      $('.limit-text').limitText();
      var sync_nav = $(".column-box-overflow");
      sync_nav.owlCarousel({
        navigation : true,
        navText: [
          "<i class='fa fa-angle-left'></i>",
          "<i class='fa fa-angle-right'></i>"
        ],
        responsive: {
          0: {
            items: 1,
            nav: true
          },
          600: {
            items: 5,
            nav: false
          },
          1000: {
            items: 5,
            nav: true,
            loop: false
          }
        },
        loop:false,
        navRewind:false,
        lazyLoad:true,
        rewindSpeed: 0
      });
      sync_nav.on("click", ".owl-item", function (e) {
        e.preventDefault();
        var number = $(this).index();
        var nav = $(this).find('.column-box');
        $('.column-box').removeClass('active');
        nav.addClass('active');
      });
    });
  </script>
@endif
