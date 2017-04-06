@extends('beta.layout')


@section('title')
  Create new account | Enfolink
@stop


@section('content')
<div class="container mt40">
  <div class="row clearfix mb20">
    <div class="col-sm-6 col-sm-offset-3">

      @if (count($errors) > 0 || session('recaptcha') === false)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
            @if (session('recaptcha') === false)
              <li>Invalid captcha</li>
            @endif
          </ul>
        </div>
      @endif

      <form class="panel-box p30" method="POST" action="{{ url('user/registration') }}">
        {{ csrf_field() }}
        <div class="text-center">
          <h3 class="fz30 mb20">Create new account</h3>
        </div>
        <div class="form">
          <div class="mb20 text-center">
            <p>This website is currently only accepting registrations from health professionals.</p>
            <p>Register a free account by filling out the application below.</p>
          </div>
          <div class="mb10">
            <input name="screen_name" value="{{ old('screen_name') }}" type="text" class="form-control" placeholder="* Create new username" class="mb0"  required />
            <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
          </div>
          <div class="mb10">
            <input name="first_name" value="{{ old('first_name') }}" type="text" class="form-control" placeholder="* First name" class="mb0" required />
            <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
          </div>
          <div class="mb10">
            <input name="last_name" value="{{ old('last_name') }}" type="text" class="form-control" placeholder="* Last name" class="mb0" required />
            <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
          </div>
          <div class="mb10">
            <input name="medical_profession" value="{{ old('medical_profession') }}" type="text" class="form-control" placeholder="* Medical Profession" class="mb0" required />
            <small class="db fz12 mt10 mb20">e.g. Pediatrician, Audiologist, Registered Nurse, Physical Therapist</small>
          </div>
          <div class="mb10">
            <input name="medical_degree" value="{{ old('medical_degree') }}" type="text" class="form-control" placeholder="* Medical Degree" class="mb0" required />
            <small class="db fz12 mt10 mb20">e.g.  MD, AuD, DO, RN, DPT</small>
          </div>
          <div class="mb10">
            <div class="panel-group mt10" id="accordion" role="tablist" aria-multiselectable="true">
              <div role="tab" id="headinginterest">
                <h5 class="panel-title fz12">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#interest" aria-expanded="true" aria-controls="interest">
                    * Medical Field of Interest
                  </a>
                </h5>
              </div>
              <div id="interest" class="mt20 panel-collapse collapse in" role="tabpanel" aria-labelledby="headinginterest">
                <div class="row row-md-gutter table-td-top">
                  @if (count($categories) < 10)
                    <div class="col-md-12">
                      @include('beta.user.categories', ['categories' => $categories])
                    </div>
                  @elseif (count($categories) < 20)
                    <div class="col-md-6">
                      @include('beta.user.categories', ['categories' => $categories->slice(0, 10)])
                    </div>
                    <div class="col-md-6">
                      @include('beta.user.categories', ['categories' => $categories->slice(10)])
                    </div>
                  @else
                    <?php $perRow = round(count($categories) / 3); ?>
                    <div class="col-md-4">
                      @include('beta.user.categories', ['categories' => $categories->slice(0, $perRow)])
                    </div>
                    <div class="col-md-4">
                      @include('beta.user.categories', ['categories' => $categories->slice($perRow, $perRow)])
                    </div>
                    <div class="col-md-4">
                      @include('beta.user.categories', ['categories' => $categories->slice($perRow*2)])
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <br/>
          <div class="mb20">
            <input name="office_address" value="{{ old('office_address') }}" type="text" class="form-control" placeholder="* Office/Work Address" class="mb0" required />
            <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
          </div>
          <div class="mb10">
            * Please choose one option.
          </div>
          <div class="mb5">
            <label for="profile" class="tooltip-absolute">
              <input type="radio" value="profile" name="website_type" id="profile" {{ old('website_type') === 'profile' || is_null(old('website_type')) ? 'checked' : '' }}>
              <span class="ml5">
                Profile page on employer’s website
              </span>
              <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Please provide a link to your personal profile page on your hospital/clinic website; ideally this page would display your name and email so that we can verify that you are a health professional."></i>
            </label>
            <div id="profile-input" class="hide mb10 mt5 {{ old('website_type') === 'profile' || is_null(old('website_type')) ? 'active' : '' }}">
              <input name="profile_website_url" value="{{ old('profile_website_url') }}"  type="text" class="form-control" placeholder="e.g. http://www.hospital.com/john_smith" class="mb0" {{ old('website_type') === 'profile' ? 'required' : '' }} />
            </div>
          </div>
          <div class="mb5">
            <label for="website" class="tooltip-absolute">
              <input type="radio" value="company" name="website_type" id="website" {{ old('website_type') === 'company' ? 'checked' : '' }}>
              <span class="ml5">Employer’s website</span>
              <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="If your employer’s website does not provide you a personal profile page, please provide us with the general web address of your the employer."></i>
            </label>
            <div id="website-input" class="hide mb10 mt5 {{ old('website_type') === 'company' ? 'active' : '' }}">
              <input type="text" name="website_url" value="{{ old('website_url')  }}" class="form-control" placeholder="e.g. http://www.hospital.com" class="mb0" {{ old('website_type') === 'company' ? 'required' : '' }} />
            </div>
          </div>
          <div class="mb20">
            <label for="no-website">
              <input type="radio" value="none" name="website_type" id="no-website" {{ old('website_type') === 'none' ? 'checked' : '' }}>
              <span class="ml5">Employer does not have a website</span>
            </label>
          </div>
          <div class="mb10">
            <input name="password" type="password" class="form-control" placeholder="* Create password" class="mb0" required />
            <!--<small class="db txt-red fz12 mt10 mb20">Password minimum lenght is 5 character</small>-->
          </div>
          <div class="mb20">
            <input name="password_confirmation" type="password" class="form-control" placeholder="* Confirm password" class="mb0" required />
            <!--<small class="db txt-red fz12 mt10 mb20">Password is not same</small>-->
          </div>
          <div class="mb20">
            <div class="tooltip-absolute">
              * Professional/Work Email Address
              <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Professional email will be used to verify your status as a healthcare professional, and a confirmation email will be sent to this email address. If you do not have an email issued by your employer, please use your personal email."></i>
            </div>
            <input name="email" value="{{ old('email') }}" type="email" class="form-control" placeholder="" class="mb0" required />
          </div>
          <div class="mb20">
            <p>
              All of your personal information, including your name, email address, and physical address, will be kept confidential and
              will not be given out to third parties. We highly value your privacy.
            </p>
            <p>
              After submitting the application, you will be sent an email for verification.<br/>
              After verification of your email, please wait up to 24 hours for review of your request.<br />
              You will be notified by email about your verification status or any requests for further documentation.
            </p>
          </div>
          <div class="mb20 mt10">
            <div class="table-view">
              <div class="col-view">
                <label class="checkbox-default mr10">
                  <input id="agree" type="checkbox" name="agree" required>
                  <span class="ico-checkbox"></span>
                </label>
              </div>
              <div class="col-view">
                <label for="remember" class="mb0">
                  Click here to indicate that you have read and agree to the terms presented in the <a href="{{ url('post/terms-of-service') }}" target="_blank">Terms of Service</a>
                  and <a href="{{ url('post/privacy-policy') }}" target="_blank">Privacy Policy</a>
                </label>
              </div>
            </div>
          </div>
          <div class="mb20 text-center">
            <div class="g-recaptcha" data-sitekey="{{ $google_site_key }}"></div>
          </div>
          <div class="text-center">
            <input type="reset" class="btn btn-default fz16 mr10" value="Cancel" />
            <input id="submitRegister" type="submit" class="btn btn-green fz16" value="Submit" />
          </div>
        </div>
      </form>

    </div>
    <div class="col-xs-12 mt40 mb20">
      <div class="text-center">
        Already have account? Login
        <a data-toggle="modal" data-remote="false" data-target="#modal-login" href="{{ url('user/login') }}">
          Here
        </a>
      </div>
    </div>
  </div>
</div>
<script>
  // Radio checked
  function radioCheckedId() {
    var a = $('input[type=radio]:checked');
    var b = a.attr('id') + '-input';
    return b;
  }
  $(document).ready(function () {
    $('input[type=radio]').on('change',function(){
      var a = radioCheckedId();
      var b = '#'+a;
      $('.hide').removeClass('active');
      $('.hide input').removeAttr('required');
      $(b).addClass('active');
      $('.hide.active input').attr('required', 'required');
    });

    $('input[type=reset]').click(function (e) {
      if ( !confirm('Are you sure do you want to reset the form data?') ) {
        e.preventDefault();
      }
    });

    $('#submitRegister').click(function (e) {
      if ( $('input[name*=categories]:checked').length === 0 ) {
        alert("Please select at least one of your Medical Field of Interest");
        e.preventDefault();
        return;
      }
      if ( !($('#agree').is(':checked')) ) {
        alert("Please confirm that you're agree with our Terms of Services & Privacy Policy");
        e.preventDefault();
        return;
      }
    });
  });
</script>
<style>
  .g-recaptcha > div { margin: 0 auto; }
</style>
@stop
