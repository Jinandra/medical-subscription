(function (
 COLLECTIONS_MAPPING,
 CSRF_TOKEN,
 URL_COLLECTION
) {
  // Compile handlebars template
  function ct (domId) {
    return Handlebars.compile($(domId).html());
  }

  // Register templates
  var TEMPLATES = {
    sidebarCollection:  ct('#tmpl-sidebar-collection'),
    emptyPin:           ct('#tmpl-empty-pin'),
    emptyPreview:       ct('#tmpl-empty-preview'),
    selectAllSelector:  ct('#tmpl-select-all')
  };


  // List pseudo collections
  function isPseudoCollection (collectionId) {
    switch (collectionId) {
      case 'bookmarked':
      case 'liked':
      case 'history':
      case 'contributed':
      case 'basic':
        return true;
      default:
        return false;
    }
  }

  function isSavedFolder ($collection) {
    return (($collection.data('original-id') && $collection.data('original-id')) || '') !== '';
  }
  function isCategoriedFolder ($collection) {
    return (($collection.data('category-id') && $collection.data('category-id')) || '') !== '';
  }
  function isOriginal ($collection) {
    return !isPseudoCollection($collection.data('id')) &&
      !isSavedFolder($collection) && !isCategoriedFolder($collection);
  }

  function splitCollectionMediumId (value) {
    var arr = value.split('-');
    return {
      collectionId: arr[0],
      mediumId:     arr[1]
    };
  }


  // AJAX sort media of a collection (collectionId, array of media id, callback on success)
  function ajaxSortCollectionMedia (collectionId, media, onSuccess) {
    $.ajax({
     type:  'PATCH',
     url:   URL_COLLECTION,
     data: {
       action:          'sort',
       collection_id:   collectionId,
       media:           media,
       _token:          CSRF_TOKEN
     },
     success: onSuccess
    });
  }

  // Get & set collections mapping
  function gc (id) {
    return COLLECTIONS_MAPPING['collection-'+id];
  }
  function sc (id, data) {
    COLLECTIONS_MAPPING['collection-'+id] = data;
  }

  // Animate pinned/unpinned item
  function animatePinned ($item, $container) {
    $item.slideUp('show', function () {
      if ($item.hasClass('pinned')) {
        $item.detach().prependTo($container);
      } else {
        $item.detach().appendTo($container);
      }
      $item.slideDown('medium');
    });
  }


  // Set selected on all media of sidebar collection
  function setCheckboxSidebar (collectionId, checked) {
    $('.collection-folder-sidebar .collection-menu-item[data-collection-id='+collectionId+']')
      .find('input[type=checkbox]')
      .prop('checked', checked);
  }

  // Highlight / unhighlight select all text on media checkbox changed (checked/not)
  function highlightSelectAll ($collectionMediaWrap) {
    var isAllChecked = function ($container) {  // collectionMediaWrap
      var totalCheckbox = $container.find('input[type=checkbox]').length;
      var totalChecked  = $container.find('input[type=checkbox]:checked').length;
      return totalCheckbox > 0 && totalCheckbox === totalChecked;
    };
    var highlight = function ($container) { // collectionMediaWrap
      var $selector  = $container.siblings('.collection-media-selector');
      $selector.find('.select-all').removeClass('active');
      if (isAllChecked($container)) {
        $selector.find('.select-all').addClass('active');
      }
    };
    $('.collection-media-wrap[data-id='+$collectionMediaWrap.data('id')+']').each(function (_i, c) {
      highlight($(c));
    });
    setCheckboxSidebar($collectionMediaWrap.data('id'), isAllChecked($collectionMediaWrap));
  }

  // Make ajax request for newly sorted collection, and sync with preview/pinned
  function sortFolderMedia ($collectionContainer) {
    var collectionId = $collectionContainer.data('id');
     if ( isPseudoCollection(collectionId) ) {
       return;
     }
    // sync the other "same" container that appear (could be on preview or pinned)
    var syncContainer = function () {
      var $parentContainer = $collectionContainer.parents('.collection-grid-panel[data-collection-id='+collectionId+']');
      $.each($('.collection-grid-panel[data-collection-id='+collectionId+']'), function (i, container) {
        if ( !$(container).is($parentContainer) ) {
          $(container).html($parentContainer.children().clone());
          $(container).find('.collection-media-selector').toggleClass('tac');
          initSortable();
          if ($parentContainer.hasClass('panel-empty')) {
            $(container).addClass('panel-empty');
          } else {
            $(container).removeClass('panel-empty');
          }
        }
      });
      // sync with global collections mapping
      var collection = gc($parentContainer.data('collection-id'));
      var html   = '';
      collection.media = $parentContainer.find('.collection-media-item').not('.empty-media').map(function (i, item) {
        var $checkbox = $(item).find('input[type=checkbox]');
        if (typeof $checkbox.val() === 'undefined') {
          console.log($parentContainer);
        }
        var id    = $checkbox.val().split('-')[1];
        var title = $checkbox.data('title');
        html += '<li data-id="'+id+'">'+title+'</li>';
        return { id: id, title: title };
      }).toArray();
    };
    var media = $collectionContainer.find('input[type=checkbox]').map(function (i, checkbox) {
      return $(checkbox).val().split('-')[1];
    }).toArray();
    if (media.length > 0) {
      ajaxSortCollectionMedia(collectionId, media, function () {
        syncContainer();
      });
    } else {
      syncContainer();
    }
  }

  // Move medium to sidebar collection (left menu)
  function addToLeftMenu (targetFolderId, medium) {
    var collection = gc(targetFolderId);
    if (typeof collection.media.find(function (m) { return String(m.id) === String(medium.id); }) !== 'undefined') {
      return; // don't add if already exists
    }
    collection.media.push(medium);
    ajaxSortCollectionMedia(
      targetFolderId,
      collection.media.map(function (m) { return m.id; })
    );
  }

  // Attach sortable handler on collection item
  function initSortable () {
    // Sortable table (drag & drop)
    $( ".collection-media-wrap" ).sortable({
      connectWith: ".collection-media-wrap",
      helper: 'clone',
      dropOnEmpty: true,
      tolerance: "pointer",
      items: ".collection-media-item:not(.empty-media)",
      start: function (e, ui) {
        ui.helper.animate({
            width:  80,
            height: 50
        });
      },
      over: function () {
        var isSidebar = $(this).parents('.collection-folder-sidebar').length > 0;
        var isSidebarSaved      = $(this).parents('.collection-folder-sidebar.saved-folder').length > 0;
        var isSidebarCategoried = $(this).parents('.collection-folder-sidebar.categoried-folder').length > 0;
        if ( isSidebar && !isSidebarSaved && !isSavedFolder($(this)) && !isSidebarCategoried && !isCategoriedFolder($(this)) ) {
          $(this).addClass('sortable-highlight')
        }
      },
      out: function () {
        $('.collection-folder-sidebar .sortable-highlight').removeClass('sortable-highlight')
      },
      cursorAt: {left:5, top:5},
      receive: function (e, ui) {
        var $target  = $(e.target);
        var targetFolderId = $target.data('id');
        var isSideMenu = $target.parents('.collection-folder-sidebar').length > 0;  // move to left menu
        if (isSideMenu) {
          targetFolderId = $target.data('collection-id');
        }
        var cancelSortable = function () {
          ui.sender.sortable('cancel');
          $(ui.item).css('display', 'table');
        };
        if (typeof targetFolderId === 'undefined') {
          cancelSortable();
          return;
        }
        if ( isPseudoCollection(targetFolderId) ) {
          cancelSortable();
          return;
        }
        if ( isSavedFolder($target) || isCategoriedFolder($target) ) {
          cancelSortable();
          return;
        }
        var checkboxSelector = 'input[type=checkbox]';
        var extractId = function ($checkbox) {
          return $checkbox.val().split('-')[1];
        };
        var extractIds = function (_i, checkbox) {
          return extractId($(checkbox));
        };
        var $sourceCheckbox = $(ui.item).find(checkboxSelector);
        var sourceFolderId  = $sourceCheckbox.val().split('-')[0];
        var sourceMediaId   = extractId($sourceCheckbox);
        if (String(sourceFolderId) === String(targetFolderId)) {
          cancelSortable();
          return;
        }

        var isCollectionHasMedia = function () {
          var collection = gc(targetFolderId);
          return typeof (collection.media.find(function (m) { return String(m.id) === String(sourceMediaId) })) !== 'undefined';
        };

        if (isCollectionHasMedia(targetFolderId, sourceMediaId)) {
          cancelSortable();
          return;
        }

        var movedSuccess = function () {
          // handle source container if it's original
          if ( isOriginal($(ui.sender)) && $(ui.sender).find(checkboxSelector).length === 0 ) { // source now empty
            $(ui.sender).find('.empty-media').css('display', 'block');
            $(ui.sender).parent().addClass('panel-empty');
          }

          var removeEmptyState = function ($collectionMediaWrap) { // remove target's empty state
            $collectionMediaWrap.find('.empty-media').css('display', 'none');
            $collectionMediaWrap.parent().removeClass('panel-empty');
          };

          var handleUnmoveableMedia = function () { // stay intact (don't move) the media if it's unmoveable (pseudo, copied, categories)
            if ( isPseudoCollection(sourceFolderId) || isSavedFolder($(ui.sender)) || isCategoriedFolder($(ui.sender)) ) {
              var $clonedItem = $(ui.item).clone();
              $clonedItem.css('display', 'table');

              // Find original position
              var items = $(ui.sender.find('.collection-media-item'));
              var found = null;
              for (var i=0 ; i < items.length ; i++) {
                var itemTop = $(items[i]).position().top;
                if (itemTop > 0 && ui.originalPosition.top <= itemTop) {
                  found = $(items[i]);
                  break;
                }
              }
              if (found === null) {
                $(ui.sender).append($clonedItem);
              } else {
                found.before($clonedItem);
              }
            }
          };

          var hasMedia = function ($collectionMediaWrap, isSideMenu) { // check if collection already has media
            var diff = isSideMenu === true ? 0 : 1; // except for side menu, will already receive the item before this is processed
            return $.grep(
              $collectionMediaWrap.find(checkboxSelector).map(extractIds),
              function (id) { return String(id) === String(sourceMediaId); }
            ).length > diff;
          };

          handleUnmoveableMedia();

          var newId = targetFolderId+'-'+sourceMediaId;

          if (isSideMenu) {
            var q = '.collection-media-wrap[data-id='+targetFolderId+']';
            $('#preview '+q+', #pinBoard '+q).each(function (_i, t) {
              var $t = $(t);
              removeEmptyState($t);
              if ( !hasMedia($t, true) ) {
                var $clonedItem = $(ui.item).clone();
                $clonedItem.css('display', 'table');
                $clonedItem.find('input[type=checkbox]').val(newId);
                $clonedItem.find('.btnDeleteMedium').data('id', newId);
                $t.append($clonedItem);
              }
            });
            var $collectionMediaWrap = $(q).first();
            if ($collectionMediaWrap.length > 0) {
              highlightSelectAll($collectionMediaWrap);
              sortFolderMedia($collectionMediaWrap);
            } else {
              var title = $(ui.item).find('input[type=checkbox]').data('title');
              addToLeftMenu(targetFolderId, { id: sourceMediaId, title: title });
            }
          } else {
            removeEmptyState($target);
            if (hasMedia($target)) {
              $(ui.item).remove();
            } else {
              $(ui.item).find('input[type=checkbox]').val(newId);
              $(ui.item).find('.btnDeleteMedium').data('id', newId);
            }
            highlightSelectAll($target);
            sortFolderMedia($target);
          }

          highlightSelectAll($(ui.sender));
          sortFolderMedia($(ui.sender));
        };

        var data = {
          action: 'move',
          _token:  CSRF_TOKEN,
          collections: {},
          targets: [targetFolderId]
        };
        data.collections[sourceFolderId] = [sourceMediaId];
        $.ajax({
          type: 'PATCH',
          url: COLLECTION_URL,
          action: 'move',
          data: data,
          success: movedSuccess
        });
      },
      stop: function (e, ui) {
        // Only deal when sorting item in the same container
        if ($(e.target).data('id') !== $(ui.item).parent().data('id')) {
          return;
        }
        var targetFolderId = $(e.target).data('id');
        if ( isPseudoCollection(targetFolderId) ) {
          $(this).sortable('cancel');
          $(ui.item).css('display', 'table');
          return;
        }
        sortFolderMedia($(e.target));
      },
      cursorAt: {left:5, top:5}
    }).disableSelection();
  }

  // Called on new created collection
  function onCreatedFolder (data) {
    sc(data.id, {
      id: data.id,
      name: data.name,
      description: data.description,
      category_id: null,
      original_id: null,
      media: []
    });
    var dom = TEMPLATES.sidebarCollection(data);
    $('.collection-folder-sidebar.all-folder').append(dom);
    $('.collection-folder-sidebar.created-folder').append(dom);
    initPopOver();
    initSortable();
  }

  // Get all selected checkboxes
  function getSelected () {
    var collections = {};
    $.each($('input[type=checkbox].folder:checked'), function (_i, checkbox) {
      var collectionId = $(checkbox).val();
      var collection   = $.extend(true, {}, gc(collectionId));
      if (collection.media.length > 0) {
        collection.media.push('self');
      }
      collections[collectionId] = collection;
    });
    $.each($('input[type=checkbox].medium:checked'), function (_i, medium) {
      var $medium = $(medium);
      var ids = splitCollectionMediumId($medium.val());

      var collection = collections[ids.collectionId];
      if (typeof collection !== 'undefined') {
        if (collection.media.indexOf('self') === -1 &&
            typeof (collection.media.find(function (m) { return typeof m === 'object' && String(m.id) === String(ids.mediumId); })) === 'undefined'
           ) {  // only add if it's not been added

          collection.media.push({ id: ids.mediumId, title: $medium.data('title') });
        }
      } else { // not yet added, start adding the media
        collection = $.extend(true, {}, gc(ids.collectionId));
        collection.media = [{ id: ids.mediumId, title: $medium.data('title') }];
        collections[ids.collectionId] = collection;
      }
    });
    // sort the collections by name and give it back as object
    return sortByStringField(objectToArray(collections), 'name');
  }



  // Handler Select All | None
  $(document.body).on('click', '.select-all', function () {
    var setActive = function ($collectionGridPanel) {
      $collectionGridPanel.find('input[type=checkbox]').prop( "checked", true);
      $collectionGridPanel.find('.select-all').addClass('active');
    };
    var collectionId = $(this).parents('.collection-grid-panel').data('collection-id');
    $('.collection-grid-panel[data-collection-id='+collectionId+']').each(function (_i, c) {
      setActive($(c));
    });
    setCheckboxSidebar(collectionId, true);
  });
  $(document.body).on('click', '.select-none', function () {
    var setInactive = function ($collectionGridPanel) {
      $collectionGridPanel.find('input[type=checkbox]').prop( "checked", false);
      $collectionGridPanel.find('.select-all').removeClass('active');
    };
    var collectionId = $(this).parents('.collection-grid-panel').data('collection-id');
    $('.collection-grid-panel[data-collection-id='+collectionId+']').each(function (_i, c) {
      setInactive($(c));
    });
    setCheckboxSidebar(collectionId, false);
  });

  // Handle accordion toggle content
  $(document.body).on('click', '.accordion-toggle', function () {
    var $container = $(this).closest('.collection-accordion-wrap');
    $container.toggleClass('active');
  });

  // Handle create collection
  $('#btnCreateFolder').on('click', function (e) {
    ENFOLINK.modal.showCreateFolder(onCreatedFolder);
  });

  // Checkbox change, affect select all|none state
  $(document.body).on('click', '.collection-media-item input[type=checkbox]', function (e) {
    var $collectionMediaWrap = $(this).parents('.collection-media-wrap');
    var value = $(this).val();
    var isChecked = $(this).is(':checked');
    $('.collection-media-wrap[data-id='+$collectionMediaWrap.data('id')+']').each(function (_i, c) {
      $(c).find('input[type=checkbox][value='+value+']').prop('checked', isChecked);
    });
    highlightSelectAll($collectionMediaWrap);
  });

  // Checkbox change on left menu, affect select all|none state
  $(document.body).on('click', '.collection-folder-sidebar input[type=checkbox]', function (e) {
    var classToClick = $(this).is(':checked') ? '.select-all' : '.select-none';
    $('.collection-grid-panel[data-collection-id='+$(this).val()+']').first().find(classToClick).trigger('click');
  });

  // View grid mode
  $('.collection-pin-board .view-grid-ico').on('click', function () {
    $('.view-grid-ico').removeClass('active');
    var elem = $(this);
    var gridClass = ['collection-grid-wrap-1','collection-grid-wrap-2','collection-grid-wrap-3']
    var gridClassIco = ['view-grid-ico-1','view-grid-ico-2','view-grid-ico-3'];
    var gridSingle = elem.hasClass(gridClassIco[0]);
    var gridDouble = elem.hasClass(gridClassIco[1]);
    var gridTriple = elem.hasClass(gridClassIco[2]);
    var colGridWrap = $('.collection-pin-board .collection-grid-wrap');

    elem.addClass('active')

    var selectedGrid = '';
    if (gridSingle) {
      selectedGrid = '1';
    } else if (gridDouble) {
      selectedGrid = '2';
    } else if (gridTriple) {
      selectedGrid = '3';
    }
    $.ajax({
      type: 'PUT',
      url:  COLLECTION_URL+"/gridview",
      data: {
        layout: selectedGrid,
        _token: CSRF_TOKEN
      }
    });

    gridClass.forEach(function(e) {
      colGridWrap.removeClass(e);
    })

    if(gridTriple){
      colGridWrap.addClass(gridClass[2])
    } else if (gridDouble) {
      colGridWrap.addClass(gridClass[1])
    } else {
      colGridWrap.addClass(gridClass[0])
    }
  });

  // Handle left menu item click (Show Preview)
  $(document.body).on('click', '.collection-menu-item', function (e) {
    var $target = $(e.target);
    if ($target.is('span.ico-checkbox') || $target.is('input[type=checkbox]') ||
      $target.is('i.fa-ellipsis-v') || $target.is('i.fa-thumb-tack')) {
      return;
    }
    var $container = $(this);
    if ($container.hasClass('selected')) {
      $('.collection-menu-item').removeClass('selected');
      $('#preview').html(TEMPLATES.emptyPreview());
    } else {
      $('.collection-menu-item').removeClass('selected');
      $('.collection-menu-item[data-collection-id='+$container.data('collection-id')+']').addClass('selected');
      var url = URL_COLLECTION + '/' + $container.data('collection-id') + '/preview';
      $.ajax({
        url: url,
        success: function (response) {
          $('#preview').html(response);
          $('#quickBoard').addClass('active');
          initPopOver();
          initSortable();
        }
      });
    }
  });

  // Ellipsis handler
  (function () {
    var delta = 10;
    $(window).on('show.bs.dropdown', function(e) {
      var $button = $(e.target);
      if ($button.parent().hasClass('accordion-dropdown-elipsis')) {
        var $dropdownContainer = $button.parents('.collection-menu-item, .collection-media-item');
        var $scrollContainer   = $dropdownContainer.parents('.collection-folder-sidebar, .collection-media-wrap');

        if ($scrollContainer.length === 0) {
          return;
        }

        var $menu = $button.find('.dropdown-menu');

        var visibleArea = $scrollContainer.height() + $scrollContainer.scrollTop();
        var bottomDropdown = ($scrollContainer.scrollTop() + $dropdownContainer.position().top) - $scrollContainer.position().top + $dropdownContainer.height();
        var bottomEdge = bottomDropdown + $menu.height() + delta;
        if (bottomEdge > visibleArea) {
          var diff = 0;
          // var diff = $dropdownContainer.height() > $button.height() ? $dropdownContainer.height() / 2 : $dropdownContainer.height() - delta;
          $menu.css('top', -1 * ($menu.height() + diff));
        }
      }
    });
  })();

  // Pin collection button
  $(document.body).on('click', 'a.pinning', function (e) {
    e.preventDefault();
    var $anchor = $(this);
    var url = $anchor.attr('href');
    $.get(url, function () {
      var $container = $anchor.parents('.collection-menu-item');
      $container.toggleClass('pinned');

      var extractedHref = url.match(/(.*)\?(.*)=(.*)/);
      var baseHref = extractedHref[1];
      var isPin    = extractedHref[2] === 'pin' ? true : false;
      var collectionId = extractedHref[3];
      var replacedUrl = baseHref + '?' + (isPin ? 'unpin' : 'pin') + '=' + collectionId;
      $anchor.attr('href', replacedUrl);
      $anchor.parent().attr('data-content', (isPin ? 'unpin' : 'pin'));

      if (isPin) { // add to pinboard
        $.get(COLLECTION_URL+'/'+collectionId+'/content' , function (html) {
          $('#pinBoard .collection-accordion-content .empty-pin').remove();
          $('#pinBoard .collection-accordion-content').append(html);
          initPopOver();
          initSortable();
        });
      } else {
        // remove from pin board
        $('#pinBoard .collection-accordion-content .collection-grid-panel[data-collection-id='+collectionId+']').remove();
        if ($('#pinBoard .collection-accordion-content .collection-grid-panel').length === 0) { // if pin board empty
          $('#pinBoard .collection-accordion-content').append(TEMPLATES.emptyPin());
        }
        hidePopOver();
      }
      // change state on quick view & left menu
      var quickViewPinSel = '#quickBoard .collection-grid-panel[data-collection-id='+collectionId+'] .icon-close.pin';
      var leftMenuSel = '.collection-menu-item[data-collection-id='+collectionId+']';
      if (isPin) {
        $(quickViewPinSel).attr('data-content', 'unpin');
        $(quickViewPinSel).addClass('pinned');
        $(leftMenuSel+' .menu-icon-pin').attr('data-content', 'unpin');
        $(leftMenuSel).addClass('pinned');
      } else {
        $(quickViewPinSel).attr('data-content', 'pin');
        $(quickViewPinSel).removeClass('pinned');
        $(leftMenuSel+' .menu-icon-pin').attr('data-content', 'pin');
        $(leftMenuSel).removeClass('pinned');
      }
      $(quickViewPinSel+' a').attr('href', replacedUrl);
      $(leftMenuSel+' a').attr('href', replacedUrl);

      // animate left menu
      if ($container.length === 0) {
        $container = $(leftMenuSel);
      }

      var $folderContainer = $container.parents('.collection-folder-sidebar');
      if ($folderContainer.length === 0) {
        $folderContainer = $container.parents('.static-folder');
        animatePinned($container, $folderContainer);
      } else { // Sync all tabs
        $.each($('.collection-folder-sidebar '+leftMenuSel), function (_i, c) {
          animatePinned($(c), $(c).parents('.collection-folder-sidebar'));
        });
      }
    });
  });

  // Edit collection handler
  $(document.body).on('click', '.btnEditFolder', function (e) {
    e.preventDefault();
    var collection = gc($(e.target).data('id'));
    ENFOLINK.modal.showEditFolder(collection,
      function success (updatedCollection) {
        collection.name = updatedCollection.name;
        collection.description = updatedCollection.description;
        // left menu
        var $collectionMenu = $('.collection-folder-sidebar .collection-menu-item[data-collection-id='+collection.id+']');
        $collectionMenu.find('input[type=checkbox]').data('name', collection.name);
        $collectionMenu.find('.collection-name').text(collection.name);
        $collectionMenu.find('img[data-toggle=popover]').attr('data-content', collection.description);

        $('.collection-grid-panel[data-collection-id='+collection.id+'] .collection-accordion-head').each(function (_i, panel) {
          var $panel = $(panel);
          $panel.find('.accordion-title .txt-bold').text(collection.name);
          $panel.find('.icon-info img').attr('data-content', collection.description);
        });
      }
    );
  });

  // Delete single collection handler
  $(document.body).on('click', '.btnDeleteFolder', function (e) {
    e.preventDefault();
    var id = $(e.target).data('id');
    var collection = $.extend(true, {}, gc(id));
    if (collection.media.length > 0) {
      collection.media.push('self');
    }
    ENFOLINK.modal.showDelete({
      collections: [collection]
    });
  });

  // Delete single collection's medium handler
  $(document.body).on('click', '.btnDeleteMedium', function (e) {
    e.preventDefault();
    var $button = $(e.target);
    var id = splitCollectionMediumId($button.data('id'));
    var collection = $.extend(true, {}, gc(id.collectionId));
    collection.media = [{
      id: id.mediumId,
      title: $button.data('title')
    }];
    ENFOLINK.modal.showDelete({
      collections: [collection]
    });
  });

  // return collections as array (not pseudo, saved, category)
  function collectionsMappingAsArray () {
    return objectToArray(COLLECTIONS_MAPPING)
    .filter(function (collection) {
      return !isPseudoCollection(collection.id) && 
        (typeof collection.category_id === 'undefined' || collection.category_id === null) &&
        (typeof collection.original_id === 'undefined' || collection.original_id === null);
    });
  }

  $('#btnBulkDelete, #btnBulkMove, #btnBulkCopy, #btnBulkBundle').on('click', function (e) {
    var $button = $(e.currentTarget);
    var collections = getSelected();
    if (collections.length === 0) {
      ENFOLINK.modal.showEmptySelection();
    } else {
      switch ($button.attr('id')) {
        case 'btnBulkDelete':
          ENFOLINK.modal.showDelete({
            collections: collections
          });
          break;
        case 'btnBulkMove':
          ENFOLINK.modal.showMoveTo({
            collections: collections,
            targets: collectionsMappingAsArray(),
            onCreatedFolder: onCreatedFolder
          });
          break;
        case 'btnBulkCopy':
          ENFOLINK.modal.showCopyTo({
            collections: collections,
            targets: collectionsMappingAsArray(),
            onCreatedFolder: onCreatedFolder
          });
          break;
        case 'btnBulkBundle':
          var collectionsToSent = collections.reduce(function (object, collection) {
            object[collection.id] = collection.media.map(function (medium) {
              return typeof medium === 'object' ? medium.id : medium;
            });
            return object;
          }, {});
          $.ajax({
            type: 'POST',
            url:  COLLECTION_URL+"/bundle",
            data: {
              collections: collectionsToSent,
              _token: CSRF_TOKEN
            },
            success: function (data) {
              $('#add-bundle-number').html(data.countBundleCart);
            }
          });
          break;
      }
    }
  });


  initSortable();
})(
  window.COLLECTIONS_MAPPING,
  window.CSRF_TOKEN,
  window.URL_COLLECTION
);
