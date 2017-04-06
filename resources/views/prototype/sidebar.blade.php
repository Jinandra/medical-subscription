<li class='<?php echo (Request::segment(1)=='user' && Request::segment(2)=='')?'active':''; ?>'><a href="{{  url('/user') }}">HOME</a></li>
<li class='<?php echo (Request::segment(1)=='user' && Request::segment(2)=='profile')?'active':''; ?>'><a href="{{  url('/user/profile') }}">PROFILE</a></li>
<li class='<?php echo (Request::segment(1)=='list')?'active':''; ?>'><a href="{{  url('/list') }}">LIST</a></li>
<li class='<?php echo (Request::segment(1)=='bundle')?'active':''; ?>'><a href="{{  url('/bundle') }}">BUNDLE</a></li>
<li class='<?php echo (Request::segment(1)=='contribute')?'active':''; ?>'><a href="{{  url('/contribute') }}">CONTRIBUTE</a></li>
<li class='<?php echo (Request::segment(1)=='account')?'active':''; ?>'><a href="{{  url('/account') }}">ACCOUNT</a></li>
