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
    <link href='https://fonts.googleapis.com/css?family=Libre Barcode 128 Text' rel='stylesheet'>



</head>

<body id="page-top" @if(Config::get('app.locale') == 'ar') style="direction: rtl" @endif>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    @include('layouts.side' , ['slag' => 3 , 'subSlag' => 4])
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
                    <h1 class="h3 mb-0 text-primary-800">{{__('main.basic_data')}} / {{__('main.item_list')}}</h1>
                    <a id="createButton" href="javascript:;"
                       class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                       style="border-radius: 10px; margin:5px;: 5px;"><i style="margin: 5px ; padding: 5px;"
                                                                         class="fas fa-plus-circle fa-sm text-white-50"></i> {{__('main.add_new')}}
                    </a>
                </div>

                <div class="card-body px-0 pt-0 pb-2">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{__('main.item_list')}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">
                                            #
                                        </th>
                                        <th>{{__('main.img')}}</th>
                                        <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7 ps-2">{{__('main.code')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.name_ar')}}</th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.name_en')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.category')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.karat')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.weight')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.gram_made_value')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.made_Value_t')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.no_metal')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.cost')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.price')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.quantity')}} </th>
                                        <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.state')}} </th>
                                        <th class="text-end text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $item)
                                        <tr>
                                            <td class="text-center">{{$loop -> index + 1}}</td>
                                            <td class="text-center">
                                                <img src="{{ $item->img ?   asset('images/Items/' . $item->img) : asset('assets/img/photo.png') }}" height=50 width=50
                                                     alt="item image">
                                            </td>
                                            <td class="text-center">{{$item -> code}}</td>
                                            <td class="text-center">{{$item -> name_ar}}</td>
                                            <td class="text-center">{{$item -> name_en}}</td>
                                            <td class="text-center">{{Config::get('app.locale') == 'ar' ? $item -> category_name_ar : $item -> category_name_en }}</td>
                                            <td class="text-center">{{Config::get('app.locale') == 'ar' ? $item -> karat_name_ar : $item -> karat_name_en}}</td>
                                            <td class="text-center">{{$item -> weight}}</td>
                                            <td class="text-center">{{$item -> made_Value}}</td>
                                            <td class="text-center">{{$item -> weight * $item -> made_Value}}</td>
                                            <td class="text-center">{{$item -> no_metal}}</td>
                                            <td class="text-center" >{{$item -> cost == 0 ? '--' : $item -> cost }}</td>
                                            <td class="text-center">{{$item -> price == 0 ? '--' : $item -> price}}</td>
                                            <td class="text-center">{{$item -> item_type == 2 ? $item -> quantity : '---'}}</td>
                                            <td class="text-center">{{$item -> state == 1  ? __('main.state1')  : __('main.state2')}}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-labeled btn-secondary editBtn"
                                                        value="{{$item -> id}}">
                                                    <span class="btn-label" style="margin-right: 10px;"><i
                                                            class="fa fa-pen"></i></span>{{__('main.edit')}}</button>

                                                <button type="button" class="btn btn-labeled btn-danger deleteBtn "
                                                        value="{{$item -> id}}">
                                                    <span class="btn-label" style="margin-right: 10px;"><i
                                                            class="fa fa-trash"></i></span>{{__('main.delete')}}
                                                </button>
                                                <br> <br>
                                                <a href="{{route('printBarcode' , $item -> id)}}" target="_blank" >
                                                <button type="button" class="btn btn-labeled btn-warning printBTN" value="{{$item -> id}}">
                                                    <span class="btn-label" style="margin-right: 10px;"><i
                                                            class="fa fa-barcode" style="margin-left: 5px;
                                                            margin-right: 5px;"></i></span>{{__('main.print_barcode')}}
                                                </button>
                                                </a>

                                                @if($item -> item_type == 3 )
                                                    <br> <br>
                                                <button type="button" class="btn btn-labeled btn-info compined"
                                                        value="{{$item -> id}}">
                                                    <span class="btn-label" style="margin-right: 10px;"><i
                                                            class="fa fa-cloud"></i></span>{{__('main.compine')}}
                                                </button>
                                                    @endif
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


<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modelTitle"> {{__('main.add_item')}}</label>
                <button type="button" class="close modal-close-btn close-create" data-bs-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="paymentBody">
                <form method="POST" action="{{ route('storeItem') }}"
                      enctype="multipart/form-data" id="modal_form">
                    @csrf

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ __('main.code') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span>
                                </label>
                                <input type="text" id="code" name="code"
                                       class="form-control"
                                       placeholder="{{ __('main.code') }}"  readonly/>
                                <input type="text" id="id" name="id"
                                       class="form-control"
                                       placeholder="{{ __('main.code') }}" hidden=""/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ __('main.item_type') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <select class="form-control" id="item_type" name="item_type" >
                                    <option value="1">{{__('main.item_type1')}}</option>
                                    <option value="2">{{__('main.item_type2')}}</option>
                                    <option value="3">{{__('main.item_type3')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ __('main.name_ar') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="text" id="name_ar" name="name_ar"
                                       class="form-control"
                                       placeholder="{{ __('main.name_ar') }}" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ __('main.name_en') }}  </label>
                                <input type="text" id="name_en" name="name_en"
                                       class="form-control"
                                       placeholder="{{ __('main.name_en') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ __('main.category') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <select class="form-control" id="category_id" name="category_id" >
                                    <option value=""> select...</option>
                                    @foreach($categories as $category)
                                        <option
                                            value="{{$category -> id}}">{{Config::get('app.locale') == 'ar' ? $category -> name_ar : $category -> name_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6 type1">
                            <div class="form-group">
                                <label>{{ __('main.karat') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <select class="form-control" id="karat_id" name="karat_id" >
                                    <option value=""> select...</option>
                                    @foreach($karats as $karat)
                                        <option
                                            value="{{$karat -> id}}">{{Config::get('app.locale') == 'ar' ? $karat -> name_ar : $karat -> name_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row type1">
                        <div class="col-4">
                            <div class="form-group">
                                <label>{{ __('main.weight') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="number"  step="any" id="weight" name="weight"
                                       class="form-control"
                                       placeholder="0" />
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>{{ __('main.no_metal') }}  </label>
                                <input type="number" step="any" id="no_metal" name="no_metal"
                                       class="form-control"
                                       placeholder="0" value="0"/>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>{{ __('main.no_metal_type') }} </label>
                                <select class="form-control" id="no_metal_type" name="no_metal_type">
                                    <option value="1" selected>{{__('main.no_metal_type1')}}</option>
                                    <option value="2">{{__('main.no_metal_type2')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row type1">
                        <div class="col-4">
                            <div class="form-group">
                                <label>{{ __('main.stamp_value') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="number" step="any" id="tax" name="tax"
                                       class="form-control"
                                       placeholder="0" readonly/>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>{{ __('main.made_Value') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="number" step="any" id="made_Value" name="made_Value"
                                       class="form-control"
                                       placeholder="0" value="0" />
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>{{ __('main.state') }}</label>
                                <select class="form-control" id="state" name="state">
                                    <option value="1" selected>{{__('main.state1')}}</option>
                                    <option value="2">{{__('main.state2')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row type2">
                        <div class="col-4">
                            <div class="form-group">
                                <label>{{ __('main.cost') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="number" step="any" id="cost" name="cost"
                                       class="form-control"
                                       placeholder="0" />
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>{{ __('main.price') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="number" step="any" id="price" name="price"
                                       class="form-control"
                                       placeholder="0" />
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>{{ __('main.tax') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="number" step="any" id="taxx" name="taxx"
                                       class="form-control"
                                       placeholder="0" />
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label>{{ __('main.img') }}</label>
                            <div class="row">


                                <div class="col-6">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="img" name="img"
                                               accept="image/png, image/jpeg" >
                                        <label class="custom-file-label" for="img"
                                               id="path">{{__('main.img_choose')}} </label>
                                    </div>
                                    <br> <span
                                        style="font-size: 9pt ; color:gray;">{{ __('main.img_hint') }}</span>

                                </div>
                                <div class="col-6 text-right">
                                    <img src="../assets/img/photo.png" id="profile-img-tag" width="150px"
                                         height="150px" class="profile-img"/>
                                </div>
                            </div>
                            @error('printer')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-6" style="display: block; margin: 20px auto; text-align: center;">
                            <button type="button" class="btn btn-labeled btn-primary" id="submit_modal_btn">
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: red; font-size: 20px; font-weight: bold;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="smallBody">
                <img src="../assets/img/warning.png" class="alertImage">
                <label class="alertTitle">{{__('main.delete_alert')}}</label>
                <br> <label class="alertSubTitle" id="modal_table_bill"></label>
                <div class="row">
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-labeled btn-primary" onclick="confirmDelete(1)">
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



<div class="modal fade" id="deleteModal2" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modelTitle"> {{__('main.deleteModal')}}</label>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: red; font-size: 20px; font-weight: bold;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="smallBody">
                <img src="../assets/img/warning.png" class="alertImage">
                <label class="alertTitle">{{__('main.delete_alert')}}</label>
                <br> <label class="alertSubTitle" id="modal_table_bill"></label>
                <div class="row">
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-labeled btn-primary" onclick="confirmDelete(2)">
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


<div class="show_modal">

</div>

<div class="barcode_modal">

</div>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profile-img-tag').attr('src', e.target.result);

            }
            reader.readAsDataURL(input.files[0]);
            document.getElementById('path').innerHTML = input.files[0].name;
        }
    }

    $("#img").change(function () {
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
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function () {
                    $('#loader').show();
                },
                // return the result
                success: function (result) {
                    $.ajax({
                        type: 'get',
                        url: 'getItemCode',
                        dataType: 'json',

                        success: function (response) {
                            $('#createModal').modal("show");
                            $(".modal-body #code").val(response);
                            $(".modal-body #name_ar").val("");
                            $(".modal-body #name_en").val("");
                            $(".modal-body #item_type").val(1);
                            $(".modal-body #category_id").val("");
                            $(".modal-body #karat_id").val("");
                            $(".modal-body #weight").val(0);
                            $(".modal-body #no_metal").val(0);
                            $(".modal-body #no_metal_type").val(1);
                            $(".modal-body #tax").val("");
                            $(".modal-body #made_Value").val(0);
                            $(".modal-body #state").val(1);
                            $(".modal-body #id").val(0);

                            setTimeout(() =>{
                                $(".modal-body .type1").slideDown();
                                $(".modal-body .type2").slideUp();
                            } , 500);


                            $(".modal-body #item_type").change(function (){
                                console.log(this.value);
                                if(this.value == 1  ){
                                    $(".modal-body .type1").slideDown();
                                    $(".modal-body .type2").slideUp();
                                    $(".modal-body #weight").prop('readonly', false);
                                    $(".modal-body #made_Value").prop('readonly', false);
                                } else if(this.value == 2){
                                    $(".modal-body .type2").slideDown();
                                    $(".modal-body .type1").slideUp();
                                } else if(this.value == 3){
                                    $(".modal-body .type1").slideDown();
                                    $(".modal-body .type2").slideUp();
                                    $(".modal-body #weight").prop('readonly', true);
                                    $(".modal-body #made_Value").prop('readonly', true);
                                }
                            });

                            $(".modal-body #karat_id").change(function (){
                                $.ajax({
                                    type: 'get',
                                    url: 'getKarat' + '/' + this.value,
                                    dataType: 'json',

                                    success: function (response) {

                                        $(".modal-body #tax").val(response.stamp_value);
                                    }
                                });
                            });
                        }
                    });




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

        $(document).on('click', '#submit_modal_btn', function (event){

            const name_ar = document.getElementById('name_ar').value ;
            const category_id = document.getElementById('category_id').value ;
            const karat_id = document.getElementById('karat_id').value ;
            const weight = document.getElementById('weight').value ;
            const made_Value = document.getElementById('made_Value').value ;
            const type = document.getElementById('item_type').value ;

            if(type == 1){
                if(name_ar && category_id && karat_id &&   weight > 0 && made_Value > 0){
                    document.getElementById('modal_form').submit();
                } else {
                    alert($('<div>{{trans('main.fill_data')}}</div>').text());
                }
            } else if(type == 2){
                if(name_ar && category_id ){
                    document.getElementById('modal_form').submit();
                } else {
                    alert($('<div>{{trans('main.fill_data')}}</div>').text());
                }
            }
            else if(type == 3){
                if(name_ar && category_id && karat_id ){
                    document.getElementById('modal_form').submit();
                } else {
                    alert($('<div>{{trans('main.fill_data')}}</div>').text());
                }
            }





        });




        $(document).on('click', '.editBtn', function (event) {

            id = event.currentTarget.value;
            event.preventDefault();
            $.ajax({
                type: 'get',
                url: 'getItem' + '/' + id,
                dataType: 'json',

                success: function (response) {
                    console.log(response);
                    if (response) {
                        let href = $(this).attr('data-attr');
                        $.ajax({
                            url: href,
                            beforeSend: function () {
                                $('#loader').show();
                            },
                            // return the result
                            success: function (result) {
                                $('#createModal').modal("show");
                                if (response.img) {
                                    var img = '../images/Items/' + response.img;

                                    $(".modal-body #profile-img-tag").attr('src', img);
                                }

                                $('#createModal').modal("show");
                                $(".modal-body #code").val(response.code);
                                $(".modal-body #name_ar").val(response.name_ar);
                                $(".modal-body #name_en").val(response.name_en);
                                $(".modal-body #item_type").val(response.item_type);
                                $(".modal-body #category_id").val(response.category_id);
                                $(".modal-body #karat_id").val(response.karat_id);
                                $(".modal-body #weight").val(response.weight);
                                $(".modal-body #no_metal").val(response.no_metal);
                                $(".modal-body #no_metal_type").val(response.no_metal_type);
                                $(".modal-body #tax").val(response.tax);
                                $(".modal-body #made_Value").val(response.made_Value);
                                $(".modal-body #state").val(response.state);
                                $(".modal-body #id").val(response.id);

                                if(response.item_type == 1 ){
                                    $(".modal-body .type1").slideDown();
                                    $(".modal-body .type2").slideUp();
                                    $(".modal-body #weight").prop('readonly', false);
                                    $(".modal-body #made_Value").prop('readonly', false);
                                } else if(response.item_type == 3){
                                    $(".modal-body .type1").slideDown();
                                    $(".modal-body .type2").slideUp();
                                    $(".modal-body #weight").prop('readonly', true);
                                    $(".modal-body #made_Value").prop('readonly', true);
                                }

                                else if(response.item_type == 2){
                                    $(".modal-body .type2").slideDown();
                                    $(".modal-body .type1").slideUp();
                                }


                                $(".modal-body #karat_id").change(function (){
                                    $.ajax({
                                        type: 'get',
                                        url: 'getKarat' + '/' + this.value,
                                        dataType: 'json',

                                        success: function (response) {

                                            $(".modal-body #tax").val(response.stamp_value);
                                        }
                                    });
                                });
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
                    } else {

                    }
                }
            });

        });
        $(document).on('click', '.deleteBtn', function (event) {
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
        $(document).on('click', '.compined', function (event) {
            var route = '{{route('getParentItem',":id")}}';
            var val = event.currentTarget.value;
            route = route.replace(":id",val);

            $.get( route, function( data ) {
                $( ".show_modal" ).html( data );
                $('#compineModal').modal('show');
            });

        });
        $(document).on('click', '.deleteCombineBtn', function (event) {
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
                    $('#compineModal').modal("hide");
                    $('#deleteModal2').modal("show");
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



        $(document).on('click' , '.printBTN' , function (event) {
            {{--const id = event.currentTarget.value;--}}
            {{--var route = '{{route('printBarcode',":id")}}';--}}
            {{--route = route.replace(":id", id);--}}

            {{--document.location.href = route ;--}}



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

    function confirmDelete(index) {
        let url = "" ;
        if(index == 1){
            url   = "{{ route('deleteItem', ':id') }}";
        } else {
            url = "{{ route('deleteItemMaterial', ':id') }}";
        }

        url = url.replace(':id', id);
        document.location.href = url;
    }

    function EditModal(id) {
        $.ajax({
            type: 'get',
            url: 'getCategory' + '/' + id,
            dataType: 'json',

            success: function (response) {
                console.log(response);
                if (response) {
                    let href = $(this).attr('data-attr');
                    $.ajax({
                        url: href,
                        beforeSend: function () {
                            $('#loader').show();
                        },
                        // return the result
                        success: function (result) {
                            $('#createModal').modal("show");
                            var img = '../images/Category/' + response.image_url;
                            $(".modal-body #profile-img-tag").attr('src', img);
                            $(".modal-body #name").val(response.name);
                            $(".modal-body #code").val(response.code);
                            $(".modal-body #slug").val(response.slug);
                            $(".modal-body #description").val(response.description);
                            $(".modal-body #parent_id").val(response.parent_id);
                            $(".modal-body #id").val(response.id);
                            $(".modal-body #isGold").prop('checked', response.isGold);


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
