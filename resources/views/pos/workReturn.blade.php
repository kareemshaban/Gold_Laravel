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


</head>

<body id="page-top" @if(Config::get('app.locale') == 'ar') style="direction: rtl" @endif>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    @include('layouts.side' , ['slag' => 15 , 'subSlag' => 151])
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            @include('layouts.header')
            <!-- End of Topbar -->

            <!-- Begin Page Content -->


            <div class="container-fluid ">

                @include('flash-message')


                <div class="card-body px-0 pt-0 pb-2">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form method="POST" action="{{ route('return_work_post') }}"
                                  enctype="multipart/form-data" id="pos_sales_form">
                                @csrf
                                <div class="row">
                                    <div class="card shadow mb-4 col-9">
                                        <div class="card-header py-3">
                                            <div class="row">

                                                <h6 class="m-0 font-weight-bold text-primary text-right"
                                                    style=" margin-bottom: 20px !important; width: 100%; ">{{__('main.return_sales')}}</h6>


                                            </div>


                                        </div>
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label style="float: right;">{{ __('main.bill_date') }} <span
                                                                style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                        </label>
                                                        <input type="text"
                                                               class="form-control" value="{{$bill -> date}}" readonly
                                                        />
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label style="float: right;">{{ __('main.bill_number') }} <span
                                                                style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                        </label>
                                                        <input type="text" value="{{$bill -> bill_number}}"
                                                               class="form-control" placeholder="bill_number" readonly
                                                        />
                                                        <input type="hidden" value="{{$bill -> id}}" id="bill_id" name="bill_id"
                                                               class="form-control" placeholder="bill_id" readonly
                                                        />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-6 ">
                                                    <div class="form-group">
                                                        <label style="float: right;">{{ __('main.clients') }} <span
                                                                style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="customer_id"
                                                               id="customer_id" value="{{$bill -> vendor_name}}"
                                                               readonly>

                                                    </div>
                                                </div>
                                                <div class="col-6 ">
                                                    <div class="form-group">
                                                        <label style="float: right;">{{ __('main.bill_client_name') }}
                                                            <span
                                                                style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                        </label>
                                                        <input type="text" name="bill_client_name" id="bill_client_name"
                                                               class="form-control"
                                                               value="{{$bill -> bill_client_name}}" readonly>


                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-md-12">


                                                    <div class="card mb-4">
                                                        <div class="card-header pb-0">
                                                            <h4 class="table-label text-center">{{__('main.items')}} </h4>

                                                            <div class="row">

                                                            </div>

                                                        </div>

                                                        <div class="card-body px-0 pt-0 pb-2">
                                                            <div class="table-responsive p-0">


                                                                <table id="sTable"
                                                                       class="table items table-striped table-bordered table-condensed table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th hidden>id</th>
                                                                        <th class="text-center">{{__('main.item')}}</th>
                                                                        <th class="text-center">{{__('main.karat')}}</th>
                                                                        <th class="text-center">{{__('main.weight')}}</th>
                                                                        <th class="text-center">{{__('main.price_gram')}} </th>
                                                                        <th class="text-center">{{__('main.gram_made_value')}}</th>
                                                                        <th class="text-center">{{__('main.gram_tax')}}</th>
                                                                        <th class="text-center">{{__('main.net_money')}}</th>
                                                                        <th class="text-center">
                                                                            <input class="form-control" id="checkAll"
                                                                                   name="checkAll" type="checkbox">
                                                                        </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="tbody">
                                                                    @foreach($details as $detail)
                                                                        <tr>
                                                                            <td class="text-center"
                                                                                hidden> {{$detail ->id}}</td>
                                                                            <td class="text-center">{{Config::get('app.locale') == 'ar' ? $detail ->item_ar : $detail ->item_en}}</td>
                                                                            <td class="text-center">{{Config::get('app.locale') == 'ar' ? $detail ->karat_ar : $detail ->karat_en}}</td>
                                                                            <td class="text-center">{{$detail ->weight}}</td>
                                                                            <td class="text-center">{{$detail -> gram_price}}</td>
                                                                            <td class="text-center">{{$detail -> gram_manufacture}}</td>
                                                                            <td class="text-center">{{$detail ->gram_tax}}</td>
                                                                            <td class="text-center">{{$detail ->net_money}}</td>
                                                                            <td class="text-center"><input
                                                                                    class="form-control checkDetail"
                                                                                    name="checkDetail[]" type="checkbox"
                                                                                    value="{{$detail -> id}}"></td>


                                                                        </tr>

                                                                    @endforeach

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="card shadow mb-4 col-3">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">{{__('main.sales_invoice_total')}}</h6>
                                        </div>
                                        <div class="card-body ">
                                            <div class="row document_type1"
                                                 style="align-items: center; margin-bottom: 10px;">
                                                <div class="col-6">
                                                    <label
                                                        style="text-align: right;float: right;"> {{__('main.items_count')}} </label>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control" id="items_count"
                                                           value="{{count($details) }}">
                                                </div>
                                            </div>
                                            <div class="row" style="align-items: center; margin-bottom: 10px;">
                                                <div class="col-6">
                                                    <label
                                                        style="text-align: right;float: right;"> {{__('main.total_weight21')}} </label>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control"
                                                           id="total_weight21" name="total_weight21"
                                                           value="{{$bill -> total21_gold}}">
                                                </div>
                                            </div>


                                            <div class="row" style="align-items: center; margin-bottom: 10px;">
                                                <div class="col-6">
                                                    <label
                                                        style="text-align: right;float: right;"> {{__('main.additional_tax')  }} </label>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control" id="tax" name="tax"
                                                           value="{{$bill -> tax}}">
                                                </div>
                                            </div>
                                            <div class="row" style="align-items: baseline; margin-bottom: 10px;">
                                                <div class="col-6">

                                                    <label
                                                        style="text-align: right;float: right;"> {{__('main.discount')}} </label>


                                                </div>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control" id="discount"
                                                           name="discount" placeholder="0"
                                                           value="{{$bill -> discount}}">
                                                </div>

                                            </div>
                                            <div class="row" style="align-items: center; margin-bottom: 10px;">
                                                <div class="col-6">
                                                    <label style="text-align: right;float: right;"
                                                    > {{__('main.net')}} </label>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control" id="net_sales"
                                                           value="{{$bill -> net_money}}">
                                                </div>
                                            </div>
                                            <hr class="sidebar-divider d-none d-md-block">


                                            <hr class="sidebar-divider d-none d-md-block">


                                            <div class="show_modal1">

                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 text-center" style="display: block; margin: auto;">
                                                    <input type="button" class="btn btn-primary" id="return_btn"
                                                           tabindex="-1"
                                                           style="width: 150px;
                                                   margin: 30px auto;" value="{{__('main.return_bill')}}"></input>

                                                </div>
                                            </div>

                                        </div>


                                    </div>

                                </div>


                            </form>
                        </div>


                    </div>
                </div>


            </div>
            <!-- /.container-fluid -->
            <input id="local" value="{{Config::get('app.locale')}}" hidden>
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
    $(document).ready(function () {
        $('#checkAll').change(function () {
            $("input:checkbox.checkDetail").prop('checked', this.checked);

        });

        $(document).on('click', '#return_btn', function () {
            var checkList = [];
            console.log('clicked');
            $('#tbody tr').each(function (index) {
                var row = $(this).closest('tr');

                var cell = row[0].cells[8].firstChild.checked;
                if (cell) {
                    checkList.push(row[0].cells[8].firstChild.value);
                }

            });

            if (checkList.length > 0) {
                document.getElementById('pos_sales_form').submit();
            } else {
                alert('select at least one item to return');

            }


        });


    });
</script>


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

</body>

</html>



