Hi {{ $user->screen_name }},<br/><br/>
In order to reset your password, please follow this link: <br/>
<a href='{{ url('/password/reset/'.$token) }}'>Reset Password</a> 
<br/><br/>
Or copy paste this url to your browser : <br/>
{{ url('/password/reset/'.$token) }}
<br/><br/>
--Enfolink--
