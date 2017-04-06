<?php $hideBundleCart = false; ?>
@extends('beta.userLayout')

@section('content') 
<div class="col-sm-9 col-md-10 col-xs-9 right-column">
    <div class="headings">
        <h1>Bundle Cart</h1>
        <p></p>
    </div>
    <div class="content spacer-top">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="collection-accordion-content collection-grid-wrap-1 mb10">
                        <div class="collection-grid-panel p0">
                            <div class="collection-accordion-head top-section">
                                <div class="accordion-title txt-bold">Media in your Bundle</div>
                                <div class="tar pull-right">
                                    <button type="button" class="btn btn-default mr5" onclick="window.location.href='{{ url('/bundle/cart/delete')}}'">
                                        <i class="fa fa-trash mr10"></i>Clear all
                                    </button>
                                </div>
                            </div>
                            @if($media)
                            <?php $i = 1; ?>
                            <div class="collection-media-wrap collection-media-wrap-with-number view-10 mb10">
                                @foreach($media as $row)
                                    <div id="{{ $row->cartMediaId }}" class="collection-media-item table-view">
                                        <div class="col-view">
                                            <div class="media-item-number dib">{{ $i }}.</div>
                                            <a href="{{ url('/media') }}/{{ $row->id }}" target="_blank">{{ limitString($row->title, 80) }}</a>
                                        </div>
                                        <div class="col-view collection-icon-list">
                                            @if( "website" == $row->type )
                                                <small class="txt-type txt-blue txt-bold" data-toggle="popover" data-content="Website">Web</small>
                                            @elseif( "text" == $row->type )
                                                <small class="txt-type txt-green txt-bold" data-toggle="popover" data-content="Doc">Doc</small>
                                            @elseif( "video" == $row->type )
                                                <small class="txt-type txt-red txt-bold" data-toggle="popover" data-content="Video">Vid</small>
                                            @elseif( "image" == $row->type )
                                                <small class="txt-type txt-orange txt-bold" data-toggle="popover" data-content="Image">Img</small>
                                            @endif
                                            <div class="icon-info dib vam">
                                                <img src="{{ config('app.assets_path') }}/images/ico-info.png" alt="" data-toggle="popover" data-content="{{ $row->description }}" class="info" />
                                            </div>
                                            <div class="dib vam lhn">
                                                <a href="{{ url('/bundle') }}/cart/{{ $row->id }}/delete"><i class="fa fa-close fa-sm" data-toggle="popover" data-content="Delete"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php $i++; ?>
                                @endforeach
                            </div>
                            @else
                            <div class="bundle-media-wrap-empty collection-media-wrap-with-number view-10 mb10">
                              <ul>
                                <li>You have not selected any media for your bundle.</li>
                                <li>To select media from your collection page, click the checkbox next to it and click "Bundle" before going to the Bundle Cart.</li>
                                <li>To select media from the library, click the Box icon in the media thumbnail before going to the Bundle Cart.</li>
                              </ul>
                            </div>
                            @endif
                            <small class="mb20 db">
                                Drag to order your media
                            </small>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-5 tooltip-absolute">
                    <form action="{{ url('/bundle/cart/store') }}" method="POST">
                        <div class="mb10">
                            <label class="form-control-static txt-bold">Email UCode <i class="fa fa-question-circle" data-toggle="popover" data-content="Enter Email"></i> - optional</label>
                            <input type="email" name="email_ucode" id="email_ucode" class="form-control" placeholder="Email address" class="mb0" />
                        </div>
                        <!--
                        <div class="mb20">
                            <label class="form-control-static txt-bold">Text UCode <i class="fa fa-question-circle" data-toggle="popover" data-content="Enter Phone Number"></i> - optional</label>
                            <input type="tel" name="text_ucode" id="text_ucode" class="form-control" placeholder="Phone Number" class="mb0" />
                        </div>
                        -->
                        <div class="mt20 clearfix"></div>
                        <!-- BEGIN success/error message -->
                        <div class="col-xs-12">
                            @if ($errors->has())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            
                            @if (Session::has('error_message'))
                            <div class="alert alert-danger">
                                <ul>
                                    <li>{{ Session::get('error_message') }}</li>
                                </ul>
                            </div>
                            @endif
                            
                            @if (Session::has('flash_message'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{{ Session::get('flash_message') }}</li>
                                </ul>
                            </div>
                            @endif
                        </div>
                        <!-- END -->
                        <div class="mt20 mb20 tar">
                            <input type="hidden" name="pdfgenerate" id="pdfgenerate" value="1">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="ucode2" value="{{ $ucode }}">
                            <input type="button" class="el-btn el-btn-lg el-btn-padding-md el-btn-grey mr5" value="Cancel" onclick="location.href='{{ url('/bundle') }}'" />
                            <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-green" value="Create" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="overlay"></div>
</div>

</div>
</div>
</div>

</div>
<style>
.bundle-media-wrap-empty {
    height: 350px;
    overflow-y: auto;
    padding: 0 10px;
    background: #fff;
}

.bundle-media-wrap-empty ul {
    margin-top: 70px;
}
.bundle-media-wrap-empty li {
  margin: 18px 0;
}
.collection-accordion-head {
  min-height: 50px;
}
.collection-accordion-head .accordion-title {
  padding-top: 10px;
}
</style>

<script type="text/javascript">
/*
* Author : Jinandra
* Date: 28-10-2016
* Sort order for media added in bundle cart
* Drag and drop sort order
*/

$(function() {
    function addBundleButton() {
        var a = $('.view-listing .bundle.active').length;
        if (a > 0) {
            $('.add-bundle-wrap').addClass('active');
        }
        else {
            $('.add-bundle-wrap').removeClass('active');
        }
        $('#add-bundle-number').html(a);
    }
    $( ".collection-media-wrap, .collection-media-wrap" ).sortable({
        connectWith: ".collection-media-wrap",
        helper: 'clone',
        dropOnEmpty: true,
        tolerance: "pointer",
        items: ".collection-media-item:not(.empty-media)",
        start: function (e, ui) {
            ui.helper.animate({
                width: 'auto',
                height: 50
            });
        },
        update: function() {
            $('.collection-media-wrap-with-number').find('.collection-media-item').each(function() {
                let number = $(this).index();
                $(this).find('.media-item-number').html((number+1)+'.')
            });
            $('.collection-grid-panel').each(function() {
                let length = $(this).find('.collection-media-wrap .collection-media-item').length;
                console.log(length);
                if(length == 1){
                    $(this).addClass('panel-empty');
                    $(this).find('.collection-media-selector').hide();
                } else {
                    $(this).removeClass('panel-empty');
                    $(this).find('.collection-media-selector').show();
                }
            });
            
            //ajax to change media ordering
            var list_sortable = $(this).sortable('toArray').toString();
            var href = '{{ url("bundle") }}' + '/mediasortorder';
            // change media order bundle cart using Ajax
            $.ajax({
                url: href,
                type: 'GET',
                data: {bundle_builder_cart_id:list_sortable},
                success: function() {
                    //finished
                }
            });
        },
        cursorAt: {left:5, top:5},
        receive: function(event, ui) {
                //hide empty message on receiver
                $('.collection-media-item.empty-media', this).hide();
                //show empty message on sender if applicable
                if($('.collection-media-item:not(.empty-media)', ui.sender).length == 0){
                    $('.collection-media-item.empty-media', ui.sender).show();
                } else {
                    $('.collection-media-item.empty-media', ui.sender).hide();
                }            
            }
    }).disableSelection();

    $('.view-listing .bundle').each(function () {
        $(this).on('click', function () {
            var a = $(this);
            var b = 'active';
            if (a.hasClass(b)) {
                a.removeClass(b);
            }
            else {
                a.addClass(b);
            }
            addBundleButton();
        })
    });

    /* $('.tooltip').on('mouseover', function () { */
    /*     var a = $(this).offset(); */
    /*     var c = $(window).width(); */
    /*     var d = c - 250; */
    /*     if (d < a.left) { */
    /*         $(this).find('.tooltiptext').addClass('pos-right') */
    /*     } */
    /* }) */

    $('#dropdown-auto').change(function () {
        $("#dropdown-auto-tmp-option").html($('#dropdown-auto option:selected').text());
        $(this).width($("#dropdown-auto-tmp").width());
    });
});
</script>

@stop
