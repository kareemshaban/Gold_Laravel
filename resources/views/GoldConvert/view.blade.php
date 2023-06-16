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
    @include('layouts.side' , ['slag' => 19 , 'subSlag' => 192])
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
                    <h1 class="h3 mb-0 text-primary-800">{{__('main.gold_convert_doc')}} / {{__('main.gold_convert_preview')}}</h1>
                </div>

                <div class="card-body px-0 pt-0 pb-2">

                        <div class="row">
                            <div class="card shadow mb-4 col-12">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">{{__('main.gold_convert_create')}}</h6>
                                </div>
                                <div class="card-body">


                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>{{ __('main.date') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                                <input type="text"  id="date" name="date"
                                                       class="form-control" value="{{$data -> date}}" readonly
                                                />
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>{{ __('main.bill_no') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                                <input type="text"
                                                       class="form-control" placeholder="bill_no" readonly value="{{$data -> doc_number}}"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>{{ __('main.notes') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                                <textarea name="notes" id="notes" rows="3" placeholder="{{ __('main.notes') }}" class="form-control-lg" style="width: 100%" disabled>{{$data -> notes}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="control-group table-group">
                                                <label class="table-label">{{__('main.items')}} </label>

                                                <div class="controls table-controls">
                                                    <table id="sTable" class="table items table-striped table-bordered table-condensed table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center">{{__('main.item')}}</th>
                                                            <th class="text-center">{{__('main.karat')}}</th>
                                                            <th class="text-center">{{__('main.weight')}}</th>
                                                            <th class="text-center">{{__('main.total_weight21')}}</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody id="tbody">
                                                        @foreach($details as $detail)
                                                          <td class="text-center"> {{ Config::get('app.locale') == 'ar' ? $detail -> item_ar :  $detail -> item_en}}</td>
                                                          <td class="text-center"> <{{ Config::get('app.locale') == 'ar' ? $detail -> karat_ar :  $detail -> karat_en}}</td>
                                                            <td class="text-center">{{$detail -> weight}}</td>
                                                          <td class="text-center">{{$detail -> weight21}}</td>
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
