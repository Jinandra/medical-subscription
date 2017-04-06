<div class="modal fade" id="enfolink-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog form-modal modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body form-popup">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
          aria-hidden="true">&times;</span></button>
        <div class="row">
          <div class="col-xs-12">
            <div class="modal-content-placeholder"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade modal-small" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog form-modal" role="document">
    <div class="modal-content">
      <div class="modal-body form-popup">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="row">
          <div class="col-xs-12">
            <h3>Confirmation</h3>
            <div>
              <div class="fwb cp mb10">
                Are you sure you want to delete this media?
              </div>
            </div>
            <hr class="hr-item mt0">
            <div>
              <span class="hidden removable-id"></span>
              <div class="fwb cp mb10" toggle-target=".confirmation-media">
                <div class="toggle-caret confirmation-media active dib mr5"></div>
                <span class='removable-title'>Selected media</span>
              </div>
              <div class="hide confirmation-media active">
                <div class="ul-limit-7 mb10">
                  <div class="p10 removable-descr">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad alias aliquid et eum fugiat fugit iure minima neque qui. Cupiditate,
                    enim explicabo ipsam minima nulla odit reprehenderit sit ullam veritatis?
                  </div>
                </div>
              </div>
              <div class="mb10 mt20 tac">
                <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-grey mr5" data-dismiss="modal" value="Cancel" />
                <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-green remove-media" value="Delete" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script id="tmpl-target-folders" type="text/x-handlebars-template">
  <div class="mb10">
    <div class="fwb">To folder</div>
    <div class="target-folders ul-limit-7">
      @{{#each targets}}
        <div>
          <label class="checkbox-default mr5">
            <input
              type="checkbox"
              name="targets[]"
              value="@{{id}}"
              id="target_folder_@{{id}}"
              @{{#if selected}}
                checked="checked"
              @{{/if}}
            />
            <span class="ico-checkbox"></span>
          </label>
          <label class="folder-name" for="target_folder_@{{id}}"><span class="name">@{{{name}}}</span></label>
        </div>
        @{{#ifCond @index '<' ../targets.length modifier=-1}}
          <hr class="hr-item" />
        @{{/ifCond}}
      @{{/each}}
    </div>
  </div>
</script>

<script id="tmpl-source-media" type="text/x-handlebars-template">
  <div class="ul-limit-7 mb10 selected-folders-media only-media">
    <ul>
    @{{#each media}}
      <li class="p5">
        @{{{title}}}
        <input type="hidden" name="media[]" value="@{{id}}" />
      </li>
    @{{/each}}
    </ul>
  </div>
</script>

<script id="tmpl-source-folders" type="text/x-handlebars-template">
  <div class="ul-limit-7 mb10 selected-folders-media">
    <ul>
    @{{#each collections}}
      <li class="p5">
        <div class="fwb cp mb10" data-toggle="collapse" toggle-target="#toggle-folder-@{{id}}" data-target="#collapse-folder-@{{id}}" aria-expanded="true">
          <div id="toggle-folder-@{{id}}" class="toggle-caret dib mr5 active" /> @{{{name}}}
        </div>
        <ul id="collapse-folder-@{{id}}" class="panel-collapse collapse in folder-selection">
          @{{#ifCond media.length '>' 0}}
            @{{#each media}}
              @{{#ifSelfMedia this}}
                <input type="hidden" name="collections[@{{../id}}][]" value="self" />
              @{{else}}
                <li class="p5">
                  @{{{title}}}
                  <input type="hidden" name="collections[@{{../id}}][]" value="@{{id}}" />
                </li>
              @{{/ifSelfMedia}}
            @{{/each}}
          @{{else}}
            <li class="p5 no-media">
              No Media Present
              <input type="hidden" name="collections[@{{id}}][]" value="self" />
            </li>
          @{{/ifCond}}
        </ul>
      </li>
    @{{/each}}
    </ul>
  </div>
</script>

<script id="tmpl-source-ucode" type="text/x-handlebars-template">
  <div class="ul-limit-7 mb10 selected-folders-media">
    <ul>
    @{{#each ucodes}}
      <li class="p5">
        <div class="fwb cp mb10" data-toggle="collapse" toggle-target="#toggle-folder-@{{id}}" data-target="#collapse-folder-@{{id}}" aria-expanded="true">
          <div id="toggle-folder-@{{id}}" class="toggle-caret dib mr5 active" /> @{{{name}}}
        </div>
        <ul id="collapse-folder-@{{id}}" class="panel-collapse collapse in folder-selection">
          @{{#ifCond media.length '>' 0}}
            @{{#each media}}
              @{{#ifSelfMedia this}}
                <input type="hidden" name="ucodes[@{{../name}}][]" value="self" />
              @{{else}}
                <li class="p5">
                  @{{{title}}}
                  <input type="hidden" name="media[]" value="@{{id}}" />
                </li>
              @{{/ifSelfMedia}}
            @{{/each}}
          @{{else}}
            <li class="p5 no-media">
              No Media Present
              <input type="hidden" name="ucodes[@{{name}}][]" value="self" />
            </li>
          @{{/ifCond}}
        </ul>
      </li>
    @{{/each}}
    </ul>
  </div>
</script>

<script id="tmpl-modal-duplicate-media" type="text/x-handlebars-template">
  <h3>Duplicate Media</h3>
  <form action="{{ url('collection') }}" method="POST">
    <div class="row">
      <div class="col-xs-12">
        <p>The Media was already submitted by another user (<b>@{{screenName}}</b>)</p>
        <p>Would you like to save it to your collections?</p>
      </div>
    </div>
    <div class="fwb">@{{defVal mediaLabel 'Media'}}</div>
    <div class="row duplicate-media mb10">
      <div class="col-xs-12 column-box">
        <div class="col-xs-6">
          @{{{duplicate.thumbnail}}}
        </div>
        <div class="col-xs-6">
          <p>
            <a href="@{{duplicate.url}}" target="_blank" class="media-title">@{{{duplicate.title}}}</a>
          </p>
          <div class="txt-teal dib">@{{screenName}}</div> - @{{duplicate.type}}
          <ul class="listing clearfix">
            <li class="tooltip" data-toggle="popover"  data-content="Likes">
              <i class="fa fa-thumbs-up"></i>@{{duplicate.likePercent}}%
            </li>
            <li class="tooltip" data-toggle="popover" data-content="Times Collected">
              <i class="fa fa-list-ul"></i>@{{duplicate.countCollected}}
            </li>
            <li class="calendar tooltip pull-right">@{{ duplicate.createdAt }}</li>
          </ul>
        </div>
      </div>
    </div>
      @{{> targetFolders }}
      <div class="mt20">
        <button type="button" class="el-btn el-btn-padding-md el-btn-grey full create-folder"><i class="fa fa-folder mr10"></i>Create new folder</button>
      </div>
    <div class="mb10 mt40 tac">
      <input type="button" data-dismiss="modal" class="el-btn el-btn-lg el-btn-padding-md el-btn-grey mr5" value="Cancel" />
      <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-green" value="@{{defVal submitLabel 'Save'}}" />
    </div>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PATCH" />
    <input type="hidden" name="action" value="copy" />
    <input type="hidden" name="media[]" value="@{{mediumId}}" />
    <input type="hidden" name="redirectURL" value="/contribute" />
  </form>
</script>

<script id="tmpl-modal-action-to" type="text/x-handlebars-template">
  <h3>@{{{title}}}</h3>
  <form action="{{ url('collection') }}" method="POST" id="form-action-to">
    {{ csrf_field() }}
    @{{#if form.method}}
      <input type="hidden" name="_method" value="@{{form.method}}" />
    @{{/if}}
    @{{#if form.action}}
      <input type="hidden" name="action" value="@{{form.action}}" />
    @{{/if}}
    <div class="mb20 @{{delete}}">
      <div class="fwb">@{{defVal mediaLabel 'Media'}}</div>
      @{{#if media }}
        @{{> sourceMedia }}
      @{{else if collections }}
        @{{> sourceFolders }}
      @{{else if ucodes }}
        @{{> ucodeData }}
      @{{/if}}
       
    </div>
    @{{#if targets }}
      @{{> targetFolders }}
    @{{/if}}
    @{{#if canCreateFolder }}
      <div class="mt20">
        <button type="button" class="el-btn el-btn-padding-md el-btn-grey full create-folder"><i class="fa fa-folder mr10"></i>Create new folder</button>
      </div>
    @{{/if}}
    <div class="mb10 mt40 tac">
      <input type="button" data-dismiss="modal" class="el-btn el-btn-lg el-btn-padding-md el-btn-grey mr5" value="Cancel" />
      <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-green" value="@{{defVal submitLabel 'Save'}}" />
    </div>
  </form>
</script>

<script id="tmpl-modal-ucode-action-to" type="text/x-handlebars-template">
  <h3>@{{{title}}}</h3>
  <form action="@{{form.url}}" method="POST" id="form-action-to">
    {{ csrf_field() }}
    @{{#if form.method}}
      <input type="hidden" name="_method" value="@{{form.method}}" />
    @{{/if}}
    @{{#if form.action}}
      <input type="hidden" name="action" value="@{{form.action}}" />
    @{{/if}}
    <div class="mb20 @{{delete}}">
      <div class="fwb">@{{defVal mediaLabel 'Media'}}</div>
      @{{#if media }}
        @{{> sourceMedia }}
      @{{else if ucodes }}
        @{{> ucodeData }}
      @{{/if}}
    </div>
    @{{#if targets }}
      @{{> targetFolders }}
    @{{/if}}
    @{{#if canCreateFolder }}
      <div class="mt20">
        <button type="button" class="el-btn el-btn-padding-md el-btn-grey full create-folder"><i class="fa fa-folder mr10"></i>Create new folder</button>
      </div>
    @{{/if}}
    <div class="mb10 mt40 tac">
      <input type="button" data-dismiss="modal" class="el-btn el-btn-lg el-btn-padding-md el-btn-grey mr5" value="Cancel" />
      <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-green" value="@{{defVal submitLabel 'Save'}}" />
    </div>
  </form>
</script>

<script id="tmpl-form-folder" type="text/x-handlebars-template">
  <form action="@{{form.url}}" method="POST" data-toggle="validator" id="form-folder" >
    @{{#if form.method}}
      <input type="hidden" name="_method" value="@{{form.method}}" />
    @{{/if}}
    <div>
      <div class="form-group">
        <div class="mb10">
          <label for="folder_name">Folder name</label>
          <input
            id="folder_name"
            value="@{{{name}}}"
            type="text"
            class="form-control"
            placeholder="Folder name"
            name="name"
            required
            data-minlength="3"
            maxlength="150"
            data-minlength-error="Please enter minimum 3 characters"
            data-error="Please enter folder name"
            @{{#if id}}
              data-remote="{{ url('collection/exists?_token='.csrf_token()) }}&id=@{{id}}"
            @{{else}}
              data-remote="{{ url('collection/exists?_token='.csrf_token()) }}"
            @{{/if}}
            data-remote-error="Folder already exists"
          />
          <div class="help-block with-errors"></div>
        </div>
      </div>
      <div class="form-group">
        <div class="mb10">
          <label for="folder_description">Folder description</label>
          <textarea
            id="folder_description"
            name="description"
            class="form-control resize-none"
            cols="30"
            rows="6"
            maxlength="1000"
            placeholder="Folder description"
          >@{{description}}</textarea>
        </div>
      </div>
      <div class="mb10 mt40 tac">
        @{{#if id}}
          <input type="hidden" name="id" value="@{{id}}" />
        @{{/if}}
        <input type="button" data-dismiss="modal" class="el-btn el-btn-lg el-btn-padding-md el-btn-grey mr5" value="Cancel" />
        <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-green" value="@{{defVal submitLabel 'Save'}}" />
      </div>
    </div>
  {{ csrf_field() }}
  </form>
</script>

<script id="tmpl-modal-folder" type="text/x-handlebars-template">
  <h3>
    @{{#if showBack}}
      <i class="fa fa-chevron-left btn-back" data-dismiss="modal" aria-hidden="true"></i>&nbsp;&nbsp;
    @{{/if}}
    @{{{title}}}
  </h3>
  <div class="content">
    @{{> formFolder }}
  </div>
</script>

<script id="tmpl-modal-empty-selection" type="text/x-handlebars-template">
  <h3 class="no-media">No Media Selected</h3>
  <p>Please select any media or folder with atleast one media</p>
</script>

<style>
  .folder-selection .no-media, h3.no-media { color: red; }
  .ul-limit-7.selected-folders-media { max-height: 130px; }
  .delete .ul-limit-7.selected-folders-media { max-height: 290px; }
  .ul-limit-7.selected-folders-media.only-media > ul > li { list-style-type: disc; }
  .ul-limit-7.target-folders { max-height: 161px; }
  .target-folders > div { margin-left: 10px; }
  .target-folders .hr-item { border-color: #dcdcdc; margin-left: 10px; margin-right: 10px; }
  .ul-limit-7 { padding-top: 10px; padding-bottom: 10px; }
  .btn-back { cursor: pointer; }
  .folder-selection li { padding-top: 2px; padding-bottom: 2px; }
  .row.duplicate-media { background: #dcdcdc; margin-left: 0; margin-right: 0; padding-top: 10px; }
  .duplicate-media .video-wrap { margin-top: 0; }
  .target-folders hr { margin-top: 5px; margin-bottom: 5px; }
  .target-folders label.folder-name { margin-bottom: 0; }
  .folder-selection { margin-bottom: 10; }
  .selected-folders-media [data-toggle=collapse] { margin-bottom: 0; }
  .selected-folders-media [data-toggle=collapse].collapsed { margin-bottom: 10; }
</style>

<script>
  window.URL_COLLECTION = "{{ url('collection') }}";
  window.URL_UCODES = "{{ url('bundle') }}";
</script>
<script src="{{ URL::asset('resources/assets/js/modal.js') }}"></script>
