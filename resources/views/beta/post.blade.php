@extends((isset($auth) && $auth)? 'beta.userLayout' : 'beta.layout')

@section('title')
  {{ $post->title }} | Enfolink
@stop

@section('content')
@if(!(isset($auth) && $auth))
<div class="container pr">
<div class="row">
    <div class=" col-xs-12 col-sm-10 col-sm-offset-1 right-column unregisterd">
@else
    <div class="col-sm-9 col-md-10 col-xs-9 right-column">
@endif
            <div class="content">
                <div class="headings nomarge">
                    <h1>{{ $post->title }}</h1>
                    <p></p>
                </div>
                <div class="container">
                    <div class="row mt20">
                        <div class="col-sm-3 col-xs-12">
                             <ul class="fz16 menu-sidebar">
                               @foreach ($posts as $p)
                                <li class="{{ (Request::segment(2)==$p->slug) ? 'active': '' }}">
                                    <a href="{{ url('post/'.$p->slug) }}">{{ $p->title }}</a>
                                </li>
                               @endforeach
                            </ul>
                        </div>
                        <div class="col-sm-9 col-xs-12 fz16">
                             {!! $post->content !!}
                        </div>
                    </div>
                </div>
            </div>
            <div id="overlay"></div>
        </div>
@if(!(isset($auth) && $auth))
    </div>
</div>
@endif
<script type="text/javascript">
    $(function() {
        
    });
</script>

@stop

