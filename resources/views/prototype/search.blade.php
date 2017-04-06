@extends('prototype.layout')

@section('title')
    Home Page
@stop

@section('content')
<br/><br/><br/><br/><br/>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <h4>Search Result : {{ Input::get('s') }}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2 col-md-offset-1">
            @if(count($media)>0)
                @foreach($media as $row)                
                    <div class="thumbnail">
                        <iframe style="width: 195px; height: 113px" src="https://www.youtube.com/embed/{{ $row->youtubeID  }}?showinfo=0" frameborder="0" allowfullscreen></iframe>
                        <div class="caption">
                            <span class="glyphicon glyphicon-info-sign pull-right" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ $row->description }}"></span>
                            <p>{{ $row->title }}
                            <br/>{{ $row->screen_name }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                No search result
            @endif
        </div>
        
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-offset-1">
            <?php 
                echo $media->appends(['s' => Input::get('s')])->render(); 
            ?>
        </div>
    </div>
@stop
