<script id="tmpl-sidebar-collection" type="text/x-handlebars-template">
  <div class="collection-menu-item collection-media-wrap" data-collection-id="@{{id}}" data-original-id="" data-category-id="">
    <label class="checkbox-default">
      <input type="checkbox" class="folder" value="@{{id}}" data-name="@{{name}}">
      <span class="ico-checkbox"></span>
    </label>
    <span class="collection-name">@{{name}}</span>
    <div class="menu-icon-info">
    <img src="{{ config('app.assets_path').'/images/ico-info.png' }}" alt=""  data-toggle="popover" data-content="@{{description}}">
    </div>
    <div class="menu-icon-pin" data-toggle="popover" data-content="pin"><a class="pinning" href="{{ url('collection/pin?pin=') }}@{{id}}"><i class="fa fa-thumb-tack"></i></a></div>
    <div class="accordion-dropdown-elipsis">
      <div class="dropdown pull-right">
        <div class="more-action" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-ellipsis-v"></i>
        </div>
        <ul class="dropdown-menu pull-right" aria-labelledby="Menu Collection">
          <li><a href="{{ url('folder') }}/@{{id}}" target="_blank">Preview</a></li>
          <li><a href="#" class="btnEditFolder" data-id="@{{id}}">Edit</a></li>
          <li><a href="#" class="btnDeleteFolder" data-type='folder' data-id="@{{id}}" data-toggle="modal" data-target="#modal-delete-single" data-dismiss="modal">Delete</a></li>
        </ul>
      </div>
    </div>
  </div>
</script>
