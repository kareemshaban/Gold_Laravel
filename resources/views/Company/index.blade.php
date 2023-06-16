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
    @include('layouts.side' , ['slag' => $type == 3 ? 5 : 4, 'subSlag' => 4])
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
                    <h1 class="h3 mb-0 text-primary-800">{{__('main.basic_data')}} / {{$type == 3 ? __('main.clients') : __('main.suppliers')}}</h1>
                    <a id="createButton" href="javascript:;"
                       class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                       style="border-radius: 10px; margin:5px;: 5px;"><i style="margin: 5px ; padding: 5px;"
                                                                         class="fas fa-plus-circle fa-sm text-white-50"></i> {{__('main.add_new')}}
                    </a>
                </div>

                <div class="card-body px-0 pt-0 pb-2">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{$type == 3 ? __('main.clients') : __('main.suppliers')}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 border">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">#</th>
                                        <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7 ps-2">{{__('main.company')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.name')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.phone')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.email')}}</th>

                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.vat_no')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.balance')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.balance_gold')}}</th>
                                        <th class="text-end text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($companies as $company)
                                        @if($company -> group_id == $type)
                                            <tr>
                                                <td class="text-center">{{$loop -> index +1}}</td>
                                                <td class="text-center">{{$company -> company}}</td>
                                                <td class="text-center">{{$company -> name}}</td>
                                                <td class="text-center">{{$company -> phone}}</td>
                                                <td class="text-center">{{$company -> email}}</td>

                                                <td class="text-center">{{$company -> vat_no}}</td>
                                                <td class="text-center">{{number_format($company -> deposit_amount - $company -> credit_amount  , 2)}}</td>
                                                <td class="text-center">{{ number_format($company -> deposit_gold  - $company -> credit_gold, 2) }}</td>

                                                <td class="text-center">
                                                    @if(in_array('vendor_account' , $routes))
                                                    <a href="{{route('clientAccount' , $company -> id)}}">
                                                        <button type="button" class="btn btn-labeled btn-secondary" value="{{$company -> id}}">
                                                            <span class="btn-label" style="margin-right: 10px;"><i class="fa fa-eye"></i></span>{{__('main.preview')}}</button>
                                                    </a>
                                                    @endif


                                                    <button type="button" class="btn btn-labeled btn-danger deleteBtn "  value="{{$company -> id}}">
                                                        <span class="btn-label" style="margin-right: 10px;"><i class="fa fa-trash"></i></span>{{__('main.delete')}}</button>
                                                </td>
                                            </tr>
                                        @endif
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


<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modelTitle"> {{$type == 3 ? __('main.create_client') : __('main.create_supplier')}}</label>
                <button type="button" class="close modal-close-btn"  data-bs-dismiss="modal"  aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="paymentBody">
                <form   method="POST" action="{{ route('storeCompany') }}"
                        enctype="multipart/form-data" >
                    @csrf

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ __('main.company') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="text"  id="company" name="company"
                                       class="form-control"
                                       placeholder="{{ __('main.company') }}"  />
                                <input type="text"  id="id" name="id"
                                       class="form-control"
                                       placeholder="{{ __('main.code') }}"  hidden=""/>
                            </div>
                        </div>
                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.name') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="text"  id="name" name="name"
                                       class="form-control"
                                       placeholder="{{ __('main.name') }}"  />
                                <input type="text"  id="type" name="type"
                                       class="form-control" value="{{$type}}"
                                       placeholder="{{ __('main.name') }}"  hidden />

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.phone') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="text"  id="phone" name="phone"
                                       class="form-control"
                                       placeholder="{{ __('main.phone') }}"  />
                            </div>
                        </div>
                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.email') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="text"  id="email" name="email"
                                       class="form-control"
                                       placeholder="{{ __('main.email') }}"  />
                            </div>
                        </div>
                    </div>
                    <div class="row">


                        <div  class="col-12 " >
                            <div class="form-group">
                                <label>{{ __('main.account') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <select class="form-control mr-sm-2"
                                        name="account_id" id="account_id">
                                    <option selected value ="0">Choose...</option>
                                    @foreach ($accounts as $item)
                                        <option value="{{$item -> id}}"> {{ $item -> name}}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.vat_no') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="text"  id="vat_no" name="vat_no"
                                       class="form-control"
                                       placeholder="{{ __('main.vat_no') }}"  />
                            </div>
                        </div>
                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.opening_balance') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="number" step="any"  id="opening_balance" name="opening_balance"
                                       class="form-control"
                                       placeholder="{{ __('main.opening_balance') }}"  />
                            </div>
                        </div>
                    </div>
                    @if($type == 3)
                        <div class="row" >
                            <div class="col-6 " >
                                <div class="form-group">
                                    <label>{{ __('main.credit_limit') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                    <input type="text"  id="credit_amount" name="credit_amount"
                                           class="form-control"
                                           placeholder="{{ __('main.credit_limit') }}"  />
                                </div>
                            </div>
                            <div class="col-6 " >
                                <div class="form-group">
                                    <label>{{ __('main.stop_sale') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                    <input type="checkbox"   id="stop_sale" name="stop_sale"
                                           class="form-check" style="width: 20px;"
                                           placeholder="{{ __('main.opening_balance') }}"  />
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12 " >
                            <div class="form-group">
                                <label>{{ __('main.address') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <textarea type="text"  id="address" name="address" class="form-control" placeholder="{{ __('main.address') }}"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6" style="display: block; margin: 20px auto; text-align: center;">
                            <button type="submit" class="btn btn-labeled btn-primary"  >
                                {{__('main.save_btn')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modelTitle"> {{__('main.deleteModal')}}</label>

            </div>
            <div class="modal-body" id="smallBody">
                <img src="../../assets/img/warning.png" class="alertImage">
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
    let id = 0 ;
    $(document).ready(function()
    {
        id = 0 ;
        $(document).on('click', '#createButton', function(event) {
            id = 0 ;
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#createModal').modal("show");
                    $(".modal-body #company").val( "" );
                    $(".modal-body #name").val( "" );
                    $(".modal-body #phone").val( "" );
                    $(".modal-body #email").val( "" );
                    $(".modal-body #account_id").val( "" );
                    $(".modal-body #vat_no").val( "" );
                    $(".modal-body #opening_balance").val( "" );
                    try {
                        $(".modal-body #customer_group_id").val( "" );
                        $(".modal-body #credit_amount").val( "" );
                        $(".modal-body #stop_sale").prop('checked' ,0);
                    }catch (err){

                    }
                    $(".modal-body #address").val( "" );
                    $(".modal-body #id").val( 0 );
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });
        $(document).on('click', '.deleteBtn', function(event) {
            id = event.currentTarget.value ;
            console.log(id);
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#deleteModal').modal("show");
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

        $(document).on('click' , '.cancel-modal' , function (event) {
            $('#deleteModal').modal("hide");
            id = 0 ;
        });


    });
    function confirmDelete(){
        let url = "{{ route('deleteCompany', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }
    function EditModal(id){
        $.ajax({
            type:'get',
            url:'/getCompany' + '/' + id,
            dataType: 'json',

            success:function(response){
                console.log(response);
                if(response){
                    let href = $(this).attr('data-attr');
                    $.ajax({
                        url: href,
                        beforeSend: function() {
                            $('#loader').show();
                        },
                        // return the result
                        success: function(result) {
                            $('#createModal').modal("show");
                            $(".modal-body #company").val( response.company );
                            $(".modal-body #name").val( response.name );
                            $(".modal-body #phone").val(  response.phone );
                            $(".modal-body #email").val(  response.email );
                            $(".modal-body #account_id").val(  response.account_id );
                            $(".modal-body #vat_no").val(  response.vat_no );
                            $(".modal-body #opening_balance").val(  response.opening_balance );
                            try {
                                $(".modal-body #customer_group_id").val(  response.customer_group_id );
                                $(".modal-body #credit_amount").val(  response.credit_amount );
                                $(".modal-body #stop_sale").prop('checked' , response.stop_sale);
                            }catch (err){

                            }
                            $(".modal-body #address").val(  response.address );
                            $(".modal-body #id").val(  response.id );

                        },
                        complete: function() {
                            $('#loader').hide();
                        },
                        error: function(jqXHR, testStatus, error) {
                            console.log(error);
                            alert("Page " + href + " cannot open. Error:" + error);
                            $('#loader').hide();
                        },
                        timeout: 8000
                    })
                } else {

                }
            }
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
