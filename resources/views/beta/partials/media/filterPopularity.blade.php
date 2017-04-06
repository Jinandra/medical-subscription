<div class="dropdown-wrap">
  <div class="dib dropdown-title">
    <span>Newly Added</span>
    <i class="fa fa-caret-down"></i> 
  </div>
  <div class="dropdown-selection">
    <div class="dropdown-selection-item" data-value="newly">Newly Added</div>
    <div class="dropdown-selection-item" data-value="daily">Daily</div>
    <div class="dropdown-selection-item" data-value="weekly">Weekly</div>
    <div class="dropdown-selection-item" data-value="monthly">Monthly</div>
  </div>
</div>
<script>
  $(document).ready(function () {
    $('.dropdown-title').on('click',function(){
      $(this).parent().addClass('active');
    });
    $('.dropdown-selection-item').on('click',function(){ //alert(1);
      var picked_option = $(this).attr('data-value');
      var a = $(this).html();
      $(this).parents('.dropdown-wrap').find('.dropdown-title span').html(a);
      $('.dropdown-wrap').removeClass('active');
      $.ajax({
        url: '{{ url() }}/'+picked_option,
        success: function (response) {
          //alert(response)
          $("#ucodeAjax").html(response);
        }
      });
    });
  });
</script>
