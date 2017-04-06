<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UCode history on PDF</title>
    <link href="{{ config('app.assets_path').'/css/pdfgenerate.css' }}" rel="stylesheet" type="text/css">
    <style>
        @media print {
            @page { margin: 28px 30px 0px; }
            body { margin: 0cm; }
        }
    </style>
</head>
<body style="margin-top:0px">
    <table style="width: 100%;">
        <tr style="text-align: center;">
            <td colspan="2" align="center">
                <img src="{{ config('app.assets_path').'/images/logo.png' }}" alt="" style="margin-top:-20px">
            </td>
        </tr>
        <tr style="text-align: center; padding-bottom: 10px">
            <td colspan="2">
                <div id="header">
                    <div id="search">
                        <div style="width:330px; float:left; text-align: left">{{ $ucodedetails->ucode }}</div> 
                        <div style=""><img src="{{ config('app.assets_path').'/images/ico-search.png' }}" alt="" style="width:25px;" /></div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="width:50px">
                <div class="circle-info"><img src="{{ config('app.assets_path').'/images/icimage.png' }}" alt="" style="width:25px" /></div>
            </td>
            <td>
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding:0px" class="headingtext">Instructions</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0px 0px 13px">
                            <table width="100%" class="list-item" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="liclasstd">
                                        <img src="{{ config('app.assets_path').'/images/liicon.png' }}" alt="" />
                                    </td>
                                    <td style="padding:0px">
                                        Go to <a href="#">www.Enfolink.org</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="liclasstd">
                                        <img src="{{ config('app.assets_path').'/images/liicon.png' }}" alt="" />
                                    </td>
                                    <td style="padding:0px">
                                        Type <strong>{{ $ucodedetails->ucode }}</strong> into the search box
                                    </td>
                                </tr>
                                <tr>
                                    <td class="liclasstd">
                                        <img src="{{ config('app.assets_path').'/images/liicon.png' }}" alt="" />
                                    </td>
                                    <td style="padding:0px">
                                        Press enter
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @if(!empty($medias))
        <tr>
            <td>
                <div class="circle-copy"><img src="{{ config('app.assets_path').'/images/cpimage.png' }}" alt="" style="width:25px" /></div>
            </td>
            <td>
                <div class="headingtext">Media</div>
            </td>
        </tr>
        <?php $i = 1; ?>
        @foreach($medias as $row)
        <tr>
            <td></td>
            <td>
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding:0px 0px 0px 0px;"><div class="innernumber">{{ $i }}</div></td>
                        <td style="padding-left:0px">
                            <div class="ordered-list">
                                <div class="ordered-list-item">
                                    <div class="headingtext3">{{ limitString($row->title, 80) }}</div>
                                    <div class="description">
                                    @if( $row->type != "" )
                                    <strong>{{ ucwords($row->type) }}:</strong>
                                    @endif
                                    {{ limitString($row->description, 520) }}
                                    </div>
                                </div>
                            </div>  
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php $i++; ?>
        @endforeach
        @else
            <tr><td colspan="2">Sorry, Media not available</td></tr>
        @endif
    </table>
</body>

</html>