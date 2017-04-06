<h1 class="dib">in</h1>
<div class="dropdown-wrap">
    <div class="dib dropdown-title">
        <span>All category</span>
        <i class="fa fa-caret-down"></i>
    </div>
    <div class="dropdown-selection dropdown-selection-small">
        <div class="row row-md-gutter">
            @if(isset($categoryData))
                <?php 
                $j = 1; 
                $k = 0; 
                $countCategory = count($categoryData);
                $catColumn = round($countCategory/3);
                ?>
                @foreach($categoryData as $catVal )
                    @if( $k == 0 )
                        <div class="col-md-4">
                    @else
                        @if( ( $k%$catColumn ) == 0 )
                        <div class="col-md-4">
                        @endif
                    @endif
                    
                        @if(1==$j)
                            <div class="dropdown-selection-item active">All category</div>
                        @endif
                        <div class="dropdown-selection-item">{{ $catVal->name }}</div>
                    <?php $k++; $j++; ?>
                    @if( ( $k%$catColumn ) == 0 )
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
<p></p>