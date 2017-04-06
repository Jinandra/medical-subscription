@extends('beta.userLayout')
@section('content')
<?php
if($session->get("pdfgenerate")!=""){
    $pdfgeneratesession = $session->get("pdfgenerate");
}
?>
<div class="col-sm-9 col-md-10 col-xs-9 right-column">
  <div class="headings nomarge">
    <h1>My UCodes</h1>
    <p></p>
  </div>
  <div class="content">
    <div class="container">
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

          @if (Session::has('flash_message'))
          <div class="alert alert-success">
              <ul>
                  <li>{{ Session::get('flash_message') }}</li>
              </ul>
          </div>
          @endif
      </div>
      <!-- END -->

      <!-- BEGIN search box -->
      <div class="row">
        <div class="col-sm-12 col-md-7 col-xs-12">
          <div class="row">
            <div class="col-sm-12 col-md-7 col-xs-12">
              <form action="{{ url('/bundle') }}" method="GET" id="formId">
                <div class="search-section small-section mt20 mb20">
                  <input type="text" name="s" value="{{ $s }}"/>
                  <input type="submit" value="search"/>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- END search box -->

      <div class="row">
        @if (count($ucodes) <= 0)
          <p>No ucode found</p>
        @else

          <!-- BEGIN ucode list -->
          <div class="col-sm-12 col-md-7 col-xs-12">
            <div class="row bundle-box">
              <div data-example-id="hoverable-table" class="bs-example bundle-box-overflow">
                <table class="table">
                <thead>
                  <tr>
                    <th>Ucode</th>
                    <th class="tac">Created</th>
                    <th class="tac">Last Accessed</th>
                    <th class="tac">Views</th>
                    <th class="tac">Bundle</th>
                    <th class="tac">PDF</th>
                    <th class="tac"></th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($ucodes) > 0)
                    <?php $i = 0; ?>
                    @foreach($ucodes as $row)
                      <?php $i++; ?>
                      <tr class="table-bundle {{ $i === 1 ? 'active' : '' }}" bundle-target="#bundle{{$i}}"  id="idTr{{ $row->ucode }}" onclick="showBundle('{{ $row->ucode }}', {{ $row->id }})"  >
                          <td><a target="_blank" href="{{ url('/ucode/'.$row->ucode.'') }}" onclick="event.cancelBubble = true;">{{ $row->ucode }}<div id="ucodeCopyId_{{ $row->id }}" style="display:none">UCode: {{ $row->ucode }}</div></a></td>
                        <td class="tac">{{ date('m/d/Y', strtotime($row->created_at)) }}</td>
                        @if(isset($row->countUcodeHistory))
                          <td class="tac">{{ date('m/d/Y', strtotime($row->uCodeHistoryCreatedAt)) }}</td>
                        @else
                          <td>&nbsp;</td>
                        @endif
                        <td class="tac">{{ $row->countUcodeHistory }}</td>
                        <td class="tac">
                          <div style="text-align: center;">
                            <a href="{{ url('bundle/'.$row->ucode.'/addUcode') }}" data-toggle="popover" data-content="Add to Bundle" class="add-to-bundle" data-ucode="{{$row->ucode}}">
                              <img src="{{ config('app.assets_path').'/images/bundle-btn-sm.png' }}" alt="">
                            </a>
                          </div>
                        </td>
                        <td class="tac">
                            <div class="dropdown">
                                <div data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-download mr5 ml5"></i>
                                </div>
                                <ul class="dropdown-menu pull-right" aria-labelledby="dLabel">
                                    <li>
                                        <a class="printcode" href="#" data-toggle="modal" data-target="#modal-report" data-dismiss="modal" id="{{ $row->id }}">Print</a>
                                    </li>
                                    <li>
                                        <a class="pdfdownload" href="#" data-toggle="modal" data-target="#modal-report" data-dismiss="modal" id="{{ $row->id }}">Download</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <!-- ucode copy div hidden -->
                            <div id="ucodecopycontainer_{{ $row->id }}" style="display: none;"></div>
                            <!-- end -->
                            <div class="dropdown">
                                <div data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" onclick="ucodeCopyClipboard({{ $row->id }})">
                                    <i class="fa fa-ellipsis-v mr5 ml5"></i>
                                </div>
                                <ul class="dropdown-menu pull-right" aria-labelledby="dLabel">
                                    <li>
                                        <a class="copyucodeonly" href="#" data-toggle="modal" data-target="#modal-report" data-dismiss="modal" id="{{ $row->id }}">Copy UCode Only</a>
                                    </li>
                                    <li>
                                        <a class="copyucodetitle" href="#" data-toggle="modal" data-target="#modal-report" data-dismiss="modal" id="{{ $row->id }}">Copy UCode & Title</a>
                                    </li>
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#modal-report" data-dismiss="modal" data-value="{{ $row->id }}" class="btnSaveTo">Save To</a>
                                    </li>
                                    <li>
                                        <a href="#contributeModalDelete" data-toggle="modal" data-target="#modal-report" data-dismiss="modal" data-id="{{$row->ucode}}" data-title="{{$row->ucode}}" data-value="{{ $row->id }}" class="tdn btnDeleteUcode">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                  @endif
                </tbody>
                </table>
              </div>
                <!-- PAGINATION TEMPLATE -->  
                @if ($ucodes->lastPage() > 1)
                    <div class="pagination-panel">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                @if ($ucodes->currentPage() != 1)
                                <li>
                                    <a href="{{ url('bundle?s='.$s.'&page='.($ucodes->currentPage()-1)) }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                @endif
                                @for ($i = 1; $i <= $ucodes->lastPage(); $i++)
                                    <li class="{{ ($ucodes->currentPage() == $i) ? ' active' : '' }}">
                                        <a href="{{ url('bundle?s='.$s.'&page='.$i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                @if($ucodes->currentPage() != $ucodes->lastPage())
                                <li class="{{ ($ucodes->currentPage() == $ucodes->lastPage()) ? ' disabled' : '' }}">
                                    <a href="{{ url('bundle?s='.$s.'&page='.($ucodes->currentPage()+1)) }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
          </div>
          <!-- END ucode list -->

          <!-- BEGIN media list -->
          <div class="col-sm-12 col-md-5 col-xs-12">
            <div id="bundle1" class="row bundle-box horizontal-column bundle-box-preview active">
              <div data-example-id="hoverable-table" class="bs-example" id="ucodeAjax">
                @include('beta.partials.ucode.mediaList', ['ucode' => $ucode, 'media' => $medias])
              </div>
            </div>
          </div>
          <!-- END media list -->
        @endif
      </div>
    </div>
    <div id="overlay"></div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalUcode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Ucode Created Sucessfully</h4>
            </div>
            @if (isset($pdfgeneratesession['ucodegenerateval']))
            <?php $mediasdata = App\Models\Ucode::where('ucode', $pdfgeneratesession['ucodegenerateval'])->first()->statsMedia(); ?>
<div id="ucodecopyppopup_{{ $pdfgeneratesession['ucodeidval'] }}" style="display: none;">UCode: {{ $pdfgeneratesession['ucodegenerateval'] }}
@if(!empty($mediasdata))
@foreach($mediasdata as $mrow)
{{ $mrow->title }}<br />
@endforeach
@endif</div>
@endif
            <div id="ucodeCopyTarget" style="display:none">UCode: {{ (isset($pdfgeneratesession['ucodegenerateval'])?$pdfgeneratesession['ucodegenerateval']:"") }}</div>
            <div class="modal-body">
                <div class="mt20 mb20">
                    <h3 class="tac fwb fz30 mb40">
                        @if (isset($pdfgeneratesession['ucodegenerateval']))
                        <div style="float: left; width: 370px; text-align: right; margin-top: -21px;">{{ (isset($pdfgeneratesession['ucodegenerateval'])?$pdfgeneratesession['ucodegenerateval']:"") }}</div>
                        <div style="float: left; width: 200px;text-align: left;padding: 0px 0px 42px 15px; margin-top: -23px;">
                            <i class="fa fa-copy fz16 txt-teal vam txt-link ucodecopytoclipboard" data-toggle="tooltip" data-placement="top" title="Copy to clipboard"></i>
                            <span id="msg" style="font-size:10px"></span>
                        </div>
                        @endif
                    </h3>
                    <div class="tac ucodepdfbutton">
                        <input type="button" class="el-btn el-btn-lg el-btn-padding-md el-btn-green ml5 mr5 pdfpreview" id="{{ (isset($pdfgeneratesession['ucodegenerateval'])?$pdfgeneratesession['ucodegenerateval']:"") }}" value="View Bundle"/>
                        <input type="button" class="el-btn el-btn-lg el-btn-padding-md el-btn-green ml5 mr5 pdfdownload" id="{{ (isset($pdfgeneratesession['ucodeidval'])?$pdfgeneratesession['ucodeidval']:"") }}" value="Download"/>
                        <input type="button" class="el-btn el-btn-lg el-btn-padding-md el-btn-green ml5 mr5 printcode" id="{{ (isset($pdfgeneratesession['ucodeidval'])?$pdfgeneratesession['ucodeidval']:"") }}" value="Print"/>
                        <input type="button" class="el-btn el-btn-lg el-btn-padding-md el-btn-green ml5 mr5 popupcopyucode" id="{{ (isset($pdfgeneratesession['ucodeidval'])?$pdfgeneratesession['ucodeidval']:"") }}" value="Copy UCode & Title"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('beta.partials.modal')
@stop

@section('additionalScript')
<div id="printcontainer" class="printable" style="display: none;"></div>
<script src="{{ config('app.assets_path').'/js/jquery.PrintArea.js' }}"></script>
<script type="text/javascript">
    
    function showBundle(ucode, ucodeid) {
        $(".dropdown").removeClass("open");
        $(document).on('click', "#idTr" + ucode, function (e) {
            $('.table-bundle').removeClass('active');
            $("#idTr" + ucode).addClass('active');
            e.stopImmediatePropagation(); e.stopPropagation(); e.preventDefault();
            $.ajax({
            url: "{{ url('/bundle/ucode/') }}" + "/" + ucode,
                    success: function(response) {
                        $("#ucodeAjax").html(response);
                        
                    }
            })
        });
    }
    /* copy to clipboard ajax */
    function ucodeCopyClipboard(ucodeid) {
        var href = '{{ url("bundle") }}' + '/ucodecopyclipboard';
        $.ajax({
            url: href,
            type: 'GET',
            async: true,
            data: {ucodeid:ucodeid},
            success: function (response) {
                $("#ucodecopycontainer_"+ucodeid).html(response);
                //ucodeSavetoMapping(ucodeid);
            }
        });
    }
    
    $(document).ready(function () {
        // ucode copy to clipboard in ucode popup
        $(document.body).on('click', '.ucodecopytoclipboard', function (e) {
            copyToClipboardMsg(document.getElementById("ucodeCopyTarget"), 'msg', 'popup');
        });
        // ucode and title copy to clipboard in ucode popup
        $(document.body).on('click', '.popupcopyucode', function (e) {
            var ucodeid = $(this).attr('id');
            copyToClipboard(document.getElementById("ucodecopyppopup_"+ucodeid), 'popup');
        });
        
        // ucode only copy to clipboard from list
        $(document.body).on('click', '.copyucodeonly', function (e) {
            var ucodeid = $(this).attr('id');
            copyToClipboardMsg(document.getElementById("ucodeCopyId_"+ucodeid), 'msgtop');
        });
        
        // PDF download in new tab
        $(document.body).on('click', '.pdfdownload', function (e) {
            var ucodeid = $(this).attr('id');
            window.open("{{ url('/bundle/ucodepdfdownload/') }}/"+ucodeid, "_blank");
        });
        //pdf preview in new tab
        $(document.body).on('click', '.pdfpreview', function (e) {
            var ucodeid = $(this).attr('id');
            window.open("{{ url('/ucode') }}/"+ucodeid, "_blank");
        });
        
        //Content for copy to clipboard ucode and title
        $(document.body).on('click', '.copyucodetitle', function (e) {
            var ucodeid = $(this).attr('id');
            copyToClipboardMsg(document.getElementById("ucodecopycontainer_"+ucodeid), 'msg', 'content');
        });
        
        
        //To print ucode
        $(document.body).on('click', '.printcode', function (e) {
            var ucodeid = $(this).attr('id');
            var href = '{{ url("bundle") }}' + '/ucodepdf';
            
            $.ajax({
              url: href,
              type: 'GET',
              data: {ucodeid:ucodeid},
              success: function (response) {
                    $("#printcontainer").html(response);
                    $("#printcontainer").show();
                    var mode = 'iframe'; // popup
                    var close = mode == "popup";
                    var title = "";
                    var options = { mode : mode, popClose : close, popTitle : title};
                    $("div.printable").printArea( options );
                    $("#printcontainer").html("");
              }
            });
        });
        
        $('.add-to-bundle').bundleButtonSM({
          onSuccess: function ($triggerButton, response) {
            if ($triggerButton.hasClass('bundle-added-sm')) {
              $triggerButton.attr('href', $triggerButton.attr('href').replace('addUcode', 'removeUcode'));
            } else {
              $triggerButton.attr('href', $triggerButton.attr('href').replace('removeUcode', 'addUcode'));
            }
            var ucodeValue = $triggerButton.data('ucode');
            $('.table-bundle').removeClass('active');
            $("#idTr" + ucodeValue).addClass('active');
          }
        });
        //Ucode in popup after created successfully
        @if( isset($pdfgeneratesession['pdfgenerateval']) && $pdfgeneratesession['pdfgenerateval']==1)
            $('#modalUcode').modal('show');
        @endif
    });
    
    var COLLECTIONS_MAPPING = {};
    @if (isset($collections) && count($collections) > 0)
      @include('beta.collection.mapping_to_js', ['collections' => $collections, 'showMedia' => false]);
    @endif
    
    var UCODES_MAPPING = {}; // global ucodes mapping
    @include('beta.bundle.mapping_to_js', ['ucodes' => $ucodes]);
    window.UCODES_MAPPING = UCODES_MAPPING;
    window.COLLECTIONS_MAPPING = COLLECTIONS_MAPPING;
    window.CSRF_TOKEN = "{{ csrf_token() }}";
    window.UCODE_URL = "{{ url('bundle') }}";
</script>

<script src="{{ URL::asset('resources/assets/js/myucodes.js') }}"></script>
<div id="savetocontainer" style="display: block;"></div>
<style>
    tr.table-bundle:last-child td { border-bottom: 1px solid #ddd; }
    .bundle-box-preview { height: 621px; }
    .ul-limit-7 > ul > li { list-style-type: none; }
    .ul-limit-7 > ul > li > a { text-decoration: none; }
    .bundle-box-overflow{
        height: auto !important;
        border: 0px solid #e8e8e8 !important;
    }
</style>
@stop
