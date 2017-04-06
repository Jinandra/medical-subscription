<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enfolink</title>
    <link href="{{ config('app.assets_path').'/css/pdfgeneratedownload.css' }}" rel="stylesheet" type="text/css">
    <!-- link href="{{ url('js/pdfgeneratedownload.css') }}" rel="stylesheet" type="text/css" -->
</head>
<body style="margin-top:0px">
    <table style="width: 100%">
        <tr style="text-align: center;">
            <td colspan="2" align="center">
                <img src="{{ config('app.assets_path').'/images/logo.png' }}" alt="">
            </td>
        </tr>
        <tr style="text-align: center; padding-bottom: 10px">
            <td align="center" colspan="2" id="header">
                <table align="center" cellpading="0" cellspacing="0" border="0">
                    <tr style="text-align: center; ">
                        <td align="center">
                            <table cellpading="0" cellspacing="0" border="0">
                                <tr>
                                    <td id="search">
                                        <table cellpading="0" cellspacing="0" border="0">
                                            <tr>
                                                <td><div style="width:335px; text-align: left; padding-left: 15px;">{{ $ucodedetails->ucode }}</div></td>
                                                <td><img src="{{ config('app.assets_path').'/images/ico-search.png' }}" alt="" style="width:25px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                <table style="width:695px; padding-top: 10px" align="center" cellpading="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding-top: 2px">
                            <img src="{{ config('app.assets_path').'/images/icimage.png' }}" alt="" style="width:25px" />
                        </td>
                        <td width="93%">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="headingtext">Instructions</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                <table style="width:695px" align="center" cellpading="0" cellspacing="0" border="0">
                    <tr>
                        <td></td>
                        <td width="93%">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding-left: 18px">
                                        <table width="100%" class="list-item" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td class="liclasstd">
                                                        <img src="{{ config('app.assets_path').'/images/liicon.png' }}" alt="" />
                                                </td>
                                                <td class="institle">
                                                    Go to <a href="#" style="padding-top: -10px">www.Enfolink.org</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="liclasstd">
                                                        <img src="{{ config('app.assets_path').'/images/liicon.png' }}" alt="" />
                                                </td>
                                                <td class="institle">
                                                        Type <strong>{{ $ucodedetails->ucode }}</strong> into the search box
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="liclasstd">
                                                        <img src="{{ config('app.assets_path').'/images/liicon.png' }}" alt="" />
                                                </td>
                                                <td style="color: #000000;">
                                                        Press enter
                                                </td>
                                            </tr>
                                        </table>
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
            <td align="center" colspan="2">
                <table style="width:695px" align="center" cellpading="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding-top: 5px">
                            <img src="{{ config('app.assets_path').'/images/cpimage.png' }}" alt="" style="width:25px" />
                        </td>
                        <td width="93%">
                            <div class="headingtext">Media</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php $i = 1; ?>
        @foreach($medias as $row)
        <tr>
            <td align="center" colspan="2">
                <table style="width:695px" align="center" cellpading="0" cellspacing="0" border="0">
                    <tr>
                        <td></td>
                        <td width="93%">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding:0px 0px 0px 0px;"><div class="innernumber">{{ $i }}</div></td>
                                    <td width="95%" style="padding-left:0px; text-align: left;">
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