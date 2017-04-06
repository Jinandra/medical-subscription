{{--
  -- Show preview for uploaded document
  -- PARAMS:
  -- $className => class name of doc
  -- $text => text to preview
  -- $url => url of the media
  --}}
<!DOCTYPE html>
<html>
  <head>
    <style>
      body {
        padding: 70px;
      }
      .preview-txt {
        font-size: 90pt;
        font-family: "Helvetica Neue","Helvetica","Arial","sans-serif";
      }
      .preview-doc {
        color: #080858;
      }
      .preview-xls {
        color: #206535;
      }
      .preview-ppt {
        color: #731818;
      }
    </style>
  </head>
  <body>
    <span class="preview-txt {{ $className }}"><b><?= $text; ?></b></span>
    <!--<iframe src="{{ $url }}" width="100%" height="456"></iframe>-->
  </body>
</html>
