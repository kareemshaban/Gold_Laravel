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
    <link href="../../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../../assets/css/sb-admin-2.css" rel="stylesheet">

    <style>
        th{
            border: solid 1px gray;
        }
        td{
            border: solid 1px gray;
        }
    </style>
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
                    <h1 class="h3 mb-0 text-primary-800 no-print">{{__('main.basic_data')}} / {{$type == 3 ? __('main.client_account') : __('main.supplier_account')}}</h1>
                </div>

                <div class="card-body px-0 pt-0 pb-2">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary no-print">{{$type == 3 ? __('main.client_account') : __('main.supplier_account')}}</h6>

                            <br>
                            <button type="button" class="btn btn-info no-print" id="btnPrint">Print</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 border">
                                    <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7" rowspan="2">#</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7 ps-2" rowspan="2">{{__('main.date')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7" rowspan="2">{{__('main.bill_no')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7" rowspan="2">{{__('main.document_type')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7" colspan="2">{{__('main.balance_gold')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"  colspan="2">{{__('main.balance')}}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7" colspan=".5">مدين</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7" colspan=".5">دائن</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7" colspan=".5">مدين</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7" colspan=".5">دائن</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sum_weight_d = 0 ;
                                    $sum_weight_c = 0 ;
                                    $sum_money_d = 0 ;
                                    $sum_money_c = 0 ;

                                    ?>
                                       @foreach($movements as $movement)
                                           <tr>
                                               <td class="text-center">{{$loop -> index + 1}}</td>
                                               <td class="text-center">{{$movement -> date}}</td>
                                               <td class="text-center">{{$movement -> bill_number}}</td>
                                               <td class="text-center">{{$movement -> invoice_type}}</td>
                                               <td class="text-center">{{ round($movement -> debit_gold , 2) }}</td>
                                               <td class="text-center">{{round($movement -> credit_gold , 2) }}</td>
                                               <td class="text-center">{{round($movement -> debit_money , 2)}}</td>
                                               <td class="text-center">{{round($movement -> credit_money , 2) }}</td>
                                           </tr>

                                           <?php
                                           $sum_weight_d += $movement ->  debit_gold;
                                           $sum_weight_c += $movement ->  credit_gold ;
                                           $sum_money_d  += $movement ->  debit_money ;
                                           $sum_money_c  += $movement ->  credit_money ;

                                           ?>
                                       @endforeach

                                       <tr style="background: antiquewhite; font-weight: bold">
                                           <td class="text-center">الإجمالي</td>
                                           <td class="text-center"></td>
                                           <td class="text-center"></td>
                                           <td class="text-center"></td>
                                           <td class="text-center">{{round($sum_weight_d , 2) }}</td>
                                           <td class="text-center">{{round($sum_weight_c , 2) }}</td>
                                           <td class="text-center">{{round($sum_money_d , 2) }}</td>
                                           <td class="text-center">{{round($sum_money_c , 2)}}</td>

                                       </tr>
                                    <tr style="background: lightblue; font-weight: bold">
                                        <td class="text-center">الصافي</td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center" colspan="2">{{round($sum_weight_d  - $sum_weight_c, 2) }}</td>
                                        <td class="text-center" colspan="2">{{round($sum_money_d  - $sum_money_c, 2) }}</td>


                                    </tr>
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


<script src="../../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="../../assets/js/demo/datatables-demo.js"></script>

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
