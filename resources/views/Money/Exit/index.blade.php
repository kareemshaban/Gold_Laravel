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
    @include('layouts.side' , ['slag' => 11 , 'subSlag' => 19])
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
                    <h1 class="h3 mb-0 text-primary-800">{{__('main.money_exit')}} / {{__('main.money_exit_list')}}</h1>
                    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                       style="border-radius: 10px; margin:5px;: 5px;" id="createButton">
                        <i style="margin: 5px ; padding: 5px;"
                           class="fas fa-plus-circle fa-sm text-white-50"></i> {{__('main.add_new')}}
                    </a>
                </div>

                <div class="card-body px-0 pt-0 pb-2">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{__('main.money_exit_list')}}</h6>
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
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.bill_no')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.client')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.paid_money')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.payment_method')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.payment_type')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.based_on')}} </th>
                                        <th class="text-end text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $bill)
                                        <tr>
                                            <td class="text-center">{{$loop -> index + 1}}</td>
                                            <td class="text-center">{{\Carbon\Carbon::parse($bill -> date) -> format('d-m-Y')  }}</td>
                                            <td class="text-center">{{$bill -> doc_number}}</td>
                                            <td class="text-center">{{$bill -> vendor_name}}</td>
                                            <td class="text-center">{{$bill -> amount }}</td>
                                            <td class="text-center">
                                                @if($bill -> payment_method == 0)
                                                    {{__('main.cash')}}
                                                @else
                                                    {{__('main.visa')}}
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                @if($bill -> type == 0)
                                                    {{__('main.payment_type0')}}
                                                @elseif($bill -> type == 2)
                                                    {{__('main.payment_type2')}}
                                                    @elseif($bill -> type == 1)
                                                    {{__('main.payment_type1')}}
                                                @endif

                                            </td>

                                            <td class="text-center">
                                                @if($bill -> based_on == 0)
                                                    {{_('main.account_deposit')}}
                                                @else
                                                    @if($bill -> type == 0 || $bill -> type == 2)
                                                        <a href="{{route('workEntryPreview' , $bill -> based_on)}}"
                                                           target="_blank">{{$bill -> invoice_number0}}</a>
                                                    @else
                                                        <a href="{{route('oldEntryPreview' , $bill -> based_on)}}"
                                                           target="_blank">{{$bill -> invoice_number1}}</a>
                                                    @endif
                                                @endif

                                            </td>

                                            <td class="text-center">

                                                <button type="button" class="btn btn-labeled btn-secondary preview"
                                                        value="{{$bill -> id}}" id="{{$bill -> type}}">
                                                    <span class="btn-label" style="margin-right: 10px;"><i
                                                            class="fa fa-eye"></i></span>{{__('main.preview')}}</button>


                                                <button type="button" class="btn btn-labeled btn-danger deleteBtn "
                                                        value="{{$bill -> id}}">
                                                    <span class="btn-label" style="margin-right: 10px;"><i
                                                            class="fa fa-trash"></i></span>{{__('main.delete')}}
                                                </button>
                                            </td>
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


<div class="show_modal">

</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modelTitle"> {{__('main.deleteModal')}}</label>

            </div>
            <div class="modal-body" id="smallBody">
                <img src="../assets/img/warning.png" class="alertImage">
                <label class="alertTitle">{{__('main.delete_alert')}}</label>
                <br> <label class="alertSubTitle" id="modal_table_bill"></label>
                <div class="row">
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-labeled btn-primary" onclick="confirmDelete()">
                            <span class="btn-label" style="margin-right: 10px;"><i
                                    class="fa fa-check"></i></span>{{__('main.confirm_btn')}}</button>
                    </div>
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-labeled btn-secondary cancel-modal">
                            <span class="btn-label" style="margin-right: 10px;"><i
                                    class="fa fa-close"></i></span>{{__('main.cancel_btn')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profile-img-tag').attr('src', e.target.result);

            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image_url").change(function () {
        readURL(this);
    });
</script>

<script type="text/javascript">
    let id = 0;
    $(document).ready(function () {
        id = 0;
        $(document).on('click', '#createButton', function (event) {
            console.log('clicked');
            id = 0;
            addPayments();

        });

        $(document).on('click', '.preview', function (event) {

              console.log(event.currentTarget.value , event.currentTarget.id);
            viewPayment(event.currentTarget.value , event.currentTarget.id);

        });


        $(document).on('click', '.deleteBtn', function (event) {
            console.log('clicked');
            id = event.currentTarget.value;
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function () {
                    $('#loader').show();
                },
                // return the result
                success: function (result) {
                    $('#deleteModal').modal("show");
                },
                complete: function () {
                    $('#loader').hide();
                },
                error: function (jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

        $(document).on('click', '.cancel-modal', function (event) {
            $('#deleteModal').modal("hide");
            id = 0;
        });
        $(document).on('click', '.close-create', function (event) {
            $('#createModal').modal("hide");
            id = 0;
        });


    });

    function confirmDelete() {
        let url = "{{ route('enterMoneyDestroy', ':id') }}";
        url = url.replace(':id', id);
        document.location.href = url;
    }

    function addPayments() {
        var route = '{{route('money_exit_create')}}';

        $.get(route, function (data) {
            $(".show_modal").html(data);
            $('#paymentsModal').modal('show');
        });
    }

    function viewPayment(id , type) {
        console.log(id);
        var route = '{{route('exitMoneyPreview', [ ':id' , ':type'])}}';
        route = route.replace(':id', id);
        route = route.replace(':type', type);
        $.get(route, function (data) {
            $(".show_modal").html(data);
            $('#paymentsModal').modal('show');
        });
    }


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
