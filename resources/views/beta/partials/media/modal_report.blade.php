<!-- START modal for media report -->
<div class="modal fade modal-small" id="modal-report" tabindex="-1" role="dialog" aria-labelledby="Create new folder">
    <div class="modal-dialog form-modal" role="document">
        <div class="modal-content">
            <div class="modal-body form-popup">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <div class="row">
                    <div class="col-xs-12">
                        <h3>Report Media</h3>
                        <form method="POST" id="report_form" data-toggle="validator" >
                            <div>
                                <?php $reasons = App\Models\MediaReport::getAllReasons(); ?>
                                @foreach($reasons as $number => $text)
                                    <label class="mb10 db">
                                        <input type="radio" name="reason" value="{{$number}}" {{(reset($reasons)==$text)?'checked':''}}/>
                                        <span class="ml5">{{$text}}</span> 
                                    </label>
                                @endforeach
                                <div class="error error-reason"></div>
                                <div class="mb10">
                                    <textarea name="comment" id="report_comment" class="form-control" cols="30" rows="3" placeholder="Type your description here."></textarea>
                                </div>
                                <div class="error error-comment"></div>
                                <div class="error error-media_id"></div>
                                <div class="mb10 tar">
                                    <input type="hidden" name="media_id" value="{{$id}}" />
                                    <input type="submit" id="send_report" class="el-btn el-btn-lg el-btn-padding-md el-btn-green" value="Send report" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END modal send report -->
