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
    @include('layouts.side' , ['slag' => 14 , 'subSlag' => 144])    <!-- End of Sidebar -->

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
                <div class="d-sm-flex align-items-center justify-content-between mb-4 no-print" style="padding: 8px">
                    <h1 class="h3 mb-0 text-primary-800 no-print">{{__('main.reports')}} / {{__('main.purchase_report')}}</h1>

                </div>

                <div class="card-body px-0 pt-0 pb-2">

                    <div class="card shadow mb-4 ">
                        <div class="card-header py-3 ">
                            <h6 class="m-0 font-weight-bold text-primary no-print">{{__('main.purchases_list')}}</h6> <br>
                            <button type="button" class="btn btn-info no-print" id="btnPrint">Print</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">
                                            #
                                        </th>
                                        <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7 ps-2">{{__('main.date')}}</th>
                                        <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7 ps-2">{{__('main.bill_no')}}</th>
                                        <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7 ps-2">{{__('main.document_type')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.karat')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.weight')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.made_Value_t')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.total_weight21')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.net_money')}} </th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $sum_weight = 0 ?>
                                    <?php $sum_total = 0 ?>
                                    <?php $sum_tax = 0 ?>
                                    <?php $sum_made = 0 ?>
                                    <?php $sum_net = 0 ?>
                                    @foreach($bills as $item)
                                        <tr>
                                            <td class="text-center">{{$loop -> index + 1}}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($item -> date) -> format('d-m-Y')  }}</td>
                                            <td class="text-center">{{$item -> bill_number}}</td>
                                            <td class="text-center">{{$item -> type == 1 ? __('main.new_gold') : __('main.old_gold')  }}</td>
                                            <td class="text-center">{{ (Config::get('app.locale') == 'ar' ? $item -> karat_name_ar : $item -> karat_name_en)  }}</td>
                                            <td class="text-center">{{$item -> weight}}</td>
                                            <td class="text-center">{{$item -> made_money}}</td>
                                            <td class="text-center">{{$item -> weight21}}</td>
                                            <td class="text-center">{{$item -> net_money}}</td>

                                        </tr>
                                        <?php $sum_weight += $item -> weight ?>
                                        <?php $sum_made += $item -> made_money ?>
                                        <?php $sum_net += $item -> weight21 ?>
                                        <?php $sum_tax += $item -> net_money ?>
                                    @endforeach
                                    <tr style="background: antiquewhite; font-weight: bold">
                                        <td class="text-center">الإجمالي</td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">{{$sum_weight}}</td>
                                        <td class="text-center">{{$sum_made}}</td>
                                        <td class="text-center">{{$sum_net}}</td>
                                        <td class="text-center">{{$sum_tax}}</td>
                                    </tr>
                                    </tbody>

                                </table>

                                <h2 class="text-center">الإجماليات حسب العيار</h2>
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">
                                            #
                                        </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.karat')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.net_weight')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.total_weight21')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.net_money')}} </th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach(Config::get('app.locale') == 'ar' ? $grouped_ar : $grouped_en as $group => $items)
                                        <?php $sum_weight_group = 0 ?>
                                        <?php $sum_made_g = 0 ?>
                                        <?php $sum_net_g = 0 ?>
                                        <?php $weight21 = 0 ?>
                                        @foreach($items as $item)
                                            <?php $sum_weight_group += $item -> weight ?>
                                            <?php $sum_made_g += $item -> made_money ?>
                                            <?php $sum_net_g += $item -> net_money ?>
                                            <?php $weight21 += $item -> weight21 ?>
                                        @endforeach
                                        <tr>
                                            <td class="text-center">{{$loop -> index + 1}}</td>
                                            <td class="text-center">{{$group}}</td>
                                            <td class="text-center"> {{$sum_weight_group}} </td>
                                            <td class="text-center"> {{$weight21}} </td>

                                            <td class="text-center">{{$sum_net_g}} </td>
                                        </tr>
                                    @endforeach

                                    </tbody>

                                </table>


                            </div>


                        </div>
                    </div>

                </div>


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



<!-- Bootstrap core JavaScript-->
<script src="../../assets/vendor/jquery/jquery.min.js"></script>
<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../../assets/js/sb-admin-2.min.js"></script>



<!-- Page level custom scripts -->

<script type="text/javascript">
    let id = 0;


    $(document).ready(function () {
        $(document).on('click', '#btnPrint', function (event) {
            printPage();

        });

    });
    function printPage(){
        var css = '@page { size: landscape; }',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet){
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);

        window.print();
    }
</script>

</body>

</html>



