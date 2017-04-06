<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>View User</title>   
    <link href="{{URL::asset('resources/assets/gente-admin/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('resources/assets/gente-admin/fonts/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('resources/assets/gente-admin/css/animate.min.css')}}" rel="stylesheet"> 
    <script src="{{URL::asset('resources/assets/gente-admin/js/jquery.min.js')}}"></script> 
    <link href="{{URL::asset('resources/assets/gente-admin/js/jquery-ui/jquery-ui.css')}}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        body{
            padding:25px;
        }
    </style>
</head>
<body>      
    <div class="modal-body">       
        
        <h2>User</h2>        
       
        <table class="table table-striped responsive-utilities jambo_table bulk_action">           
            <tbody>
                <tr class="pointer">                
                    <td width="30%">ID</td>
                    <td class=" ">{{ $user->id }}</td>                
                </tr>
                <tr class="pointer">                
                    <td width="30%">Name</td>
                    <td class=" ">{{ $user->name }} / {{ $user->screen_name }}</td>                
                </tr>
                <tr class="pointer">                
                    <td width="30%">Email</td>
                    <td class=" ">{{ $user->email }}</td>                
                </tr>                
                <tr class="pointer">
                    <td width="30%">Address</td>
                    <td class=" ">{{ $user->address }}</td>
                </tr>
                <tr class="pointer">
                    <td width="30%">Birthdate</td>
                    <td class=" ">{{ $user->birthday }}</td>                
                </tr>
                <tr class="pointer">
                    <td width="30%">Country</td>
                    <td class=" ">{{ $user->country }}</td>                
                </tr>
                <tr class="pointer">
                    <td width="30%">mobile Number</td>
                    <td class=" ">{{ $user->mobile_number }}</td>                
                </tr>
                <tr class="pointer">
                    <td width="30%">Alt Phone Number</td>
                    <td class=" ">{{ $user->alt_phone_number }}</td>                
                </tr>
                <tr class="pointer">
                    <td width="30%">Work Number</td>
                    <td class=" ">{{ $user->work_number }}</td>                
                </tr>
                <tr class="pointer">
                    <td width="30%">Hometown</td>
                    <td class=" ">{{ $user->hometown }}</td>                
                </tr>
                <tr class="pointer">
                    <td width="30%">Gender</td>
                    <td class=" ">{{ $user->gender }}</td>                
                </tr>
                <tr class="pointer">
                    <td width="30%">Created At</td>
                    <td class=" ">{{ $user->created_at }}</td>                
                </tr>
                <tr class="pointer">
                    <td width="30%">Last Modified</td>
                    <td class=" ">{{ $user->updated_at }}</td>                
                </tr>

                <tr class="pointer">                
                    <td width="30%">Status</td>
                    <td class=" ">{{ $user->user_status }}</td>                
                </tr>                
                <tr class="pointer">                
                    <td width="30%">Role</td>
                    <td class=" ">{{ $user->getDisplayRoleName() }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

<script src="{{URL::asset('resources/assets/gente-admin/js/bootstrap.min.js')}}"></script>

<script src="{{URL::asset('resources/assets/gente-admin/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('resources/assets/gente-admin/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('resources/assets/gente-admin/js/datepicker/daterangepicker.2.1.17.js')}}"></script>
<script src="{{URL::asset('resources/assets/gente-admin/js/jquery-ui/jquery-ui.min.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        
    });
</script>

</html>
