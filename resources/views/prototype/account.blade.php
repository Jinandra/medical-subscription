@extends('prototype.userLayout')

@section('title')
  Account Page
@stop

@section('sidebar')
  @include('prototype.sidebar')
@stop

@section('content')
	<div class="col-sm-offset-4 col-sm-8">
		<table class="table table-bordered" style="width: auto;">
			<tr>
				<td colspan="2">Change Password</td>
			</tr>
			<tr>
        <td colspan="2">Change Email Address</td>
      </tr>
			<tr>
        <td colspan="2">Request to be Verified</td>
      </tr>
			<tr>
        <td colspan="2"></td>
      </tr>
			<tr>
        <td>UCode Notification Default</td>
				<td>
					<label class="radio-inline">
						<input type="radio" name="ucode" id="inlineRadio1" selected="true" checked> ON
					</label>
					<label class="radio-inline">
						<input type="radio" name="ucode" id="inlineRadio2" value="off"> OFF
					</label>
				</td>
      </tr>
			<tr>
        <td>Ability to Share with Friends</td>
        <td>
          <label class="radio-inline">
            <input type="radio" name="share" id="inlineRadio1" selected="true" checked> ON
          </label>
          <label class="radio-inline">
            <input type="radio" name="share" id="inlineRadio2" value="off"> OFF
          </label>
        </td>
      </tr>
			<tr>
        <td>Set Friends List</td>
        <td>
          <label class="radio-inline">
            <input type="radio" name="friends" id="inlineRadio1" value="public" checked> Public
          </label>
          <label class="radio-inline">
            <input type="radio" name="friends" id="inlineRadio2" value="private"> Private
          </label>
        </td>
      </tr>
			<tr>
        <td>Accept Friend Request</td>
        <td>
          <label class="radio-inline">
            <input type="radio" name="friendRequest" id="inlineRadio1" selected="true" checked> ON
          </label>
          <label class="radio-inline">
            <input type="radio" name="friendRequest" id="inlineRadio2" value="off"> OFF
          </label>
        </td>
      </tr>
		</table>
	</div>
@stop
