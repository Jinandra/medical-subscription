@include('admin.partial.header')
@include('admin.partial.sidebar')
@include('admin.partial.topnav')


<div class="right_col" role="main">	
  @include('admin.dashboard.users', ['user' => $user])
  @include('admin.dashboard.media', ['media' => $media])
  @include('admin.dashboard.collection', ['collection' => $collection])
  @include('admin.dashboard.ucode', ['ucode' => $ucode])
</div>

@include('admin.partial.footerjs')
@include('admin.partial.footer')
