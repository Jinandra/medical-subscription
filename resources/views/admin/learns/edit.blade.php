@include('admin.partial.header')
@include('admin.partial.sidebar')
@include('admin.partial.topnav')

<div class="right_col" role="main">
  <div class="page-title">
    <div class="title_left">
      <h3>Add New Learn</h3>
      @if (Session::has('message'))
        <div role="alert" class="alert alert-success">
          {{ Session::get('message') }}
        </div>
      @endif

      @if($errors->has())
        <div role="alert" class="alert alert-danger">
          @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      @endif
    </div>
  </div>
  <div class="clearfix"></div>
  <form method="post" action="{{URL::to('admin/learns/'.Input::get('id').'/update')}}"  class="form-horizontal form-label-left">
    @include('admin.learns.form')
  </form>
</div>

@include('admin.partial.footer')
