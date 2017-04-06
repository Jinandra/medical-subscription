{{--
  -- PARAMS:
  -- $formAction => form action
  -- $media => media with array style access to property
  --}}

<?php
  function styleError($errors, $field) {
    return $errors->has($field) ? 'style="color: red;"' : '';
  }
  function oldOrObject($field, $media) {
    return is_null(old($field)) ? $media[$field] : old($field);
  }
  
  $dragndropText1 = 'Drag and drop into box';
  $dragndropText2 = 'Or click to browse';
  $fileInputPlaceholder = 'Click here to browse for file';
  
  $createTypeText = 'Select supported media type';
  $editTypeText = 'Media type';
?>

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" >
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <div class="container spacer">
    <div class="row">
      <div class="col-xs-12 col-md-8 mb20">
        <div class="tab-content">
          <input type="hidden" id="create_type" name="create_type" value="{{ (isset($createType))?$createType:App\Models\Media::CREATE_TYPE_ONLINE }}">
          @if(($media['id'] && isset($createType) && $createType == App\Models\Media::CREATE_TYPE_ONLINE) || !$media['id'])
            <div class="tab-pane {{ (!isset($createType) || $createType == App\Models\Media::CREATE_TYPE_ONLINE)?'active':'' }}" id="{{ App\Models\Media::CREATE_TYPE_ONLINE }}">
              <input type="hidden" class="create_type" value="{{ App\Models\Media::CREATE_TYPE_ONLINE }}">
              <div class="row mb20">
                <div class="col-md-8">
                  <div class="fwb mb5">
                    {{ $media['id']?$editTypeText:$createTypeText }}
                  </div>
                  @foreach(App\Models\Media::getAllOnlineTypes() as $type=>$text)
                    @if(($media['id'] && $media['type'] == $type) || !$media['id'])
                      <div>
                        <label class="el-selected-highlight {{ (oldOrObject('type', $media) == $type)?'active' : '' }}">
                          <div class="el-radiobtn mr5">
                            <input type="radio" class="required-input" required name="type" value="{{ $type }}" {{ (oldOrObject('type', $media) == $type)?'checked' : '' }}>
                          </div>
                          {{ $text }}
                        </label>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
              <div class="row mb20">
                <div class="col-xs-12">
                  <label class="fwb mb5" for="web_link">{{ $media['id']?'Media URL':'Add Media URL' }}</label>
                  <input type="url" {{ (!isset($createType) || $createType == App\Models\Media::CREATE_TYPE_ONLINE)?'required':'' }} 
                         onBlur="normalizeURL(this);" value="{{ oldOrObject('web_link', $media) }}" 
                         class="form-control required-input" name="web_link" id="web_link" placeholder="http://..."
                         {{ ($media['id'])?'disabled':'' }} />
                </div>
              </div>
            </div>
          @endif
          @if(($media['id'] && isset($createType) && $createType == App\Models\Media::CREATE_TYPE_UPLOAD) || !$media['id'])
            <div class="tab-pane {{ (isset($createType) && $createType == App\Models\Media::CREATE_TYPE_UPLOAD)?'active':'' }}" id="{{ App\Models\Media::CREATE_TYPE_UPLOAD }}">
              <input type="hidden" class="create_type" value="{{ App\Models\Media::CREATE_TYPE_UPLOAD }}">
              <div class="row mb20">
                <div class="col-md-6">
                  <div class="fwb mb5">
                    {{ $media['id']?$editTypeText:$createTypeText }}
                  </div>
                  @foreach(App\Models\Media::getAllUploadTypes() as $type=>$text)
                    @if(($media['id'] && $media['type'] == $type) || !$media['id'])
                      <div>
                        <label class="el-selected-highlight {{ (oldOrObject('type', $media) == $type)?'active' : '' }}">
                          <div class="el-radiobtn mr5">
                            <input type="radio" class="required-input" name="type" value="{{ $type }}" {{ (oldOrObject('type', $media) == $type)?'checked' : '' }}>
                          </div>
                          {{ $text }}
                        </label>
                      </div>
                    @endif
                  @endforeach
                </div>
                <div class="col-md-6" data-toggle="tooltip" data-placement="bottom" title="{{ round(Auth::user()->getMediaUsedSize() / App\Models\User::ONE_MB, 2) }}MB/{{ round(App\Models\User::MEDIA_TOTAL_SIZE / App\Models\User::ONE_MB, 1) }}MB Used">
                  <label class="fwb pull-left form-control-static ml10 mr10">Your upload space</label>
                  <div class="panel p5 mb0">
                    <div class="progress mb0">
                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"
                           style="width: {{ Auth::user()->getMediaUsedSizePersentage() }}%" data-toggle="tooltip" data-placement="bottom">
                        <span class="sr-only">{{ Auth::user()->getMediaUsedSizePersentage() }}%</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @if(!$media['id'])
                <div class="row mb20">
                  <div class="col-xs-12">
                    <label class="fwb mb5" for="upload-input">Upload Media</label>
                    <label for="upload" class="upload-file">
                      <span class="upload-file-text txt-teal">
                          {{ $dragndropText1 }} <br /> {{ $dragndropText2 }}
                      </span>
                      <input type="file" name="media" id="upload-input">
                    </label>
    <!--                <input type="file" name="media" id="upload-input" style="display: none;">
                    <label class="fwb mb5" for="file-input">Upload Media</label>-->
                    <!--<input type="text" class="form-control" id="file-input" placeholder="{{ $fileInputPlaceholder }}"/>-->
                  </div>
                </div>
              @endif
            </div>
          @endif
          @if(($media['id'] && isset($createType) && $createType == App\Models\Media::CREATE_TYPE_CREATE) || !$media['id'])
            <div class="tab-pane {{ (isset($createType) && $createType == App\Models\Media::CREATE_TYPE_CREATE)?'active':'' }}" id="{{ App\Models\Media::CREATE_TYPE_CREATE }}">
              <input type="hidden" class="create_type" value="{{ App\Models\Media::CREATE_TYPE_CREATE }}">
            </div>
          @endif
        </div>
        <div class="row mb20">
          <div class="col-xs-12">
            <label class="fwb mb5" for="title">Title for your media*</label>
            <input type="text" required maxlength="100" value="{{ oldOrObject('title', $media) }}" class="form-control" name="title" id="title" placeholder="Enter your title here..." />
          </div>
        </div>
        <ul class="nav nav-tabs mb10">
          <li role="presentation" el-toggle="description" el-toggle-target="#advanced-description" el-toggle-state="false" class="active">
            <a href="javascript:void(0)">Simple Description</a>
          </li>
          <li role="presentation" el-toggle="description" el-toggle-target="#advanced-description" el-toggle-state="true">
            <a href="javascript:void(0)">Advanced</a>
          </li>
        </ul>
        <div class="row mb20">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <textarea required class="form-control resize-none" rows="5" id="description" name="description" placeholder="Type description here...">{{ oldOrObject('description', $media) }}</textarea>
          </div>
        </div>
        
        <div class="row mb20 hidden" id="advanced-description">
          <div class="col-xs-12 mb20">
            <div class="fwb mb5">
              Media Source
            </div>
            <div>
              @foreach(App\Models\Media::getAllSources() as $source => $text)
                <label class="mr20 el-selected-highlight {{ (oldOrObject('source', $media) == $source)?'active':'' }}">
                  <div class="el-radiobtn mr5">
                    <input type="radio" name="source" value="{{ $source }}" {{ (oldOrObject('source', $media) == $source)?'checked':'' }}>
                  </div>
                  {{ $text }}
                </label>
              @endforeach
            </div>
          </div>
          <div class="col-xs-12 mb20">
            <div class="fwb mb5">
              Your Level of Expertise on Media's Topic
            </div>
            <div>
              @foreach(App\Models\Media::getAllExpertiseLevels() as $level => $text)
                <label class="mr20 el-selected-highlight {{ (oldOrObject('user_expertise', $media) == $level)?'active':'' }}">
                  <div class="el-radiobtn mr5">
                    <input type="radio" name="user_expertise"  value="{{ $level }}" {{ (oldOrObject('user_expertise', $media) == $level)?'checked':'' }}>
                  </div>
                  {{ $text }}
                </label>
              @endforeach
            </div>
          </div>
          <div class="col-xs-12 mb20">
            <label class="fwb mb5">Target Audience for Media</label>
            <div>
              <?php $mediaAudiences = unserialize($media['target_audience']); ?>
              <?php $idx = 1; ?>
              @foreach(App\Models\Media::getAllAudiences() as $audience => $text)
                <?php $audienceIsActive = ((!is_null(old('target_audience')) && is_array(old('target_audience')) && in_array($audience, old('target_audience')))
                                        || (is_null(old('target_audience')) && is_array($mediaAudiences) && in_array($audience, $mediaAudiences)))?true:false; ?>
                <div class="dib mr20 el-selected-highlight {{ ($audienceIsActive)?'active':'' }}">
                  <label class="checkbox-default mr5">
                    <input id="target_audience_{{ $idx }}" type="checkbox" name="target_audience[]" value="{{ $audience }}" {{ ($audienceIsActive)?'checked':'' }}>
                    <span class="ico-checkbox"></span>
                  </label>
                  <label for="target_audience_{{ $idx }}">{{ $text }}</label>
                </div>
                <?php $idx += 1; ?>
              @endforeach
            </div>
          </div>
          <?php $locationActive = (!empty(oldOrObject('state_id', $media)) || !empty(oldOrObject('city', $media)) || !empty(oldOrObject('area', $media))); ?>
          <div class="col-xs-12">
            <label class="fwb mb5">Is your media specific for any location?</label>
            <div class="mb20">
              <div class="country-switch el-btn el-btn-grey {{ ($locationActive)?'':'active' }}" el-toggle="location" el-toggle-target="#spesific-location" el-toggle-state="false">No</div>
               <div class="country-switch el-btn el-btn-grey {{ ($locationActive)?'active':'' }}" el-toggle="location" el-toggle-target="#spesific-location" el-toggle-state="true">Yes</div>
            </div>
          </div>
          <div id="spesific-location" class="{{ ($locationActive)?'':'hidden' }}">
<!--                <div class="col-md-3 col-reset mb20">
                <input type="text" class="form-control" placeholder="Country">
            </div>-->
            <div class="col-md-4 col-reset mb20">
              <select name="state_id" class="form-control mr10 dib">
                <option value="">Select State</option>
                @foreach($states as $state)
                  <option value="{{ $state->id }}" {{ (oldOrObject('state_id', $media) == $state->id)?'selected':'' }}>{{ $state->short_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4 col-reset mb20">
              <input type="text" name="city" class="form-control" placeholder="City" value="{{ oldOrObject('city', $media) }}">
            </div>
            <div class="col-md-4 col-reset mb20">
              <input type="text" name="area" class="form-control" placeholder="Area"  value="{{ oldOrObject('area', $media) }}">
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-6">
            <label class="fwb mb5 db">Language</label>
            <select name="language" id="language" class="form-control mr10 dib" style="max-width: 170px">
              <option value="english">English</option>
            </select>
            <div class="dib el-selected-highlight {{ (oldOrObject('caption_available', $media) == App\Models\Media::CAPTION_AVAILABLE)?'active':'' }}">
              <label class="checkbox-default mr5">
                <input id="caption_available" type="checkbox" name="caption_available" value="{{ App\Models\Media::CAPTION_AVAILABLE }}" {{ (oldOrObject('caption_available', $media) == App\Models\Media::CAPTION_AVAILABLE)?'checked':'' }}>
                <span class="ico-checkbox"></span>
              </label>
              <label for="caption_available">Caption Available</label>
            </div>
          </div>
          <div class="col-md-6">
            <label class="fwb mb5" for="tag">Add Tags seperated by comma - Optional</label>
            <input type="text" class="form-control" name="tags" id="tag" placeholder="Type your tags (eg: Tag1, Tag2, Tag3)" value="{{ oldOrObject('tags', $media) }}">
          </div>
        </div>
      
        <div class="row mb20">
          <div class="col-md-12">
            <label class="fwb mb5">Category*</label>
            <div class="form-control-box fz14">
              <div class="row row-md-gutter table-td-top">
                <div class="col-md-12">
                  <table class="full">
                    <tbody>
                      <tr>
                        <?php
                        $countCategories = $categories->count();
                        $count1 = $count2 = floor($countCategories/3);
                        $residue = $countCategories % 3;
                        if($residue) {
                            $count1 += 1;
                            if($residue == 2) {
                                $count2 += 1;
                            }
                        }
                        ?>
                        @for($i = 0; $i < $countCategories; $i++)
                          @if($i == 0 || $i == $count1 || $i == ($count1 + $count2))
                          <td>
                          @endif
                            <div>
                              <label class="checkbox-default mr5" for="category-{{ $categories[$i]->id }}">
                                <input id="category-{{ $categories[$i]->id }}" type="checkbox" name="categories[]" value="{{ $categories[$i]->id }}"
                                  @if(oldOrObject('categories', $media)) 
                                    @if(in_array($categories[$i]->id, oldOrObject('categories', $media))) 
                                      checked
                                    @endif
                                  @elseif (isset($media['categories']) && is_array($media['categories']))
                                    @if (in_array($categories[$i]->id, $media['categories']))
                                      checked
                                    @endif
                                  @endif
                                />
                                <span class="ico-checkbox"></span>
                              </label><label for="category-{{ $categories[$i]->id }}">{{ $categories[$i]->name }}</label>
                            </div>
                          @if($i == ($count1 - 1) || $i == ($count2 - 1) || $i == ($countCategories - 1))
                          </td>
                          @endif
                        @endfor
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-5">
            <label class="fwb mb5">Add to collection</label>
            <div class="form-control-box">
              <button type="button" class="el-btn el-btn-sm el-btn-primary full" id="btnCreateFolder">
                <i class="fa fa-plus mr10"></i>Create new folder
              </button>
              <hr class="mt10 mb10">
              <div class="select-collection-list-box">
                @foreach ($collections as $collection)
                <div class="collection-item">
                    <label class="checkbox-default mr5" for="collection-{{ $collection->id }}">
                        <input id="collection-{{ $collection->id }}" type="checkbox" name="collections[]" value="{{ $collection->id }}"
                          @if(oldOrObject('collections', $media))
                            @if(in_array($collection->id, oldOrObject('collections', $media))) 
                              checked
                            @endif
                          @elseif (isset($media['collections']) && is_array($media['collections']))
                            @if (in_array($collection->id, $media['collections']))
                              checked
                            @endif
                          @endif
                        />
                        <span class="ico-checkbox"></span>
                    </label>
                    <label for="collection-{{ $collection->id }}">{{ $collection->name }}</label>
                </div>
                @endforeach
              </div>
            </div>
          </div>
          <input name="private" type="hidden" value="0" />

      {{--
        --<div class="row">
        --  <div class="col-sm-5 col-md-5 col-xs-12">
        --    <div style="margin: 2em 0;">
        --      <label for="visibilityPublic" style="margin-right: 2em; text-decoration: underline;">Publishing</label>
        --      <label class="radio-inline" for="visibilityPrivate">
        --        <input type="radio" id="visibilityPrivate" name="private" value="1" {{ oldOrObject('private', $media) == '1' ? 'checked' : '' }}>Private
        --      </labeli>
        --      <label class="radio-inline" style="margin-left: 20px;">
        --        <input type="radio" id="visibilityPublic" name="private" value="0" {{ oldOrObject('private', $media) == '0' || is_null(oldOrObject('private', $media)) ? 'checked' : '' }}>Public
        --      </label>
        --    </div>
        --  </div>
        --</div>
        --}}
        </div>
        <div class="row mt40">
          <div class="col-sm-12 spacer tac">
            <input type="reset" class="el-btn el-btn-lg el-btn-padding-md el-btn-grey mr5" value="Cancel" />
            <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-green" value="Submit" />
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<script>
  function normalizeURL (url) {
    var string = url.value;
    if (string.trim() !== '') {
      if (string.match(/^(http|https):\/\//) === null) {
        string = 'http://' + string;
      }
      if (string.match(/\..{2,}$/) === null) {
        string += '.com';
      }
    }
    url.value = string;
    return url;
  }
  function onCreatedFolder (newFolder) {
    var folderBlock =
      '<div class="collection-item">\n\
        <label class="checkbox-default mr5" for="collection-'+newFolder.id+'">\n\
          <input id="collection-'+newFolder.id+'" type="checkbox" name="collections[]" checked="checked" value="' + newFolder.id + '">\n\
          <span class="ico-checkbox"></span>\n\
        </label>\n\
        <label for="collection-'+newFolder.id+'">'+ newFolder.name + '</label>\n\
      </div>';
      $('.select-collection-list-box').prepend(folderBlock);
  }
  
  var text1 = '{{ $dragndropText1 }}';
  var text2 = '{{ $dragndropText2 }}';
  var dropText = 'Drop media';
  var fileName;
  
  $(document).ready(function () {
    $('input[type=reset]').on('click', function (e) {
//      if ( !confirm('Are you sure do you want to reset form data?') ) {
//        e.preventDefault();
//      }
      window.location = "{{ url('contribute') }}";
    });
    $('.save-type').change(function() {
      console.log($(this).val());
      if($(this).val() == 'file') {
        $('#web_link').addClass('hidden');
        $('#web_link').removeAttr('required');
        $('#media_file').removeClass('hidden');
        $('#media_file_name').removeClass('hidden');
      } else if($(this).val() == 'url') {
        $('#web_link').removeClass('hidden');
        $('#web_link').attr('required', 'required');
        $('#media_file').addClass('hidden');
        $('#media_file_name').addClass('hidden');
      }
    });
    
    $('#btnCreateFolder').on('click', function (e) {
      ENFOLINK.modal.showCreateFolder(onCreatedFolder);
    });
    var fileInput = $('.upload-file-text');
    //var fileInput = $('#file-input');
    var file = $('#upload-input');

    fileInput.on('click', function (e) {
      $(this).next().trigger('click');
//      file.trigger('click');
    });
    
    file.on('change', function (e) {
  //    var activeText = fileInput.text();
      if (e.target.value) {
        fileInput.text(e.target.value.replace(/C:\\fakepath\\/i, ''))
      } else {
        fileInput.text('');
        fileInput.append(text1);
        fileInput.append('<br />');
        fileInput.append(text2);
      }
      fileInput.blur();
    });
    
    fileInput.on(
      'dragover',
      function(e) {
        $(this).addClass('active');
        e.preventDefault();
        e.stopPropagation();
      }
    );
    file.on(
      'dragover',
      function(e) {
        fileInput.addClass('active');
        fileInput.text(dropText);
        e.preventDefault();
        e.stopPropagation();
      }
    );

    fileInput.on(
      'mouseleave',
      function(e) {
        $(this).removeClass('active');
        e.preventDefault();
        e.stopPropagation();
      }
    );
    file.on(
      'mouseleave',
      function(e) {
        mouseOrDragLeave(e);
      }
    );
    file.on(
      'dragleave',
      function(e) {
        mouseOrDragLeave(e);
      }
    );
    function mouseOrDragLeave(e) {
      fileInput.removeClass('active');
      if(fileInput.text() == dropText) {
        if(typeof fileName == 'undefined') {
          fileInput.text('');
          fileInput.append(text1);
          fileInput.append('<br />');
          fileInput.append(text2);
        } else {
          fileInput.text(fileName);
        }
      }
      e.preventDefault();
      e.stopPropagation();
    }

    fileInput.on(
      'dragenter',
      function(e) {
        e.preventDefault();
        e.stopPropagation();
      }
    );

    fileInput.on(
      'drop',
      function(e){
        if(e.originalEvent.dataTransfer){
          if(e.originalEvent.dataTransfer.files.length) {
            e.preventDefault();
            e.stopPropagation();
            fileInput.text(e.originalEvent.dataTransfer.files[0].name)
          }
        }
      }
    );

    $('[el-toggle]').on('click', function () {
      var type = $(this).attr('el-toggle');
      var $typeEl = $('[el-toggle="' + type + '"]');

      var target = $(this).attr('el-toggle-target');
      var $targetEl = $(target);

      var state = $(this).attr('el-toggle-state');

      $typeEl.each(function (e) {
          $(this).removeClass('active')
      });
      $(this).addClass('active');

      if (state == 'true') {
        $targetEl.removeClass('hidden')
      } else {
        $targetEl.addClass('hidden')
      }
    });

    $(document).on('click', '.el-selected-highlight input', function() {
      const name = $(this).attr('name');
      const checked = $(this).is(':checked');
      const type = $(this).attr('type');
      if (type == 'radio') {
        $('input[name='+ name +']').each(function(){
          $(this).parents('.el-selected-highlight').removeClass('active');
        });
      }
      if(checked) {
        $(this).parents('.el-selected-highlight').addClass('active');
      } else {
        $(this).parents('.el-selected-highlight').removeClass('active');
      }
    });
    $('body').on('click', '.media-type', function() {
      var typeBlockId = $(this).find('.media-type-link').attr('href').replace('#', '');
      $('#create_type').val(typeBlockId);
      $('.required-input').removeAttr('required');
      $('#' + typeBlockId).find('.required-input').attr('required', 'required');
    });
  });
  
  var imageLoader = document.getElementById('upload-input');
  if(imageLoader != null) {
    imageLoader.addEventListener('change', handleImage, false);
  }

  function handleImage(e) {
    if(e.target.files[0]) {
      fileName = e.target.files[0].name;
      var reader = new FileReader();
      reader.onload = function (event) {
      
      }
      reader.readAsDataURL(e.target.files[0]);
    }
  }
</script>
<style type="text/css">
    .error {
        color: #a94442;
    }
    /* Upload file */

    .upload-file {
        width: 100%;
        overflow: hidden;
    }

    .upload-file-text {
        display: block;
        width: 100%;
        padding: 30px 50px;
        text-align: center;
        border: 2px dashed #bdbdbd;
        background: #eee;
        cursor: pointer;
    }

    .upload-file-text.active {
        /*background: #17baa3;*/
        background: #eee;
        border: 3px dashed #17baa3;
    }

    .upload-file input{
/*        position: absolute;
        opacity: 0;
        display: none;*/
        position: absolute;
        opacity: 0;
        display: block;
        height: 100%;
        width: 100%;
        top: 0;
        cursor: pointer;
    }
    
    .country-switch {
        cursor: pointer;
    }
    
    .states-select {
        width: 100%;
    }
    
    .el-selected-highlight.active {
        color: #009688;
    }
</style>
