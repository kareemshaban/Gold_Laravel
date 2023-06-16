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

    <!-- Custom styles for this template-->
    <link href="../../assets/css/sb-admin-2.css" rel="stylesheet">


    <style>
        .quick-button.small {
            padding: 15px 0px 1px 0px;
            font-size: 13px;
            border-radius: 15px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            width: 100%;
            height: 100%;
        }
        .quick-button.small:hover{
            transform: scale(1.1);
        }
        .quick-button {
            margin-bottom: -1px;
            padding: 30px 0px 10px 0px;
            font-size: 15px;
            display: block;
            text-align: center;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            opacity: 0.9;
        }
        .bblue {
            background: darkgoldenrod !important;
        }.white {
             color: white !important;
         }
        .bdarkGreen {
            background: #78cd51 !important;
        }
        .blightOrange {
            background: #fabb3d !important;
        }.bred {
             background: #ff5454 !important;
         }
        .bpink {
            background: #e84c8a !important;
        }
        .bgrey {
            background: #b2b8bd !important;
        }
        .blightBlue {
            background: #5BC0DE !important;
        }
        .padding1010 {
            padding: 10px;
        }


    </style>

</head>

<body id="page-top"  @if(Config::get('app.locale') == 'ar') style="direction: rtl" @endif>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
     @include('layouts.side' , ['slag' => 1 , 'subSlag' => 0])
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

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{{__('main.cpanel')}}</h1>
                </div>

                <!-- Content Row -->
                <div class="row" >

                    <div class="col-lg-12">

                        <div class="box" style="padding-bottom: 30px; width: 90%; margin: auto">
                            <div class="box-header">
                                <h2 class="blue" style="color: goldenrod !important;"><i class="fa fa-th"></i><span class="break"></span> {{__('main.prices')}}</h2>

                            </div>
                            <div class="box-content" style="display: flex;flex-flow: wrap; padding: 20px; background: whitesmoke;">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7 ps-2">{{__('main.ounce')}}</th>
                                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.k18')}}</th>
                                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.k24')}} </th>
                                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.k21')}} </th>
                                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.currency')}} </th>
                                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.last_update')}} </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pricings as $pricing)
                                            <tr>
                                                <td class="text-center">{{number_format($pricing -> price  , 2)}}  </td>
                                                <td class="text-center">{{number_format($pricing -> price_18  , 2)}}</td>
                                                <td class="text-center">{{number_format($pricing -> price_24  , 2)}}</td>
                                                <td class="text-center">{{number_format($pricing -> price_21  , 2)}}</td>
                                                <td class="text-center">{{$pricing -> currency}}</td>
                                                <td class="text-center">{{$pricing -> last_Update}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Content Row -->


                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-lg-12">
                            <div class="box" style="padding-bottom: 30px; width: 90%; margin: auto">
                                <div class="box-header">
                                    <h2 class="blue" style="color: goldenrod !important;"><i class="fa fa-th"></i><span class="break"></span>روابط سريعة</h2>
                                </div>
                                <div class="box-content" style="display: flex;flex-flow: wrap; padding: 20px; background: whitesmoke;">
                                    <div class="col-xl-3 col-sm-6  col-6  mb-xl-0 mb-4 padding1010">
                                        <a class="bblue white quick-button small"  @if(in_array('items' , $routes)) href="{{route('items')}}" @endif>
                                            <i class="fa fa-barcode"></i>

                                            <p>{{__('main.items')}}</p>
                                        </a>
                                    </div>
                                    <div class="col-xl-3 col-sm-6  col-6 mb-xl-0 mb-4 padding1010">
                                        <a class="bdarkGreen white quick-button small" @if(in_array('workEntryAll' , $routes)) href="{{route('workEntryAll')}}" @endif >
                                            <i class="fa fa-heart"></i>

                                            <p>{{__('main.enter_work')}}</p>
                                        </a>
                                    </div>

                                    <div class="col-xl-3 col-sm-6  col-6 mb-xl-0 mb-4 padding1010">
                                        <a class="blightOrange white quick-button small" @if(in_array('daily_all_movements' , $routes)) href="{{route('daily_all_movements')}}" @endif >
                                            <i class="fa fa-heart"></i>

                                            <p> {{__('main.daily_all_movements')}}</p>
                                        </a>
                                    </div>

                                    <div class="col-xl-3 col-sm-6  col-6 mb-xl-0 mb-4 padding1010">
                                        <a class="bred white quick-button small"  @if(in_array('item_list_report' , $routes)) href="{{route('item_list_report')}}" @endif >
                                            <i class="fa fa-star"></i>

                                            <p> {{__('main.item_list_report')}}</p>
                                        </a>
                                    </div>



                                    <div class="col-xl-3 col-sm-6  col-6 mb-xl-0 mb-4 padding1010">
                                        <a class="bgrey white quick-button small" @if(in_array('clients/3' , $routes)) href="{{route('clients' , 3)}}" @endif>
                                            <i class="fa fa-users"></i>

                                            <p>{{__('main.clients')}}</p>
                                        </a>
                                    </div>

                                    <div class="col-xl-3 col-sm-6  col-6 mb-xl-0 mb-4 padding1010">
                                        <a class="bgrey white quick-button small" @if(in_array('clients/4' , $routes)) href="{{route('clients' , 4)}}" @endif>
                                            <i class="fa fa-users"></i>

                                            <p>{{__('main.supplier')}}</p>
                                        </a>
                                    </div>





                                    <div class="col-xl-3 col-sm-6  col-6 mb-xl-0 mb-4 padding1010">
                                        <a class="bdarkGreen white quick-button small"  @if(in_array('prices' , $routes)) href="{{route('prices' )}}" @endif>
                                            <i class="fa fa-credit-card"></i>

                                            <p>{{__('main.prices')}}</p>
                                        </a>
                                    </div>

                                    <div class="col-xl-3 col-sm-6  col-6 mb-xl-0 mb-4 padding1010">
                                        <a class="bblue white quick-button small"  @if(in_array('gold_stock' , $routes)) href="{{route('gold_stock' )}}" @endif>
                                            <i class="fa fa-credit-card"></i>

                                            <p>{{__('main.gold_stock')}}</p>
                                        </a>
                                    </div>


                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                <!-- Content Row -->


            </div>
            <!-- /.container-fluid -->

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

<!-- Logout Modal-->


<!-- Bootstrap core JavaScript-->
<script src="../../assets/vendor/jquery/jquery.min.js"></script>
<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../../assets/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../../assets/vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="../../assets/js/demo/chart-area-demo.js"></script>
<script src="../../assets/js/demo/chart-pie-demo.js"></script>

</body>

</html>
