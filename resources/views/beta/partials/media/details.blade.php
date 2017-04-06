{{--
  -- PARAMS:
  -- $media => a media to display
  -- $collections => available collections of current logged user, only if auth is true
  -- $auth  => true if user authenticated, default to false
  --}}

<div class="content tac">
  @if ( App\Models\Media::IsTypeVideo($media) )
    <div class="video-container">
      <div class="item">
        <?php
          $videoSrc = $media->web_link;
          if ( isset($media->youtubeEmbed) && !is_null($media->youtubeEmbed) ) {
            $videoSrc = $media->youtubeEmbed;
          } else if ( isset($media->driveEmbed) && !is_null($media->driveEmbed) ) {
            $videoSrc = $media->driveEmbed;
          }
        ?>
        <iframe src="{{ $videoSrc }}" width="100%" height="456" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>
  @elseif ( App\Models\Media::IsTypeImage($media) )
    <img src='{{ $media->resolved_web_link }}'>
  @elseif ( App\Models\Media::IsTypeDocument($media) )
    @if ( (App\Models\Media::IsUploaded($media) && App\Models\Media::IsFileMS($media)) )
      <a class="media-embed via-google" href="http://docs.google.com/gview?url={{ $media->resolved_web_link }}&embedded=true"></a>
    @else
      <a class="media-embed" href="{{ $media->resolved_web_link }}"></a>
    @endif
  @elseif ( App\Models\Media::IsTypeWebsite($media) )
    <a href="{{ $media->resolved_web_link }}" target="_blank">
      <img src='{{ $media->website_thumbnail_url }}' width="100%" height="456">
    </a>
  @endif
</div>

<div class="row">
  <div class="col-sm-12 col-xs-12">
    <div class="content">
      <div class="headings">
        @include('beta.partials.media.header', [
          'media' => $media,
          'auth' => isset($auth) ? $auth : false,
          'collections' => isset($collections) ? $collections : [],
        ])
      </div>
      @include('beta.partials.media.comment', [
        'media' => $media,
        'auth' => isset($auth) ? $auth : false
      ])
      <div>
        <?php /*@include('beta.partials.disqus.media', ['media' => $media]) */ ?>
      </div>
    </div>
  </div>
</div>
@include('beta.partials.media.modal_report', ['id' => $media->id])

<script type="text/javascript">
  $(document).ready(function() {
    $('a.media-embed').each(function() {
      var href = $(this).attr('href');
      if (!$(this).hasClass('via-google') && (/(docs|drive)\.google.com/.test(href))) {
        var matches = href.match(/.*.google.com\/(.*?)\/(.*?)\/(.*?)\//); // *.google.com/file/d/foobar/
        if (matches !== null) {
          href = matches[0] + 'preview';
        } else {
          var matches2 = href.match(/.*.google.com\/(.*?)\/(.*?)\/(.+)/); // *.google.com/file/d/foobar
            if (matches2 !== null) {
            href = matches2[0] + '/preview';
          }
        }
      }
      if(href.slice(-4) == '.pdf') {
        var mediaContent = '<object data="' + href + '" width="100%" height="456" type="application/pdf">'+
                                '<embed src="' + href + '"  type="application/pdf" />'+
                            '</object>';
      } else if(href.slice(-5) == '.docx' || href.slice(-4) == '.doc') {
        mediaContent = '<iframe src="http://docs.google.com/gview?url='+href+'&embedded=true" width="100%" height="456"></iframe>';
      } else {
        mediaContent = '<iframe src="'+href+'" width="100%" height="456"></iframe>';
      }
      $(this).replaceWith(mediaContent);
    });

    $('.likeCount, .dislikeCount').on('click', function (e) {
      e.preventDefault();
      $.get($(this).attr('href'), function (data) {
        $('.likeCount span').html(data.count_like);
        $('.dislikeCount span').html(data.count_dislike);
        $('.likePercent').html(data.likePercent);
      });
    });
    
    $('body').on('submit', '#report_form', function() {
      var datastring = $("#report_form").serialize();
      $.ajax({
        type: 'POST',
        url: "{{url('media/report')}}",
        data: {
          data: datastring,
          _token: "{{ csrf_token() }}"
        },
        success: function (data) {
          $('.error').html('');
          if (data.success) {
            $('#report_form')[0].reset();
            $('#modal-report').modal('hide');
          } else {
            $.each(data.errors, function (key, value) {
              $('.error-' + key).html(value);
            });
          }
        }
      })
      return false;
    });
  });
</script>

<style>
  a.likeCount:visited, a.likeCount:active, a.likeCount:hover, a.likeCount:link,
  a.dislike:visited, a.dislikeCount:active, a.dislikeCount:hover, a.dislikeCount:link,
  a.likeCount *, a.dislikeCount * { text-decoration: none; }
  .error { color: #a94442; }
  .open-report { cursor: pointer; }
  .title-heading {
    padding-bottom: 1px;
  }
</style>
