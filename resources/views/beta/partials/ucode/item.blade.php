{{--
  -- PARAMS:
  -- $ucode => ucode code
  -- $media => array of media of current ucode
  -- $auth  => true if user authenticated, default to false
  --}}

<div class="content">
  <div class="headings nomarge">
    <h1>UCode: {{ $ucode }} ({{ count($media)}} Media)</h1>
    <p></p>
  </div>
  <div class="container overflow-x-hidden">
    @if ($media != "" && count($media) > 0)
      @include('beta.partials.media.carousel', [
        'media' => $media,
        'fnOnClick' => 'showMedia'
      ])
    @else
      <h4>Ucode Not Found.</h4>
    @endif
  </div>
</div>

@if ($media != "" && count($media) > 0)
  <div class="row">
    <div class="col-xs-12">
      <div class="content" id="mediaAjax">
        @include('beta.partials.media.details', [
          'media' => $media[0],
          'auth' => isset($auth) ? $auth : false,
          'ucode' => $ucode
        ])
      </div>
    </div>
  </div>
@endif

<script type="text/javascript">
  function showMedia(id) {
    $.ajax({
      url: "<?php echo url('media/ajax')?>/"+ id,
      success: function(response) {
        $("#mediaAjax").html(response);
      }
    });
    return false;
  }
</script>
