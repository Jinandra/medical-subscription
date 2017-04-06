@include('admin.partial.header')
@include('admin.partial.sidebar')
@include('admin.partial.topnav')


<div class="right_col" role="main">
  <div class="page-title">
    <div class="title_left">
      @if (Session::has('message'))
        <div role="alert" class="alert alert-success">
          {{ Session::get('message') }}
        </div>                            
      @endif
    </div>      
  </div>
  <div class="clearfix"></div>

  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        User: {{ $user->first_name }} {{ $user->last_name }} ({{ $user->screen_name }})
      </div>

      <div class="x_content">
        <table class="table table-striped">
          <tr class="pointer">
            <td class="labelInfo">Name</td>
            <td>{{ $user->fullnameWithScreenName() }}</td>
          </tr>
          <tr class="pointer">
            <td class="labelInfo">Email</td>
            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
          </tr>
          <tr class="pointer">
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr class="pointer">
            <td class="labelInfo">Medical Profession</td>
            <td>{{ $user->medical_profession }}</td>
          </tr>
          <tr class="pointer">
            <td class="labelInfo">Medical Degree</td>
            <td>{{ $user->medical_degree }}</td>
          </tr>
          <tr class="pointer">
            <td class="labelInfo">Office Address</td>
            <td>{{ $user->office_address }}</td>
          </tr>
          <tr class="pointer">
            <td class="labelInfo">
              @if ($user->website_type === 'none')
                &nbsp;
              @else
                @if ($user->website_type === 'company')
                  Employer's website
                @else
                  Profile page on employer's website
                @endif
              @endif
            </td>
            <td>
              @if ($user->website_type === 'none')
                * User doesn't have website
              @else
                @if ($user->website_type === 'company')
                  <a href="{{ $user->website_url }}">{{ $user->website_url }}</a>
                @else
                  <a href="{{ $user->profile_website_url }}">{{ $user->profile_website_url }}</a>
                @endif
              @endif
            </td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          @if ($user->user_status === 'need_verification')
            <tr>
              <td class="labelInfo" style="font-weight: strong; font-style: normal;">
                Action
              </td>
              <td>
                <form method="POST" action="{{ url('admin/user/'.$user->id.'/verify') }}">
                  {{ csrf_field() }}
                  <select name="action" class="form-control" id="selectAction">
                    <option value="">-- Select Your Action --</option>
                    <option value="activate">Activate</option>
                    <option value="decline">Decline</option>
                  </select>
                  <div class="form-group" style="margin: 1em 0; display: none;" id="declineContainer">
                    <label for="decline_message">Decline reason (*won't be sent to user)</label>
                    <textarea class="form-control" name="decline_message" rows="2"></textarea>
                  </div>
                  <input id="submitAction" class="btn" type="submit" value="Submit" disabled style="margin-top: 2em;"/>
                </form>
                <script>
                  $(document).ready(function () {
                    $('#selectAction').change(function () {
                      var action = $(this).val();
                      switch (action) {
                        case 'activate':
                          $('#declineContainer').hide();
                          $('#submitAction').removeClass('btn-danger');
                          $('#submitAction').addClass('btn-primary');
                          $('#submitAction').attr('value', 'Activate');
                          $('#submitAction').removeAttr('disabled');
                          break;
                        case 'decline':
                          $('#declineContainer').show(1000);
                          $('#submitAction').addClass('btn-danger');
                          $('#submitAction').removeClass('btn-primary');
                          $('#submitAction').attr('value', 'Decline');
                          $('#submitAction').removeAttr('disabled');
                          break;
                        default:
                          $('#declineContainer').hide();
                          $('#submitAction').removeClass('btn-danger');
                          $('#submitAction').removeClass('btn-primary');
                          $('#submitAction').attr('value', 'Submit');
                          $('#submitAction').attr('disabled', 'disabled');
                          break;
                      }
                    });
                  });
                </script>
              </td>
            </tr>
          @else
            <tr>
              <td class="labelInfo">Status</td>
              <td>
                @if ($user->user_status === 'active')
                  <span style="color: green;">Verified</span> at {{ $user->verified_at }}
                @else
                  <p>
                    <span style="color: red;">Declined</span> at {{ $user->declined_at }}<br/>
                    Reason: {{ $user->decline_message }}
                  </p>
                  <form method="POST" action="{{ url('admin/user/'.$user->id.'/verify') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="action" value="activate" />
                    <input class="btn btn-warning" type="submit" value="Activate" style="margin-top: 2em;"/>
                  </form>
                @endif
              </td>
            </tr>
          @endif
        </table>
        <p>
          <a href="{{ $user->user_status === 'need_verification' ? url('admin/user/pendings') : url('admin/user') }}">Back</a>
        </p>
      </div>
    </div>   
  </div>
</div>
<style>
  .labelInfo { width: 15%; text-align: right; font-style: italic; }
</style>


@include('admin.partial.footerjs')
<script>
</script>
@include('admin.partial.footer')
