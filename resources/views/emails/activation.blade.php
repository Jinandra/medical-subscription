<p>
  Hi {{ $user->fullname() }},<br/><br/>
  Please click on this link to verify your email:<br/>
</p>
<p>
  <a href='{{ url('/user/activation/?token='.$user->register_token.'&user='.$user->id) }}'>Verify</a> 
  <br/><br/>
  Or copy paste this url to your browser : <br/>
  {{ url('/user/activation/?token='.$user->register_token.'&user='.$user->id) }}
</p>
<br/><br/>
-- Enfolink Team --
