@include('admin.partial.header')
@include('admin.partial.sidebar')
@include('admin.partial.topnav')


<div class="right_col" role="main"> 
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Something Wrong</h2>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content">
                <p>{{ $message }}</p>
                <a href="javascript:history.back(-1)" class="btn btn-default">Back</a>
            </div>

        </div>
    </div>
    

</div>

@include('admin.partial.footerjs')
@include('admin.partial.footer')
