(function (ALL_MEDIA, COLLECTION_MEDIA, BASE_MEDIA_URL) {
  console.log(ALL_MEDIA);
  console.log(COLLECTION_MEDIA);

  $(document).ready(function () {

    function createMediaList (media) {
      return '<li class="list-group-item"><input type="hidden" name="media[]" value="'+media.id+'" /><a target="_blank" href="'+BASE_MEDIA_URL+'/'+media.id+'">'+media.title+'</a> <i class="glyphicon glyphicon-remove"></i></li>';
    }

    // Modal remove
    (function () {
      var $selectedMedia = null;
      $(document.body).on('click',  '.manage-media .glyphicon-remove', function (e) {
        e.preventDefault();
        $selectedMedia = $(this).parent();
        $('#modal-confirm-remove .modal-body').html('Do you want to remove \''+$selectedMedia.text().trim()+'\'?');
        $('#modal-confirm-remove').modal('show');
      });
      $('#modal-confirm-remove .btn-danger').on('click', function (e) {
        e.preventDefault();
        $selectedMedia.remove();
        $('#modal-confirm-remove').modal('hide');
      });
    })();

    // Autocomplete
    $('#autocomplete').autocomplete({
      minLength: 2,
      focus: function( event, ui ) {
        $('#autocomplete').val(ui.item.title);
        return false;
      },
      source: function (request, response) {
        var inserteds = $('.manage-media li input').map(function (_i, input) {
          return $(input).val();
        }).toArray();
        var notInserted = function (m) {
          return inserteds.indexOf(String(m.id)) === -1;
        };
        var data = $.unique(ALL_MEDIA.filter(function (m) {
          var pattern = new RegExp(request.term, 'i');
          return pattern.test(m.title);
        })).filter(notInserted);
        response(data);
      },
      select: function (event, ui) {
        $('.manage-media').append(createMediaList(ui.item));
      }
    })
    .autocomplete('instance')._renderItem = function (ul, item) {
      return $('<li>').append(item.title).appendTo(ul);
    };
    $('#autocomplete').on('keypress', function (e) {
      if (e.which === 13) {
        e.preventDefault();
        window.alert('Please select from search result in order to add a media');
        // var media = ALL_MEDIA.find(function (m) { return m.title === $(this).val(); });
        // $('.manage-collections').append(createMediaList(media));
        // $(this).autocomplete('close');
        // $(this).val('');
      }
    });

    // Reset
    $('button[type=reset]').on('click', function (e) {
      if ( !confirm('Are you sure do you want to reset form data ?') ) {
        e.preventDefault();
        return;
      }
      var list = (typeof COLLECTION_MEDIA === 'undefined' ? [] : COLLECTION_MEDIA).map(createMediaList).join(' ');
      $('.manage-media').html(list);
    });

    // Submit
    $('form').on('submit', function (e) {
      if ($('.manage-media li').length === 0) {
        window.alert('Media can\'t be empty');
        e.preventDefault();
      }
      debugger;
    });

  });
})(
window.ALL_MEDIA,
window.COLLECTION_MEDIA,
window.BASE_MEDIA_URL
);
