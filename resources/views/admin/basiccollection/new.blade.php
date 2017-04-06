@include('admin.partial.header')
@include('admin.partial.sidebar')
@include('admin.partial.topnav')

<div class="right_col" role="main">
  @include('admin.basiccollection.form', [
    'media' => $media
  ])
</div>

@include('admin.partial.footerjs')
@include('admin.partial.footer')
