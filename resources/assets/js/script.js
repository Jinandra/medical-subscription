var LIMIT_TEXT_TOGGLE_MORE_CLASSNAME = 'limit-text-toggle-more';


// Plugins
(function ($) {
  // handle click more on truncated text
  $.fn.limitTextToggleMore = function () {
    return this.each(function () {
      $(this).on('click', function (e) {
        e.preventDefault();
        $(this).parent().parent().find('.limit-text-hidden').show();
        $(this).parent().hide();
      });
    });
  };

  // limit text by truncating and read more
  $.fn.limitText = function (opts) {
    var options = $.extend({
      limitChar: 250,
      label: 'Read More',
      ellipsis: '...',
    }, opts);
    return this.each(function () {
      if ($(this).find('.truncated-text').length > 0) {
        return;
      }
      var content = $(this).html();
      if (content.length > options.limitChar) {
        var fullText = '<div class="limit-text-hidden">' + content + '</div>';
        var link = $('<a class="'+LIMIT_TEXT_TOGGLE_MORE_CLASSNAME+' dib txt-link txt-green">').append(options.ellipsis).append(options.label);
        var c = content.substr(0, options.limitChar);
        var truncatedText = $('<div class="truncated-text">').append(c).append(link);
        $(this).html(truncatedText).append(fullText);

        $(link).limitTextToggleMore();
      };
    });
  };

  // button to send a media to folder
  $.fn.sendToFolderButton = function () {
    return this.each(function () {
      $(this).on('click', function (e) {
        e.preventDefault();
        var $trigger = $(this);
        var href     = $trigger.attr('href');
        var $listContainer = $trigger.parent().parent();

        $.ajax({
          url: href,
          success: function (response) {
            var newList = '';
            var baseURL = $listContainer.data('base-url');
            var mediaID = $listContainer.data('media-id');
            $.each(response.collections, function (_i, collection) {
              if (collection.isAdded) {
                newList += '<li style="width:100%"><a><i class="fa fa-check"></i> '+collection.name+'</a></li>';
              } else {
                newList += '<li style="width:100%"><a class="send-to-folder" style="cursor:pointer" href="'+baseURL+'/'+mediaID+'/'+collection.id+'">'+collection.name+'</a></li>';
              }
            });
            $listContainer.html(newList);
            $('.send-to-folder').sendToFolderButton();
          }
        });
      });
    });
  };

  $.fn.saveCollectionButton = function (opts) {
    var options = $.extend({
      activeClassName: 'active'
    }, opts);
    return this.each(function () {
      $(this).on('click', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var token = $('.hiddenToken').val();
        $.ajax({
          method: 'post',
          data: {'_token': token},
          url: url,
          success: function (data) {
            var msg = data.message;
            $('.folder-block').each(function() {
              if($(this).attr('data-original')) {
                var originalId = $(this).attr('data-original');
              } else {
                originalId = $(this).attr('id').replace('folder_', '');
              }
              if(originalId == data.originalId) {
                var button = $(this).find('.set-folder');
                if (msg === 'copied') {
                  button.attr('data-content', 'Folder is already in your My Collection');
                } else if (msg === 'removed') {
                  button.attr('data-content', 'Save collection');
                }
                button.toggleClass(options.activeClassName);
              }
            });
            if (msg === 'copied') {
//              $('#search_folders').append(data.data);
            } else if (msg === 'removed') {
              for(var key in data.data) {
                var removedId = data.data[key];
//                $('#folder_' + removedId).remove();
              }
            }
          }
        });
      });
    });
  };

  // button to add a media into bundle
  $.fn.bundleButton = function (opts) {
    var options = $.extend({
      activeClassName: 'active'
    }, opts);
    return this.each(function () {
      $(this).on('click', function (e) {
        e.preventDefault();
        var $button = $(this);
        var href    = $button.attr('href');

        $.ajax({
          url: href,
          success: function (response) {
            $("#add-bundle-number").html(response.countBundleCart);
            if ($button.hasClass(options.activeClassName)) {
              $button.attr('data-content', 'Add to bundle');
            } else {
              $button.attr('data-content', 'Remove from bundle');
            }
            $button.toggleClass(options.activeClassName);
            if (typeof options.onSuccess === 'function') {
              options.onSuccess($button);
            }
          }
        });
      });
    });
  };


  var NORMAL_IMAGE = 'http://s3.amazonaws.com/enfolink-assets/images/bundle-btn-sm.png';
  var ADDED_IMAGE  = 'http://s3.amazonaws.com/enfolink-assets/images/bundle-btn-white-sm.png';
  // button to add a media into bundle (large version)
  $.fn.bundleButtonSM = function (opts) {
    var options = $.extend({
      className: 'bundle-added-sm'
    }, opts);
    return this.each(function () {
      $(this).on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $triggerButton = $(this);
        var url = $triggerButton.attr('href');
        var bundleAddedClassname = options.className;
        $.ajax({
          url: url,
          success: function (response) {
            $("#add-bundle-number").html(response.countBundleCart);
            if ($triggerButton.hasClass(bundleAddedClassname)) {
              $triggerButton.removeClass(bundleAddedClassname);
              $triggerButton.attr('data-content', 'Add to bundle');
              $triggerButton.find('img').attr('src', NORMAL_IMAGE);
            } else {
              $triggerButton.addClass(bundleAddedClassname);
              $triggerButton.attr('data-content', 'Remove from bundle');
              $triggerButton.find('img').attr('src', ADDED_IMAGE);
            }
            if (typeof options.onSuccess === 'function') {
              options.onSuccess($triggerButton, response);
            }
          }
        });
      });
    });
  };

  // button to bookmark a media
  $.fn.bookmarkButton = function () {
    return this.each(function () {
      $(this).on('click', function (e) {
        e.preventDefault();
        var $button = $(this);
        var href    = $button.attr('href');

        $.ajax({
          url: href,
          success: function (response) {
            if ($button.hasClass('active')) {
              $button.attr('data-content', 'Add to bookmark');
            } else {
              $button.attr('data-content', 'Remove from bookmark');
            }
            $button.toggleClass('active');
          }
        });
      });
    });
  };
}(jQuery));


function initTooltip () {
  $('body').tooltip({
    selector: '[data-toggle="tooltip"]'
  });
  $('.tooltip.tooltip-toggle').on({
    mouseover: function () {
      var a = $(this).offset();
      var c = $(window).width();
      var d = c - 450;

      if ($('.tooltip-floating').length == 0) {
        var h = $(this).find('.tooltiptext').html();
        var t = a.top + 40;
        var l;
        if (d < a.left) {
          l = a.left - 290;
          var x = 'tooltip-floating-right';
        }
        else {
          l = a.left - 5;
          var x = '';
        }
        var i = '<span class="tooltiptext tooltip-floating ' + x + '" style="top:' + t + 'px;left:' + l + 'px">' + h + '</span>'
        $('body').append(i);

        // reattach toggle more
        $('.'+LIMIT_TEXT_TOGGLE_MORE_CLASSNAME).limitTextToggleMore();
      }
    },
    mouseleave: function () {
      var hoverfloating = $('.tooltip-floating').is(":hover");
      if (hoverfloating) {
        $('.tooltip-floating').hover(
          function () { },
          function () { $('.tooltip-floating').remove(); }
        );
      } else {
        $('.tooltip-floating').remove();
      }
    }
  });
  $(document.body).on('mouseover', '.view-listing .tooltip', function () {
    var a = $(this).offset();
    var c = $(window).width();
    var d = c - 250;
    if (d < a.left) {
        $(this).find('.tooltiptext').addClass('pos-right')
    }
  });
}

function hidePopOver () {
  $('.popover.in').remove();
}

function initPopOver () {
  $(document.body).popover({
    trigger: 'hover',
    placement: 'bottom',
    container: 'body',
    selector: '[data-toggle="popover"]:not(.info)'
  });
  // $('[data-toggle="popover"]').not('.info').popover({
  //   trigger: 'hover',
  //   placement: 'bottom',
  //   container: 'body'
  // });
  $('[data-toggle="popover"].info').popover({
    html: true,
    container: 'body',
    trigger: 'manual',
    placement: 'bottom',
    content: function () {
      return 'No description available.';
    }
  }).on('mouseenter', function () {
    var _this = this;
    $(this).popover("show");
    $('.popover').on('mouseleave', function () {
      $(_this).popover('hide');
    });
  }).on('mouseleave', function () {
    var _this = this;
    setTimeout(function () {
      if ( !$(".popover:hover").length ) {
        $(_this).popover("hide");
      }
    }, 300);
  });

  (function() {
    var content = '';
    $('[data-toggle="popover"].info').on('show.bs.popover', function () {
      $(this).popover('getContent');
      content = $(this).data('bs.popover').getContent();
    });
    $('[data-toggle="popover"].info').on('inserted.bs.popover', function (foo, bar, baz) {
      // console.log('generate read more');
      var $popoverContent = $('.popover .popover-content');
      $popoverContent.html('<div class="limit-text">'+content+'</div>');
      $popoverContent.find('.limit-text').limitText();
    });
  })();
}


/**
 * Laravel 5
 * 
 * Initialize using:
 *      $(function () {
 *          anchorMethod.init();
 *      });
 * 
 * Default:
 *      <a href="URL" data-method="delete">
 * 
 * Confirmation dialog:
 *      <a href="URL" data-method="delete"
 *                    data-alert="confirm"
 *                    data-alert-text="Confirmation text">
 * 
 * SweetAlert confirmation dialog (http://t4t5.github.io/sweetalert):
 *      <a href="URL" data-method="delete"
 *                    data-alert="confirm"
 *                    data-alert-text="Confirmation text"
 *                    data-alert-title="Caution"
 *                    data-alert-button="Yes delete"
 *                    data-alert-cancel="Cancel">
 */
var anchorMethod = {
    init: function () {
        $('a[data-method]').on('click', function (e) {
            e.preventDefault();
            var link = $(this);
            var httpMethod = link.data('method').toUpperCase();
            var form;

            if ($.inArray(httpMethod, ['PUT', 'PATCH', 'DELETE']) === -1) {
                return;
            }

            if (link.data('alert') == "confirm") {
                // Try to load SweetAlert if applicable.
                if (typeof swal !== 'undefined') {
                    options = {
                        title:            link.data('alert-title'),
                        text:             link.data('alert-text'),
                        type:             "warning",
                        html:             true,
                        showCancelButton: true,
                        closeOnConfirm:   false
                    };
                    if (link.data('alert-button')) {
                        options['confirmButtonText'] = link.data('alert-button');
                    }
                    if (link.data('alert-cancel')) {
                        options['cancelButtonText'] = link.data('alert-cancel');
                    }
                    swal(options, function () {
                        anchorMethod.submit(link);
                    });
                }
                // Show a default confirm box (eventually as SweetAlert fallback)
                else if (confirm($($.parseHTML(link.data('alert-text'))).text())) {
                    anchorMethod.submit(link);
                }
            }
            else {
                anchorMethod.submit(link);
            }
        });
    },

    submit: function (link) {
        var form =
            $('<form>', {
                'method': 'POST',
                'action': link.attr('href')
            });

        var token =
            $('<input>', {
                'type':  'hidden',
                'name':  '_token',
                'value': $('meta[name="_token"]').attr('content')
            });

        var hiddenInput =
            $('<input>', {
                'name':  '_method',
                'type':  'hidden',
                'value': link.data('method')
            });

            (link.data('inputs') || '').split(',').forEach(function (name) {
              form.append(
                $('<input>', {
                  'name':  name,
                  'type':  'hidden',
                  'value': link.data('input-'+name)
                })
              );
            });

        form.append(token, hiddenInput)
            .appendTo('body').submit();
    }
};


// Init
$(function () {
  initTooltip();
  initPopOver();
  anchorMethod.init();
  $(document.body).on('click', '[toggle-target]', function () {
    var target = $(this).attr('toggle-target');
    $(target).toggleClass('active');
  });
});

//Copy to clipboard message
function copyToClipboardMsg(elem, msgElem, popup) {
    var succeed = copyToClipboard(elem, popup);
    var msgElem = document.getElementById(msgElem);
    var msg;
    if (!succeed) {
        msg = "Copy not supported or blocked.  Press Ctrl+c to copy."
    } else {
        msg = "Text copied to the clipboard."
    }
   
    if (msgElem != null){        
        msgElem.innerHTML = msg;        
        setTimeout(function() {
            msgElem.innerHTML = "";
        }, 2000);
    }
    
}
// Copy to clipboard text area
function copyToClipboard(elem, popup) {
    // create hidden text element, if it doesn't already exist
    if(popup=='popup'){
        var targetId = "_hiddenCopyText_";
    } else {
        var targetId = "_hiddenCopyTextContent_";
    }
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            if(popup=='popup'){
                $('.ucodepdfbutton').prepend(target);
            } else {
                document.body.appendChild(target);
            }
        }
        //alert(elem.textContent+'_content');
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    // copy the selection
    var succeed;
    try {
    	succeed = document.execCommand("copy", false, null);
        //alert(succeed+"_gggggg");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    //alert(succeed);
    return succeed;
}


function sortByStringField (array, field) {
  return array.sort(function (a, b) {
    return a[field].localeCompare(b[field]);
  });
}

function objectToArray (object) {
  return Object.keys(object).map(function (id) {
    return object[id];
  });
}
