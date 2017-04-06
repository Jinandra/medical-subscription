    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script src="{{URL::asset('resources/assets/gente-admin/js/progressbar/bootstrap-progressbar.min.js')}}"></script>
    <script src="{{URL::asset('resources/assets/gente-admin/js/nicescroll/jquery.nicescroll.min.js')}}"></script>
    <script src="{{URL::asset('resources/assets/gente-admin/js/icheck/icheck.min.js')}}"></script>    
    <script type="text/javascript" src="{{URL::asset('resources/assets/gente-admin/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('resources/assets/gente-admin/js/datepicker/daterangepicker.2.1.17.js')}}"></script>
    <script src="{{URL::asset('resources/assets/gente-admin/js/input_mask/jquery.inputmask.js')}}"></script>
    <script src="{{URL::asset('resources/assets/gente-admin/js/custom.js')}}"></script>
    <script src="{{URL::asset('resources/assets/emodal/eModal.min.js')}}"></script>

    <script>
       // NProgress.done();

        $(document).ready(function () {
            $(".mask").inputmask();
            $(":input").inputmask();    

            $('.datepicker').daterangepicker({
                locale: {
                  format: 'YYYY-MM-DD'
                },
                singleDatePicker: true,    
                showDropdowns : true,
                autoUpdateInput : true
            });                

            $('.rangepicker').daterangepicker({
                locale: {
                  format: 'YYYY/MM/DD'
                },
                startDate: "<?php echo date('Y/m/d')?>",                
                singleDatePicker: false,    
                showDropdowns : true,
                
            });      
        });

    </script>
