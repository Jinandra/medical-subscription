(function (
URL_COLLECTION,
URL_UCODES
) {

$(function () {
  Handlebars.registerPartial({
    sourceMedia:    $('#tmpl-source-media').html(),
    sourceFolders:  $('#tmpl-source-folders').html(),
    ucodeData:      $('#tmpl-source-ucode').html(),
    targetFolders:  $('#tmpl-target-folders').html(),
    formFolder:     $('#tmpl-form-folder').html()
  });
  function ct (domId) { // compile template
    return Handlebars.compile($(domId).html());
  }
  // Register templates
  var TEMPLATES = {
    actionTo:         ct('#tmpl-modal-action-to'),
    actionUcodeTo:    ct('#tmpl-modal-ucode-action-to'),
    folder:           ct('#tmpl-modal-folder'),
    emptySelection:   ct('#tmpl-modal-empty-selection'),
    duplicateMedia:   ct('#tmpl-modal-duplicate-media')
  };
  var MODAL_ID = '#enfolink-modal';


  // Global variables
  var gCurrentAction = {};  // save current action (save-to, copy-to)


  // Compilation pass data to template
  function compileSaveTo (data) {
    gCurrentAction = {
      action: 'save-to',
      data: $.extend({ selectedTargets: [] }, data)
    };
    return TEMPLATES.actionTo($.extend({
      title: 'Save to',
      canCreateFolder: true,
      form: {
        method: "PATCH",
        action: "copy"
      }
    }, data));
  }
  function compileCopyTo (data) {
    gCurrentAction = {
      action: 'copy-to',
      data: $.extend({ selectedTargets: [] }, data)
    };
    return TEMPLATES.actionTo($.extend({
      title: 'Copy to',
      canCreateFolder: true,
      form: {
        method: "PATCH",
        action: "copy"
      }
    }, data));
  }
  function compileMoveTo (data) {
    gCurrentAction = {
      action: 'move-to',
      data: $.extend({ selectedTargets: [] }, data)
    };
    return TEMPLATES.actionTo($.extend({
      title: 'Move to',
      canCreateFolder: true,
      form: {
        method: "PATCH",
        action: "move"
      }
    }, data));
  }
  function compileDuplicateMedia (data) {
    gCurrentAction = {
      action: 'duplicate-media',
      data: $.extend({ selectedTargets: [] }, data)
    };
    return TEMPLATES.duplicateMedia($.extend({
      title: 'Duplicate Media'
    }, data));
  }
  function compileDelete (data) {
    return TEMPLATES.actionTo($.extend({
      title: 'Delete Confirmation',
      delete: 'delete',
      form: {
        method: "DELETE"
      },
      submitLabel: 'Delete',
      mediaLabel: 'Are you sure want to delete?'
    }, data));
  }
  function compileUcodeDelete (data) {
    return TEMPLATES.actionUcodeTo($.extend({
      title: 'Delete Confirmation',
      delete: 'delete',
      form: {
        method: "DELETE",
        url: URL_UCODES
      },
      submitLabel: 'Delete',
      mediaLabel: 'Are you sure want to delete?'
    }, data));
  }
  function compileCreateFolder (auxData) {
    return TEMPLATES.folder($.extend({
      title: 'Create new folder',
      form: {
        url: URL_COLLECTION,
      }
    }, auxData));
  }
  function compileEditFolder (data) {
    return TEMPLATES.folder($.extend({
      title: 'Edit folder',
      form: {
        url: URL_COLLECTION+'/'+data.id,
        method: 'PATCH'
      }
    }, data));
  }
  function compileEmptySelection () {
    return TEMPLATES.emptySelection();
  }

  // Utils
  function replaceModalContent (content, animated, onDone) {
    var $modalContent = $(MODAL_ID+' .modal-content-placeholder');
    if (animated) {
      $modalContent.fadeOut('slow', function () {
        $modalContent.html(content);
        $modalContent.fadeIn('slow');
        if (typeof onDone === 'function') {
          onDone();
        }
      });
    } else {
      $modalContent.html(content);
      if (typeof onDone === 'function') {
        onDone();
      }
    }
  }
  function changeModalBackdrop (backdrop) {
    $(MODAL_ID).data('bs.modal').options.backdrop = backdrop || 'static';
  }
  function hideModal () {
    $(MODAL_ID).modal('hide');
  }
  function showModal (backdrop) {
    var $modal = $(MODAL_ID).modal({ backdrop: 'static', keyboard: false });
    changeModalBackdrop(backdrop);
  }
  function restoreModalContent () {
    var html = '';
    switch (gCurrentAction.action) {
      case 'save-to':
        html = compileSaveTo(gCurrentAction.data);
        break;
      case 'move-to':
        html = compileMoveTo(gCurrentAction.data);
        break;
      case 'copy-to':
        html = compileCopyTo(gCurrentAction.data);
        break;
      case 'duplicate-media':
        html = compileDuplicateMedia(gCurrentAction.data);
        break;
    }
    return html;
  }
  function attachFolderHandler (onSuccess, onFailed) {
    $('#form-folder').validator()
    .on('submit', function (e) {
      if (e.isDefaultPrevented()) { return; }
      e.preventDefault();
      var $form  = $(this);
      var method = $(this).attr('method');
      var $inputMethod = $(this).find('input[name=_method]');
      if ($inputMethod.length > 0) {
        method = $inputMethod.val();
      }
      var $btnSubmit = $form.find('input[type=submit]');
      $btnSubmit.attr('disabled', 'disabled');
      $.ajax({
        type: method,
        url:  $form.attr('action'),
        data: $(this).serialize(),
        success: function (resp) {
          $btnSubmit.removeAttr('disabled');
          if (resp.error) {
            var $errorBlock = $form.find('input[name=name]').siblings('.help-block.with-errors');
            var errorList = resp.message.map(function (msg) { return '<li>'+msg+'</li>'; });
            $errorBlock.html('<ul class="list-unstyled">'+errorList+'</ul>');
            $errorBlock.parents('.form-group').addClass('has-error').addClass('has-danger');
            if (typeof onFailed === 'function') {
              onFailed(resp.error);
            }
            return false;
          }
          $form.validator('destroy');
          if (typeof onSuccess === 'function') {
            onSuccess(resp);
          }
        }
      });
    });
  }

  $(document.body).on('submit', '#form-action-to', function (e) {
    if (gCurrentAction.data && typeof gCurrentAction.data.onSuccess === 'function') {
      e.preventDefault();
      var $form  = $(this);
      var method = $form.find('input[name=_method]').val();
      $.ajax({
        type: method,
        url:  $form.attr('action'),
        data: $(this).serialize(),
        success: function (resp) {
          gCurrentAction.data.onSuccess(resp);
          hideModal();
        }
      });
    }
  });

  // Handle create new folder inside modal
  $(document.body).on('click', MODAL_ID+' .create-folder', function (e) {
    // save current target selection
    gCurrentAction.modalCreate = true;
    var selectedTargets = $(this)
      .parents('.modal')
      .find("input[name*='targets']:checked")
      .map(function (_i, el) { return $(el).val(); })
      .toArray();
    var targets = $.extend(true, [], gCurrentAction.data.targets);
    selectedTargets.forEach (function (folderId) {
      var folder = targets.find(function (f) { return String(f.id) === String(folderId); });
      if (typeof folder !== 'undefined') {
        folder.selected = true;
      }
    });
    gCurrentAction.data.targets = targets;

    var html = compileCreateFolder({ showBack: true });
    replaceModalContent(html, true, function () {
      attachFolderHandler(function success(newFolder) {
        delete gCurrentAction.modalCreate;
        gCurrentAction.data.targets.unshift($.extend(true, {}, newFolder, { selected: true }));
        if (typeof gCurrentAction.data.onCreatedFolder === 'function') {
          gCurrentAction.data.onCreatedFolder(newFolder);
        }
        replaceModalContent(restoreModalContent(), true);
      });
    });
  });
  // Handle hide modal in case it's within another modal
  $(MODAL_ID).on('hide.bs.modal', function (e) {
    if (gCurrentAction.modalCreate) {
      e.preventDefault();
      delete gCurrentAction.modalCreate;
      replaceModalContent(restoreModalContent(), true);
    }
  });

  // Register methods on window.ENFOLINK.modal.*
  var enfolinkModal = {
    showSaveTo: function (data) {
      replaceModalContent(compileSaveTo(data));
      showModal();
    },
    showCopyTo: function (data) {
      replaceModalContent(compileCopyTo(data));
      showModal();
    },
    showMoveTo: function (data) {
      replaceModalContent(compileMoveTo(data));
      showModal();
    },
    showCreateFolder: function (onSuccess, onFailed) {
      replaceModalContent(compileCreateFolder());
      attachFolderHandler(function success(folder) {
        $(MODAL_ID).find('.content').html("<p>Folder '"+folder.name+"' created</p>");
        changeModalBackdrop(true);
        if (typeof onSuccess === 'function') {
          onSuccess(folder);
        }
      }, onFailed);
      showModal();
    },
    showEditFolder: function (data, onSuccess, onFailed) {
      replaceModalContent(compileEditFolder(data));
      attachFolderHandler(function success (folder) {
        $(MODAL_ID).find('.content').html("<p>Folder '"+folder.name+"' updated</p>");
        changeModalBackdrop(true);
        if (typeof onSuccess === 'function') {
          onSuccess(folder);
        }
      }, onFailed);
      showModal();
    },
    showDelete: function (data, onSuccess, onFailed) {
      replaceModalContent(compileDelete(data));
      showModal();
    },
    showUcodeDelete: function (data, onSuccess, onFailed) {
      replaceModalContent(compileUcodeDelete(data));
      showModal();
    },
    showEmptySelection: function () {
      replaceModalContent(compileEmptySelection());
      showModal(true);
    },
    showDuplicateMedia: function (data) {
      replaceModalContent(compileDuplicateMedia(data));
      showModal();
    }
  };
  window.ENFOLINK = $.extend(window.ENFOLINK, {});
  window.ENFOLINK.modal = $.extend(window.ENFOLINK.modal, enfolinkModal);

});

})(
window.URL_COLLECTION,
window.URL_UCODES
);
