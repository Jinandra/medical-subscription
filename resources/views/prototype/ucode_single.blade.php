@extends('prototype.layout')

@section('title')
    Ucode
@stop

@section('content')
<br/><br/><br/><br/><br/>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <h4>aaUCODE : {{ Request::segment(2) }}</h4>            
        </div>
    </div>    
    
    <div class="row">        
        <div class="col-sm-2 col-md-offset-1">
            <?php
                if(count($ucode)>0):
                    $items = App\Models\DetailUcode::where('ucode',$ucode->ucode)->get();                    
            ?>
                @foreach($items as $item)                
                    <?php $row = App\Models\Media::find($item->id_media)?>
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
            <?php else:?>
                <h4>Ucode Not Found.</h4>
            <?php endif;?>
        </div>        
    </div>    
    
@stop
