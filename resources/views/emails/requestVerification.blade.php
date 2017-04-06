<p>
  Username: {{ $user->screen_name }}<br/><br/>
  Firstname: {{ $user->first_name }}<br />
  Lastname: {{ $user->last_name }}<br />
</p>
<p>
  Medical Profession: {{ $user->medical_profession }}<br />
  Medical Degree: {{ $user->medical_degree }}<br />
  Office Address: {{ $user->office_address }}<br />
</p>
<p>
  Email Address: <a href="mailto:{{ $user->email }}">{{ $user->email }}</a><br/><br/>
  @if ($user->website_type === 'none')
    * User doesn't have website.
  @else
    @if ($user->website_type === 'profile')
      Profile page on employer's website: {{ $user->profile_website_url }}
    @else
      Employer's website: {{ $user->website_url }}
    @endif
  @endif
</p>
<br/>
<br/>
<br/>
Please take your action by following this link:<br/>
<a href='{{ url('/admin/user/'.$user->id) }}'>Review</a>
<br/><br/>
Or copy paste this url to your browser : <br/>
{{ url('/admin/user/'.$user->id) }}
<br/><br/>
--Enfolink--
