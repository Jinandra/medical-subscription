{{--
  -- Show invite form
  --}}
<div class="invite">
  <form>
    <div class="row mb20 mt20">
      <div class="col-xs-12">
        <ul class="intro fz13">
          <li>Invite colleagues and help them get started right away with media and folders from your collection.</li>
          <li>Your colleague will be sent to a special sign-up page and will automatically be approved without delay.</li>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label class="fwbold mb0">Colleague's Email Addres</label>
        <input placeholder="You can add multiple by pressing Enter key" type="email" data-role="multiemail" class="form-control" class="mb0" />
        <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
      </div>
    </div>
    <div class="row mt40 mb20">
      <div class="col-xs-12">
        <h3 class="fz15" data-toggle="collapse" data-target="#collapsePersonalMessage" aria-expanded="false" aria-controls="collapsePersonalMessage">
          <input type="checkbox" id="personal_message" />
          <label for="personal_message">Add a message to your invitation</label>
        </h3>
        <div class="collapse" id="collapsePersonalMessage">
          <textarea class="form-control" name="personal_message" maxlength="1000" rows="8"></textarea>
        </div>
      </div>
    </div>
    <div class="row mt20 mb20">
      <div class="col-xs-12 suggest">
        <h3 class="fz15" data-toggle="collapse" data-target="#collapseSuggestMedia" aria-expanded="false" aria-controls="collapseSuggestMedia">
          <input type="checkbox" id="suggest_media" />
          <label for="suggest_media">Suggest Media</label>
        </h3>
        <div class="collapse" id="collapseSuggestMedia">
          <p class="fz13">Add your suggested media to your colleague's account, they will see it in a folder called "{{ Auth::user()->first_name }}'s Media"</p>
          <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#media-saved" aria-controls="media-saved" role="tab" data-toggle="tab">Saved Media</a></li>
            <li role="presentation"><a href="#media-contributed" aria-controls="media-contributed" role="tab" data-toggle="tab">Contributed</a></li>
            <li role="presentation"><a href="#media-bookmarked" aria-controls="media-bookmarked" role="tab" data-toggle="tab">Bookmarked</a></li>
            <li role="presentation"><a href="#media-liked" aria-controls="media-liked" role="tab" data-toggle="tab">Liked</a></li>
          </ul>
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="media-saved">
              @include('beta.user.invite_media', [
                'media' => Auth::user()->savedMedia()->sortBy('title'),
                'prefixId' => 'saved-media'
              ])
            </div>
            <div role="tabpanel" class="tab-pane fade in" id="media-contributed">
              @include('beta.user.invite_media', [
                'media' => Auth::user()->media->sortBy('title'),
                'prefixId' => 'contributed-media'
              ])
            </div>
            <div role="tabpanel" class="tab-pane fade in" id="media-bookmarked">
              @include('beta.user.invite_media', [
                'media' => Auth::user()->bookmarkedMedia
                           ->map(function ($b) { return $b->media; })
                           ->filter(function ($media) { return !is_null($media); })
                           ->sortBy('title'),
                'prefixId' => 'bookmarked-media'
              ])
            </div>
            <div role="tabpanel" class="tab-pane fade in" id="media-liked">
              @include('beta.user.invite_media', [
                'media' => Auth::user()->likedMedia
                           ->map(function ($l) { return $l->media; })
                           ->filter(function ($media) { return !is_null($media); })
                           ->sortBy('title'),
                'prefixId' => 'liked-media'
              ])
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt20 mb40">
      <div class="col-xs-12 suggest">
        <h3 class="fz15" data-toggle="collapse" data-target="#collapseSuggestFolders" aria-expanded="false" aria-controls="collapseSuggestFolders">
          <input type="checkbox" id="suggest_folders" />
          <label for="suggest_folders">Suggest Folder</label>
        </h3>
        <div class="collapse" id="collapseSuggestFolders">
          <p class="fz13">Your suggested folders will be placed into your colleague's collection under `Saved Folders`</p>
          <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#folder-saved" aria-controls="folder-saved" role="tab" data-toggle="tab">Saved</a></li>
            <li role="presentation"><a href="#folder-created" aria-controls="folder-created" role="tab" data-toggle="tab">Created</a></li>
          </ul>
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="folder-saved">
              @include('beta.user.invite_folder', [
                'folder' => Auth::user()->savedFolders(),
                'prefixId' => 'saved-folder'
              ])
            </div>
            <div role="tabpanel" class="tab-pane fade in" id="folder-created">
              @include('beta.user.invite_folder', [
                'folder' => Auth::user()->createdFolders(),
                'prefixId' => 'created-folders'
              ])
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt40 mb20">
      <div class="col-md-4">
        <a href="#" id="emailPreview">Preview email invitation</a>
      </div>
      <div class="col-md-8">
        <input type="reset" class="el-btn el-btn-lg el-btn-padding-md el-btn-grey mr5" value="Reset" />
        <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-green" value="Send" />
      </div>
    </div>
  </form>
</div>
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body form-popup">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
          aria-hidden="true">&times;</span></button>
        <div class="row">
          <div class="col-xs-12">
            <h3>Email Invitation</h3>
            <div class="container">
              <p class="mb30">
                Hello &lt;invited email&gt;,<br/>
                {{ Auth::user()->first_name }} &lt;{{ Auth::user()->email }}&gt; has sent you a free invitation to join <a target="_blank" href="{{ url('/') }}">Enfolink</a>.
              </p>
              <div class="personal-message mt30 mb30">
                Message from {{ Auth::user()->first_name }}:<br />
                <p class="content"></p>
              </div>
              <div class="suggested mt30 mb30">
                {{ Auth::user()->first_name }} has suggested some media for your patients to help you get started right away:<br />
                <div class="media">
                  <div><span class="totalShow">10</span>/<span class="totalAll">34</span> Suggested Media</div>
                  <ol>
                    <li><a href="#">Media 1</a></li>
                    <li><a href="#">Media 2</a></li>
                    <li><a href="#">Media 3</a></li>
                    <li><a href="#">Media 4</a></li>
                    <li><a href="#">Media 5</a></li>
                    <li><a href="#">Media 6</a></li>
                    <li><a href="#">Media 7</a></li>
                    <li><a href="#">Media 8</a></li>
                    <li><a href="#">Media 9</a></li>
                    <li><a href="#">Media 10</a></li>
                  </ol>
                </div>
                <div class="folders">
                  <div><span cass="totalShow">10</span>/<span class="totalAll">34</span> Suggested Folders</div>
                  <ol>
                    <li><a href="#">Folder 1</a></li>
                    <li><a href="#">Folder 2</a></li>
                    <li><a href="#">Folder 3</a></li>
                    <li><a href="#">Folder 4</a></li>
                    <li><a href="#">Folder 5</a></li>
                    <li><a href="#">Folder 6</a></li>
                    <li><a href="#">Folder 7</a></li>
                    <li><a href="#">Folder 8</a></li>
                    <li><a href="#">Folder 9</a></li>
                    <li><a href="#">Folder 10</a></li>
                  </ol>
                </div>
              </div>
              <p class="mt30 mb30">
                <a target="_blank" href="{{ url('/') }}">Enfolink</a> is a website which helps healthcare professionals optimize the delivery of education to their patients.<br/>
                You can learn more about it <a target="_blank" href="{{ url('/post') }}">here</a>.
              </p>
              <p class="mt30">
                Because {{ Auth::user()->first_name }} has invited you, there won't be any wait for approval - you can start using it right away!<br/>
                To sign up, click <a target="_blank" href="{{ url('/user/registration') }}">here</a> or copy and paste this address into your search bar {{ url('/user/registration') }}.
              </p>
              <p class="mt40">
                Thank You,<br/>
                Enfolink Team
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  .invite ul.intro { padding-left: 15px; }
  .suggest .tab-pane { background: white; padding: 18px; padding-bottom: 0; height: 272px; max-height: 274px; overflow-y: auto; border-left: 1px solid #ddd; border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; }
  .suggest .tab-pane li { list-style-type: none; }
  .suggest .tab-pane a { color: #424242; }
  .suggest .tab-pane a:hover { color: #149985; }
  .bootstrap-multiemail input { min-width: 280px; }
  h3 input[type=checkbox] { position: relative; top: 2px; margin-right: 4px; }
  #previewModal .container { overflow-y: auto; max-height: 500px; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/bootstrap-multiEmail.css') }}" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="{{ URL::asset('resources/assets/js/bootstrap-multiEmail.js') }}"></script>
<script>
$(function () {
  $('.invite form').submit(function(e) {
    e.preventDefault();
    alert('submit not yet implemented');
  });
  $('.invite input[type=reset]').click(function(e) {
    e.preventDefault();
    alert('reset not yet implemented');
  });
  $('#collapsePersonalMessage, #collapseSuggestMedia, #collapseSuggestFolders').on('shown.bs.collapse', function () {
    $(this).siblings('h3').find('input[type=checkbox]').prop('checked', true);
  });
  $('#collapsePersonalMessage, #collapseSuggestMedia, #collapseSuggestFolders').on('hidden.bs.collapse', function () {
    $(this).siblings('h3').find('input[type=checkbox]').prop('checked', false);
  });
  $('#emailPreview').click(function (e) {
    e.preventDefault();
    var $modal = $('#previewModal');

    (function () {  // personal message
      var personalMessage = $('textarea[name=personal_message]').val().trim();
      if (personalMessage === '') {
        $modal.find('.personal-message').hide();
      } else {
        $modal.find('.personal-message .content').html(personalMessage.replace(/\n/g, '<br/>'));
        $modal.find('.personal-message').show();
      }
    })();

    (function () {  // suggested folder / media
      var $suggested = $modal.find('.suggested');
      function getSuggesteds (type) {
        var all = $('input[name="'+type+'[]"]:checked')
          .map(function (_i, el) { return $(el).val() })
          .toArray()
          .reduce(function (array, id) {  // remove duplicate
            if (array.indexOf(id) === -1) {
              array.push(id);
            }
            return array;
          }, []);
        var show = all.sort(function () { return 0.5 - Math.random(); }).slice(0, 10);
        var objects = show.map(function (id) {
          var $anchor = $('input[name="'+type+'[]"][value='+id+']').parents('li').find('a');
          return {
            title: $anchor.attr('title'),
            href: $anchor.attr('href')
          };
        });
        return { all: all, show: show, objects: objects };
      }
      function showSuggesteds (type, data) {
        var $container = $suggested.find('.'+type);
        if (data.all.length > 0) {
          $container.find('.totalShow').text(data.show.length);
          $container.find('.totalAll').text(data.all.length);
          $container.find('ol').html(data.objects.map(function (object) {
            return '<li><a href="'+object.href+'" title="'+object.title+'" target="_blank">'+object.title+'</a></li>';
          }));
          $container.show();
        } else {
          $container.hide();
        }
      }
      var suggestedMedia   = getSuggesteds('media');
      var suggestedFolders = getSuggesteds('folders');
      if (suggestedMedia.all.length > 0 || suggestedFolders.all.length > 0) {
        showSuggesteds('media', suggestedMedia);
        showSuggesteds('folders', suggestedFolders);
        $suggested.show();
      } else {
        $suggested.hide();
      }
    })();

    $modal.modal('show');
  });
});
</script>
