<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Gold MS</title>

    <!-- Custom fonts for this template-->
    <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Custom styles for this template-->
    <link href="../../assets/css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top" @if(Config::get('app.locale') == 'ar') style="direction: rtl" @endif>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    @include('layouts.side' , ['slag' => 14 , 'subSlag' => 147])
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            @include('layouts.header')
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
                @include('flash-message')
                <div class="d-sm-flex align-items-center justify-content-between mb-4" style="padding: 8px">

                        <h1 class="h3 mb-0 text-primary-800 no-print">{{__('main.reports')}} / {{__('main.daily_all_movements')}}</h1>

                </div>

                <div class="card-body px-0 pt-0 pb-2">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary no-print">{{__('main.daily_all_movements')}}</h6>
                            <br>
                            <button type="button" class="btn btn-info no-print" id="btnPrint">Print</button>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>

                                    <tr>
                                        <th  class="text-center btn-success" colspan="{{(count($karats) * 2 )  + 1}}">{{__('main.enter')}}</th>
                                        <th  class="text-center btn-danger" colspan="{{(count($karats) * 2 )  + 1}}">{{__('main.exit')}}</th>
                                    </tr>
                                    <tr>
                                        <th  class="text-center btn-warning" colspan="{{count($karats) }}">{{__('main.new_gold')}}</th>
                                        <th  class="text-center btn-secondary" colspan="{{count($karats) }}">{{__('main.old_gold')}}</th>
                                        <th  class="text-center btn-dark" rowspan="2">{{__('main.money')}}</th>

                                        <th  class="text-center btn-warning" colspan="{{count($karats) }}">{{__('main.new_gold')}}</th>
                                        <th  class="text-center btn-secondary" colspan="{{count($karats) }}">{{__('main.old_gold')}}</th>
                                        <th  class="text-center btn-dark" rowspan="2">{{__('main.money')}}</th>
                                    </tr>
                                    <tr>
                                        @foreach($karats as $karat)
                                            <th class="text-center btn-primary">{{Config::get('app.locale') == 'ar' ?$karat -> name_ar : $karat -> name_en}}</th>
                                        @endforeach
                                        @foreach($karats as $karat)
                                            <th class="text-center btn-info">{{Config::get('app.locale') == 'ar' ?$karat -> name_ar : $karat -> name_en}}</th>
                                        @endforeach


                                            @foreach($karats as $karat)
                                                <th class="text-center btn-primary">{{Config::get('app.locale') == 'ar' ?$karat -> name_ar : $karat -> name_en}}</th>
                                            @endforeach
                                            @foreach($karats as $karat)
                                                <th class="text-center btn-info">{{Config::get('app.locale') == 'ar' ?$karat -> name_ar : $karat -> name_en}}</th>
                                            @endforeach

                                    </tr>



                                    </thead>
                                    <tbody>
                                            <tr>
                                                @foreach($karats as $karat)
                                                    @if( isset($work[$karat -> id]) )
                                                        <td class="text-center" style="color: green;font-size: 20px;font-weight: bold;">{{$work[$karat -> id]['enter_weight']}}</td>
                                                    @else
                                                        <td class="text-center" style="color: green;font-size: 20px;font-weight: bold;">0.0</td>
                                                    @endif

                                                @endforeach


                                                @foreach($karats as $karat)
                                                    @if(isset($old[$karat -> id]))
                                                        <td class="text-center" style="color: green;font-size: 20px;font-weight: bold;">{{$old[$karat -> id]['enter_weight']}}</td>
                                                    @else
                                                        <td class="text-center" style="color: green;font-size: 20px;font-weight: bold;"> 0.0</td>
                                                    @endif

                                                @endforeach

                                                    <?php $enter_money = 0  ?>


                                                    @foreach($enterMoney as $money)
                                                        <?php $enter_money += $money -> amount  ?>
                                                    @endforeach
                                                <td class="text-center" style="color: green;font-size: 20px;font-weight: bold;">
                                                    {{$enter_money}}</td>


                                                    @foreach($karats as $karat)
                                                        @if( isset($work[$karat -> id]) )
                                                            <td class="text-center" style="color: red;font-size: 20px;font-weight: bold;">{{$work[$karat -> id]['out_weight']}}</td>
                                                        @else
                                                            <td class="text-center" style="color: red;font-size: 20px;font-weight: bold;">0.0</td>
                                                        @endif

                                                    @endforeach


                                                    @foreach($karats as $karat)
                                                        @if(isset($old[$karat -> id]))
                                                            <td class="text-center" style="color: red;font-size: 20px;font-weight: bold;">{{$old[$karat -> id]['out_weight']}}</td>
                                                        @else
                                                            <td class="text-center" style="color: red;font-size: 20px;font-weight: bold;"> 0.0</td>
                                                        @endif

                                                    @endforeach

                                                    <?php $exit_money = 0  ?>
                                                    @foreach($exitMoney as $money)
                                                        <?php $exit_money += $money -> amount  ?>
                                                    @endforeach

                                                    <td class="text-center" style="color: red;font-size: 20px;font-weight: bold;">
                                                        {{$exit_money}}</td>
                                            </tr>


                                    </tbody>

                                </table>

                                 <h2 class="text-center" style="margin: 20px;">{{__('main.movements_net')}}</h2>

                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th  class="text-center btn-warning" colspan="{{count($karats) }}">{{__('main.new_gold')}}</th>
                                        <th  class="text-center btn-secondary" colspan="{{count($karats) }}">{{__('main.old_gold')}}</th>
                                        <th  class="text-center btn-dark" rowspan="2">{{__('main.money')}}</th>
                                    </tr>

                                    @foreach($karats as $karat)
                                        <th class="text-center btn-primary">{{Config::get('app.locale') == 'ar' ?$karat -> name_ar : $karat -> name_en}}</th>
                                    @endforeach

                                    @foreach($karats as $karat)
                                        <th class="text-center btn-info">{{Config::get('app.locale') == 'ar' ?$karat -> name_ar : $karat -> name_en}}</th>
                                    @endforeach

                                    </thead>

                                    <tbody>


                                    <tr style="background: antiquewhite;">
                                        @foreach($karats as $karat)
                                            @if( isset($work[$karat -> id]) )
                                                <td class="text-center"
                                                    @if($work[$karat -> id]['enter_weight'] - $work[$karat -> id]['out_weight'] >= 0) style="color: green; font-weight: bold; font-size: 30px;"
                                                    @else style="color: red; font-weight: bold; font-size: 30px;" @endif
                                                >{{$work[$karat -> id]['enter_weight'] - $work[$karat -> id]['out_weight']}}</td>
                                            @else
                                                <td class="text-center"  style="color: green">0.0</td>
                                            @endif
                                        @endforeach
                                        @foreach($karats as $karat)
                                            @if( isset($old[$karat -> id]) )
                                                <td  class="text-center"
                                                    @if($old[$karat -> id]['enter_weight'] - $old[$karat -> id]['out_weight'] >= 0) style="color: green; font-weight: bold; font-size: 30px;"
                                                    @else style="color: red; font-weight: bold; font-size: 30px;" @endif
                                                >{{$old[$karat -> id]['enter_weight'] - $old[$karat -> id]['out_weight']}}</td>
                                            @else
                                                <td class="text-center"  style="color: green">0.0</td>
                                            @endif

                                        @endforeach

                                        <td class="text-center"   @if($enter_money - $exit_money >= 0) style="color: green; font-weight: bold; font-size: 30px;"
                                            @else style="color: red; font-weight: bold; font-size: 30px;" @endif>{{$enter_money - $exit_money}}</td>
                                    </tr>

                                    </tbody>

                                </table>



                            </div>




                        </div>
                    </div>

                </div>


            </div>
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        @include('layouts.footer')
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


<script type="text/javascript">
    let id = 0;


    $(document).ready(function () {
        $(document).on('click', '#btnPrint', function (event) {
            printPage();

        });

    });


    function printPage() {
        var css = '@page { size: landscape; }',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet) {
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);
        window.print();
    }


</script>





<!-- Bootstrap core JavaScript-->
<script src="../../assets/vendor/jquery/jquery.min.js"></script>
<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../../assets/js/sb-admin-2.min.js"></script>



<!-- Page level custom scripts -->
<script src="../../assets/js/demo/datatables-demo.js"></script>

</body>

</html>
