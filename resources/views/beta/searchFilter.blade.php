@extends(Auth::check() ? 'beta.userLayout' : 'beta.layout')

@section('title')
Search Result Page for '{{ $query }}' | Enfolink
@stop

@section('content')
<div class="col-sm-9 col-md-10 col-xs-9 right-column">
    <div class="content">
        <div class="row">
            <div class="col-md-12"> 
                <div class="headings nomarge">
                    <h1>Search Results : {{ $query }}</h1>
                    <p></p>
                </div>
            </div>
            <div class="col-sm-3 col-md-3 sidebar-box-wrap mt20">
                <div class="sidebar-box">
                    <div class="fwb p10">
                        Sort
                    </div>
                    <ul class="p0 sidebar-box-menu mb0">
                        <?php $i = 1; ?>
                        @foreach($allSortTerms as $term => $text)
                        <li>
                            <input type="radio" name="sort-term" id="st{{ $i }}" class="sort-term filter-option" style="display: none;" value="{{ $term }}" {{ (Input::get("sort") == $term)?'checked':'' }} />
                            <label for="st{{ $i }}" class="filter-text"><i class="fa fa-angle-right mr10"></i>{{ $text }}</label>
                        </li>
                        <?php $i ++; ?>
                        @endforeach
                    </ul>
                    <div class="fwb p10">
                        Upload Date
                    </div>
                    <ul class="p0 sidebar-box-menu">
                        <?php $i = 1; ?>
                        @foreach($allSearchDates as $date => $text)
                        <li>
                            <input type="radio" name="upload-date" id="ud{{ $i }}" class="upload-date filter-option" style="display: none;" value="{{ $date }}" {{ (Input::get("date") == $date)?'checked':'' }} />
                            <label for="ud{{ $i }}" class="filter-text"><i class="fa fa-angle-right mr10"></i>{{ $text }}</label>
                        </li>
                        <?php $i ++; ?>
                        @endforeach
                    </ul>
                    <div class="fwb p10">
                        Type
                    </div>
                    <ul class="p0 sidebar-box-checklist">
                        @foreach($allSearchTypes as $type => $text)
                        <li>
                            <label class="checkbox-default dib">
                                <input type="checkbox" class="search-type {{ ($type == App\Models\Common::SEARCH_TYPE_ALL)?'all-types':(($type == App\Models\Common::SEARCH_TYPE_USER)?'user-type':'single-type') }}" name="{{ $type }}" {{ (in_array($type, $checkedSearchTypes))?'checked':'' }}>
                                <span class="ico-checkbox"></span>
                            </label>
                            <div class="dib">
                                <span class="ml5">{{ $text }}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <br />
                </div>
            </div>
            <div class="col-md-9 mainbar-box">
                <div class="container container-listing-vertical">
                    <div id="search-results" class="row row-col-4">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--<a href="watch-more-video.html" class="more">Watch more</a>-->
    <div id="overlay"></div>
</div>
@stop

@section('additionalScript')
<style>
    #search_folders, #search_media, #search_user {
        margin: 0;
    }
    .filter-option:checked+label {
        background: #ddd;
    }
    .filter-text {
        cursor: pointer;
        display: block;
        padding: 7px 30px 7px 10px;
        margin: 0;
        position: relative;
        text-decoration: none;
        color: #424242;
    }
    .filter-text:hover {
        background: #d1f1ec;
    }
</style>
<script>
    var query = '{{ Input::get("query") }}';
    var sortTerm = '{{ Input::get("sort") }}';
    var uploadDate = '{{ Input::get("date") }}';
    var checkedTypes = '{{ Input::get("types") }}';

    function attachHandlers () {
      $('.limit-text').limitText();
      $('.set-folder').saveCollectionButton();
      $('.set-bookmark').bookmarkButton();
      $('.bundleMedia').bundleButton();
      $('.bundleFolder').bundleButton({
        onSuccess: function ($button) {
          if ($button.hasClass('active')) {
            $button.attr('href', $button.attr('href').replace('add', 'remove'));
          } else {
            $button.attr('href', $button.attr('href').replace('remove', 'add'));
          }
        }
      });
    }
  
    var search = function() {
        var searchString = '{{ url("search/filter") }}' + '?query=' + query + '&sort=' + sortTerm + '&date=' + uploadDate + '&types=' + checkedTypes;
        $.ajax({
            type: 'POST',
            url: "{{url('search/filter')}}",
            data: {query: query, sort: sortTerm, date: uploadDate, types: checkedTypes, _token: "{{ csrf_token() }}"},
            success: function (data) {
                if(data.success == true) {
                    $('#search-results').html('');
                    $('#search-results').append(data.data).html();
                    window.history.pushState("", "", searchString);

                    attachHandlers();
                }
            }
        })
    }
    
    $(document).ready(function () {
        search();

        $('body').on('change', '.sort-term', function() {
            sortTerm = $(this).val();
            $('#search-sort').val(sortTerm);
            search();
        });
        $('body').on('change', '.upload-date', function() {
            uploadDate = $(this).val();
            $('#search-date').val(uploadDate);
            search();
        });
        $('body').on('change', '.search-type', function() {
            if($(this).hasClass('single-type')) {
                if($('.single-type:checked').length == $('.single-type').length) {
                    $('.all-types').prop('checked', true);
                    $('.single-type').prop('checked', false);
                } else {
                    $('.all-types').prop('checked', false);
                }
            } else if($(this).hasClass('all-types')) {
                $('.single-type').prop('checked', false);
            }
            checkedTypes = $('.search-type:checked').serialize().replace(/=on/g, '').replace(/&/g, '-');
            $('#search-types').val(checkedTypes);
            search();
        });
    });
</script>
@stop
