<p>
  Congratulations {{ $user->fullname() }}! Your application has been approved.
</p>
<p>
  Login now at <a href="{{ url('/?modal=login') }}">Enfolink.com</a><br />
  With username: <strong>{{ $user->screen_name }}</strong>
</p>
<p>
  Thank You,
</p>
<br/><br/>
-- Enfolink Team --
