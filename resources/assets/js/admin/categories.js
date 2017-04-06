(function (ALL_COLLECTIONS, CATEGORY_COLLECTIONS) {
  $(document).ready(function () {

    function createCollectionList (collection) {
      return '<li class="list-group-item">'+collection+'<i class="glyphicon glyphicon-remove"></i></li>';
    }

    // Modal remove
    (function () {
      var $selectedCollection = null;
      $(document.body).on('click',  '.manage-collections .glyphicon-remove', function (e) {
        e.preventDefault();
        $selectedCollection = $(this).parent();
        $('#modal-confirm-remove .modal-body').html('Do you want to remove \''+$selectedCollection.text().trim()+'\'?');
        $('#modal-confirm-remove').modal('show');
      });
      $('#modal-confirm-remove .btn-danger').on('click', function (e) {
        e.preventDefault();
        $selectedCollection.remove();
        $('#modal-confirm-remove').modal('hide');
      });
    })();

    // Autocomplete
    $('#autocomplete').autocomplete({
      minLength: 2,
      source: function (request, response) {
        var inserteds = $('.manage-collections li').map(function (_i, list) {
          return $(list).text().trim();
        }).toArray();
        var notInserted = function (c) {
          return inserteds.indexOf(c) === -1;
        };
        var data = $.unique(ALL_COLLECTIONS.filter(function (c) {
          var pattern = new RegExp(request.term, 'i');
          return pattern.test(c);
        })).filter(notInserted);
        response(data);
      }
    });
    $('#autocomplete').on('keypress', function (e) {
      if (e.which === 13) {
        e.preventDefault();
        $('.manage-collections').append(createCollectionList($(this).val()));
        $(this).autocomplete('close');
        $(this).val('');
      }
    });

    // Reset
    $('button[type=reset]').on('click', function (e) {
      if ( !confirm('Are you sure do you want to reset form data ?') ) {
        e.preventDefault();
        return;
      }
      var list = (typeof CATEGORY_COLLECTIONS !== 'undefined' ? CATEGORY_COLLECTIONS : []).map(createCollectionList).join(' ');
      $('.manage-collections').html(list);
    });

    // Submit
    $('form').on('submit', function (e) {
      var collections = $('.manage-collections li').map(function (_i, list) {
        return $(list).text().trim();
      }).toArray();
      if (collections.length === 0) {
        window.alert('Collections can\'t be empty');
        e.preventDefault();
      }
      $('input[name=collections]').val(collections);
    });

  });
})(
  window.DATA_SOURCE,
  window.CATEGORY_COLLECTIONS
);
