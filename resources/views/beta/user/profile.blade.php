<div class="accounts">
    <form class="form-horizontal" method="post" action="{{ url('account') }}">
        <div class="row">      
            <div class="col-xs-12">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if (Session::has('message'))
                <div class="alert alert-success">
                    <ul>
                        <li>{{ Session::get('message') }}</li>
                    </ul>
                </div>
                @endif
            </div>
            
            <div class="col-md-6 mt20 mb10">
                <div class="form">
                    <div class="mb10">
                        <label class="fwbold mb0">Username:</label>
                        <input type="text" class="form-control" value="{{ $user->screen_name }}" class="mb0" disabled/>
                        <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
                    </div>
                    <div class="mb10">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="fwbold mb0">First Name:</label>
                                <input type="text" class="form-control" value="{{ $user->first_name }}" class="mb0" disabled/>
                            </div>
                            <div class="col-sm-6">
                                <label class="fwbold mb0">Last Name:</label>
                                <input type="text" class="form-control" value="{{ $user->last_name }}" class="mb0" disabled/>
                            </div>
                        </div>
                        <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
                    </div>
                    <div class="mb10">
                        <label class="fwbold mb0">Profession:</label>
                        <input type="text" name="medical_profession" id="medical_profession" class="form-control" value="{{ $user->medical_profession }}" class="mb0" edit-account="form" disabled/>
                        <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
                    </div>
                    <div class="mb10">
                        <label class="fwbold mb0">Degree:</label>
                        <input type="text" name="medical_degree" id="medical_degree" class="form-control" value="{{ $user->medical_degree }}" class="mb0" edit-account="form" disabled/>
                        <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
                    </div>
                    <div class="mb10">
                        <div class="panel-group mt10" id="accordion" role="tablist" aria-multiselectable="true">
                            <label class="fwbold mb0">Field of Interest: <i class="fa fa-question-circle" data-toggle="popover" data-content="This will help us suggest and organize contents that are related to your field of interest"></i></label>
                            <div role="tab" id="headinginterest">
                                <h5 class="panel-title fz12">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#interest" aria-expanded="true" aria-controls="interest" class="collapsed">
                                        @if($userCategory)
                                            <?php 
                                            $first = 1;
                                            $userInterestString = "";
                                            if( $userCategory ){
                                                $userCategoryNameArray = explode(",", $userCategory->user_category);
                                            } else {
                                                $userCategoryNameArray = array();
                                            }
                                            ?>
                                            @if(isset($categoryData))
                                                @foreach($categoryData as $catVal )
                                                    @if( in_array($catVal->id, $userCategoryNameArray) )
                                                        <?php
                                                        $userInterestString.=$catVal->name; 
                                                        if( $first < count($userCategoryNameArray) ) {
                                                            $userInterestString.=", ";
                                                        }
                                                        $first++;
                                                        ?>
                                                    @endif
                                                @endforeach
                                                {{ $userInterestString }}
                                            @endif
                                        @else
                                        * Field of Interest
                                        @endif
                                    </a>
                                </h5>
                            </div>
                            <div id="interest" class="mt20 panel-collapse collapse" role="tabpanel" aria-labelledby="headinginterest">
                                <div class="row row-md-gutter table-td-top">
                                    @if(isset($categoryData))
                                        <?php 
                                        if( $userCategory ){
                                            $userCategoryArray = explode(",", $userCategory->user_category);
                                        } else {
                                            $userCategoryArray = array();
                                        }
                                        $j = 1; 
                                        $k = 0; 
                                        $closedivstatus = "";
                                        $countCategory = count($categoryData);
                                        $catColumn = round($countCategory/3);
                                        ?>
                                        @foreach($categoryData as $catVal )
                                            <?php $closedivstatus = 0; ?>
                                            @if( $k == 0 )
                                                <div class="col-md-4">
                                                    <table>
                                                        <tbody>
                                            @else
                                                @if( ( $k%$catColumn ) == 0 )
                                                <div class="col-md-4">
                                                    <table>
                                                        <tbody>
                                                @endif
                                            @endif
                                                <tr>
                                                    <td width="10"><label class="checkbox-default mr10 interestcheckbox"><input class="interestedname" id="field_of_interest_{{ $catVal->id }}" type="checkbox" name="field_of_interest[]" value="{{ $catVal->id }}" {{ (in_array($catVal->id, $userCategoryArray)?"checked":"") }}><span class="ico-checkbox"></span></label></td>
                                                    <td><div id="interestnameblck"><label for="field_of_interest_{{ $catVal->id }}">{{ $catVal->name }}</label></div></td>
                                                </tr>
                                            <?php $k++; $j++;?>
                                                @if( ( $k%$catColumn ) == 0 )
                                                    <?php $closedivstatus = 1; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @endif
                                        @endforeach
                                        
                                        @if(0==$closedivstatus)
                                                </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb10">
                        <label class="fwbold mb0">Office Address:</label>
                        <input type="text" name="office_address" id="office_address" class="form-control" value="{{ $user->office_address }}" placeholder="Street" class="mb0" edit-account="form" disabled/>
                        <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
                    </div>
                    <div class="mb10">
                        <label class="fwbold mb0">Email Address:</label>
                        <input type="text" name="new_email" use class="form-control" placeholder="" value="{{ $user->email }}" class="mb0" edit-account="form" disabled/>
                        <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
                    </div>
                    <div class="mb10">
                        <label class="fwbold mb0">Work Website:</label>
                        <input type="text" name="website_url" id="website_url" class="form-control" placeholder="" value="{{ $user->website_url }}" class="mb0" edit-account="form" disabled/>
                        <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
                    </div>
                </div>
                <div class="col-sm-12 spacer tac hidden" edit-account="action">
                    <input type="reset" value="Cancel" class="el-btn el-btn-grey el-btn-lg el-btn-padding-md mr10" onclick="editAccount(false)"/>
                    <input type="submit" value="Save" name="submit" class="el-btn el-btn-green el-btn-lg el-btn-padding-md"/>
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    {{ csrf_field() }}
                </div>
            </div>
            <div class="col-md-3 col-md-offset-1 mt20 mb10">
                <input type="button" value="Edit Profile" class="el-btn el-btn-primary db full mb10" edit-account="trigger" onclick="editAccount(true)"/>
                <input type="button" value="Change Password" class="el-btn el-btn-primary db full" data-toggle="modal" data-target="#modal-change"/>
            </div>
        </div>
    </form>
</div>
<!-- Change Password Modal -->
<div class="modal fade modal-small" id="modal-change" tabindex="-1" role="dialog">
    <div class="modal-dialog form-modal" role="document">
        <div class="modal-content">
            <div class="modal-body form-popup">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
                <div class="row">
                    <div class="col-xs-12">
                        <h3>Change Password</h3>
                        <form method="post" action="{{ url('user/changeprofilepassword') }}" name="changepasswordFrm" id="changepasswordFrm">
                            <div>
                                <div class="mb10">
                                    <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Your old password" required class="mb0" />
                                    <small id="old_password_check" class="db txt-red fz12 mt10 mb10 hidden"></small>
                                </div>
                                <div class="mb10">
                                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New password" required class="mb0" />
                                    <small id="new_password_check" class="db txt-red fz12 mt10 mb10 hidden"></small>
                                </div>
                                <div class="mb10">
                                    <input type="password" name="new_password_confirm" id="new_password_confirm" class="form-control" placeholder="Confirm new password" required class="mb0" />
                                    <small id="confirm_new_password_check" class="db txt-red fz12 mt10 mb10 hidden"></small>
                                </div>
                                <div class="text-center mt20 mb20">
                                    {{ csrf_field() }}
                                    <input type="button" value="Cancel" class="el-btn el-btn-grey el-btn-lg el-btn-padding-md mr10"  data-dismiss="modal" aria-label="Close" />
                                    <input type="button" name="passwordsubmit" value="Save" class="el-btn el-btn-green el-btn-lg el-btn-padding-md" id="save-account" onclick="return saveChangePassword();" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Author: Jinandra
 * Date: 27-12-2016
 * Change profile password validation
 *
 */
$(document).ready(function(){
    $("input.interestedname").attr("disabled", true);
    
    var old_password_check = $('#old_password_check');
    var new_password_check = $('#new_password_check');
    var confirm_new_password_check = $('#confirm_new_password_check');
            
    $("#old_password").bind("keyup change", function(el) {
        old_password_check.addClass('hidden');
    });
    $("#new_password").bind("keyup change", function(el) {
        var new_password = $("#new_password").val();
        if( new_password.length < 6 ){
            new_password_check.removeClass('hidden');  
            new_password_check.html("Password length should be minimum 6 characters");  
        } else {
            new_password_check.addClass('hidden');  
            new_password_check.html("");  
        }
    });
    $("#new_password_confirm").bind("keyup change", function(el) {
        var new_password = $("#new_password").val();
        var new_password_confirm = $("#new_password_confirm").val();
        if( new_password.length > 1 ){
            if( new_password != new_password_confirm ){
                confirm_new_password_check.removeClass('hidden');  
                confirm_new_password_check.html("Sorry, confirm password doesn't match");  
                
            } else {
                confirm_new_password_check.addClass('hidden');  
                confirm_new_password_check.html("");  
            }
        } else {
            confirm_new_password_check.addClass('hidden');  
            confirm_new_password_check.html("");  
        }
    });
});

/**
 * Author: Jinandra
 * Date: 27-12-2016
 * Change profile password validation on save button
 *
 */
function saveChangePassword() {
        
    var old_password = $("#old_password").val();
    var old_password_ency = btoa(old_password);
    var new_password = $("#new_password").val();
    var new_password_ency = btoa(new_password);
    var new_password_confirm = $("#new_password_confirm").val();
    var new_password_confirm_ency = btoa(new_password_confirm);

    //ajax to change media ordering
    var href = '{{ url("user") }}' + '/accountverifyoldpassword';

    // change media order bundle cart using Ajax
    $.ajax({
        url: href,
        type: 'GET',
        data: 'func=checkoldpassword&old_password='+old_password_ency,
        success: function(response) {
            var old_password_check = $('#old_password_check');
            var new_password_check = $('#new_password_check');
            var confirm_new_password_check = $('#confirm_new_password_check');
            
            if( response == "OldPasswordBlank" ){
                old_password_check.removeClass('hidden');  
                old_password_check.html("Please enter old password");  
                return false;
            } else if( response == "OldPasswordWrong" ){
                old_password_check.removeClass('hidden');  
                old_password_check.html("Password is incorrect");  
                return false;
            } else if( new_password_ency == "" ){
                new_password_check.removeClass('hidden');  
                new_password_check.html("Please enter new password");  
                return false;
            } else if( new_password_confirm_ency == "" ){
                confirm_new_password_check.removeClass('hidden');  
                confirm_new_password_check.html("Please enter confirm password");  
                return false;
            } else if( new_password_ency != new_password_confirm_ency ){
                confirm_new_password_check.removeClass('hidden');  
                confirm_new_password_check.html("Sorry, confirm password doesn't match");  
                return false;
            } else {
                $("#changepasswordFrm").submit();
            }
        }
    });
}


/**
 * Author: Jinandra
 * Date: 27-12-2016
 * Edit profile
 *
 */
function editAccount(status) {
    var action = $('[edit-account="action"]');
    var trigger = $('[edit-account="trigger"]');
    var form = $('[edit-account="form"]');
    if(status){
        $("input.interestedname").removeAttr("disabled");
        action.removeClass('hidden');     
        trigger.addClass('hidden');      
        form.prop('disabled', false);  
        $('form').addClass('editMode');
    } else {
        $("input.interestedname").attr("disabled", true);
        action.addClass('hidden');     
        trigger.removeClass('hidden');   
        form.prop('disabled', true);  
        $('form').removeClass('editMode');
    }
}

</script>
<style>
  .editMode #accordion .panel-title a { background: white; }
  .editMode .interestcheckbox { background: white; }
</style>
