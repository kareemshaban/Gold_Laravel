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
    @include('layouts.side' , ['slag' => $slag , 'subSlag' => $subSlag])
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
                    @if($slag == 3)
                    <h1 class="h3 mb-0 text-primary-800 no-print">{{__('main.items')}} / {{__('main.gold_stock')}}</h1>
                        @else
                        <h1 class="h3 mb-0 text-primary-800 no-print">{{__('main.reports')}} / {{__('main.gold_stock_report')}}</h1>
                    @endif
                </div>

                <div class="card-body px-0 pt-0 pb-2">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary no-print">{{__('main.gold_stock')}}</h6>
                            <br>
                            <button type="button" class="btn btn-info no-print" id="btnPrint">Print</button>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <h3 class="text-center" style="margin: 15px auto ;">{{__('main.gold_stock_by_karat')}}</h3>
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-md-center font-weight-bolder opacity-7 ps-2 btn-info" colspan="{{count($karats) * 2}}">{{__('main.new_gold')}}</th>
                                        <th class="text-center text-uppercase text-md-center font-weight-bolder opacity-7 ps-2 btn-primary" colspan="{{count($karats) * 2}}">{{__('main.old_gold')}}</th>
                                    </tr>

                                    <tr>
                                        @foreach($karats as $karat)
                                            <th class="text-center btn-info" colspan="2">{{Config::get('app.locale') == 'ar' ?$karat -> name_ar : $karat -> name_en}}</th>
                                        @endforeach
                                            @foreach($karats as $karat)
                                                <th class="text-center btn-primary" colspan="2"> {{Config::get('app.locale') == 'ar' ?$karat -> name_ar : $karat -> name_en}}</th>
                                            @endforeach

                                    </tr>
                                    <tr>
                                        @foreach($karats as $karat)
                                            <th  class="text-center btn-success">{{__('main.enter')}}</th>
                                            <th  class="text-center btn-danger">{{__('main.exit')}}</th>
                                        @endforeach
                                            @foreach($karats as $karat)
                                                <th  class="text-center btn-success">{{__('main.enter')}}</th>
                                                <th  class="text-center btn-danger">{{__('main.exit')}}</th>
                                            @endforeach
                                    </tr>


                                    </thead>
                                    <tbody>
                                    @foreach($karats as $karat)
                                     @if( isset($work[$karat -> id]) )
                                         <td class="text-center" style="color: green">{{$work[$karat -> id]['enter_weight']}}</td>
                                         <td class="text-center" style="color: red">{{$work[$karat-> id]['out_weight']}}</td>
                                       @else
                                         <td class="text-center" style="color: green">0.0</td>
                                         <td class="text-center" style="color: red">0.0</td>
                                     @endif

                                    @endforeach



                                    @foreach($karats as $karat)
                                        @if(isset($old[$karat -> id]))
                                            <td class="text-center" style="color: green">{{$old[$karat -> id]['enter_weight']}}</td>
                                            <td class="text-center" style="color: red">{{$old[$karat-> id]['out_weight']}}</td>
                                        @else
                                            <td class="text-center" style="color: green"> 0.0</td>
                                            <td class="text-center" style="color: red">0.0</td>
                                        @endif

                                    @endforeach


                                    <tr style="background: antiquewhite;">
                                        @foreach($karats as $karat)
                                            @if( isset($work[$karat -> id]) )
                                            <td colspan="2" class="text-center"
                                            @if($work[$karat -> id]['enter_weight'] - $work[$karat -> id]['out_weight'] >= 0) style="color: green; font-weight: bold; font-size: 30px;"
                                                @else style="color: red; font-weight: bold; font-size: 30px;" @endif
                                                >{{$work[$karat -> id]['enter_weight'] - $work[$karat -> id]['out_weight']}}</td>
                                         @else
                                                <td class="text-center" colspan="2" style="color: green">0.0</td>
                                            @endif
                                        @endforeach
                                            @foreach($karats as $karat)
                                                @if( isset($old[$karat -> id]) )
                                                    <td colspan="2" class="text-center"
                                                        @if($old[$karat -> id]['enter_weight'] - $old[$karat -> id]['out_weight'] >= 0) style="color: green; font-weight: bold; font-size: 30px;"
                                                        @else style="color: red; font-weight: bold; font-size: 30px;" @endif
                                                    >{{$old[$karat -> id]['enter_weight'] - $old[$karat -> id]['out_weight']}}</td>
                                                @else
                                                    <td class="text-center" colspan="2" style="color: green">0.0</td>
                                                @endif

                                            @endforeach
                                    </tr>




                                    </tbody>

                                </table>

                                <h3 class="text-center" style="margin: 15px auto ;">{{__('main.gold_stock_by_21')}}</h3>
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-md-center font-weight-bolder opacity-7 ps-2 btn-info" colspan="2">{{__('main.new_gold')}}</th>
                                        <th class="text-center text-uppercase text-md-center font-weight-bolder opacity-7 ps-2 btn-primary" colspan="2">{{__('main.old_gold')}}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center btn-info" colspan="2">{{Config::get('app.locale') == 'ar' ? 'عيار 21' : 'Karat 21'}}</th>
                                        <th class="text-center btn-info" colspan="2">{{Config::get('app.locale') == 'ar' ? 'عيار 21' : 'Karat 21'}}</th>
                                    </tr>
                                    <tr>
                                        <th  class="text-center btn-success">{{__('main.enter')}}</th>
                                        <th  class="text-center btn-danger">{{__('main.exit')}}</th>
                                        <th  class="text-center btn-success">{{__('main.enter')}}</th>
                                        <th  class="text-center btn-danger">{{__('main.exit')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php  $in_work_gold = 0 ;
                                    $out_work_gold = 0 ;
                                    $in_old_gold = 0 ;
                                    $out_old_gold = 0 ;
                                    ?>
                                    @foreach($karats as $karat)
                                        @if( isset($work[$karat -> id]) )
                                            <?php  $in_work_gold += $work[$karat -> id]['enter_weight']  * $karat -> transform_factor ;
                                            $out_work_gold += $work[$karat -> id]['out_weight']  * $karat -> transform_factor ;

                                            ?>
                                        @endif
                                        @if( isset($old[$karat -> id]) )
                                            <?php
                                            $in_old_gold += $old[$karat -> id]['enter_weight']  * $karat -> transform_factor ;
                                            $out_old_gold += $old[$karat -> id]['out_weight']  * $karat -> transform_factor ;
                                            ?>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td class="text-center"  style="color: green">{{ round($in_work_gold , 2) }}</td>
                                        <td class="text-center" style="color: red">{{round($out_work_gold , 2)}}</td>
                                        <td class="text-center"  style="color: green">{{round($in_old_gold , 2)}}</td>
                                        <td class="text-center" style="color: red">{{round($out_old_gold , 2)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center"
                                            @if($in_work_gold - $out_work_gold >= 0) style="color: green; font-weight: bold; font-size: 30px;"
                                            @else style="color: red; font-weight: bold; font-size: 30px;" @endif
                                        >{{ round($in_work_gold - $out_work_gold , 2) }}</td>
                                        <td colspan="2" class="text-center"
                                            @if($in_old_gold - $out_old_gold >= 0) style="color: green; font-weight: bold; font-size: 30px;"
                                            @else style="color: red; font-weight: bold; font-size: 30px;" @endif
                                        >{{round($in_old_gold - $out_old_gold , 2)}}</td>
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
