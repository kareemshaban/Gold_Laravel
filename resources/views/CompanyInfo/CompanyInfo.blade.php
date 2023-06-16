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

<body id="page-top"  @if(Config::get('app.locale') == 'ar') style="direction: rtl" @endif>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    @include('layouts.side' , ['slag' => 18 , 'subSlag' => 182])
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
                    <h1 class="h3 mb-0 text-primary-800" >{{__('main.settings')}} / {{__('main.companyInfo')}}</h1>
                </div>

                <div class="card-body px-0 pt-0 pb-2">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{__('main.companyInfo')}}</h6>
                        </div>
                        <div class="card-body">

                            <form   method="POST" action="{{ route('storeCompanyInfo') }}"
                                    enctype="multipart/form-data" >
                                @csrf

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ __('main.name_ar') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <input type="text"  id="name_ar" name="name_ar"
                                                   class="form-control"
                                                   placeholder="{{ __('main.name_ar') }}"  @if($info)  value="{{$info -> name_ar}}" @endif />
                                            <input type="text"  id="id" name="id"
                                                   class="form-control"
                                                   placeholder="{{ __('main.code') }}"  hidden=""   @if($info) value="{{$info -> id}}" @endif/>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ __('main.name_en') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <input type="text"  id="name_en" name="name_en"
                                                   class="form-control"
                                                   placeholder="{{__('main.name_en')}}"  @if($info) value="{{$info -> name_en}}" @endif />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 " >
                                        <label>{{ __('main.phone') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                        <input type="text"  id="phone" name="phone"
                                               class="form-control"
                                               placeholder="{{__('main.phone')}}"  @if($info) value="{{$info -> phone}}" @endif />
                                    </div>
                                    <div class="col-6 " >
                                        <label>{{ __('main.phone')  . ' ' . '2'  }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                        <input type="text"  id="phone2" name="phone2"
                                               class="form-control"
                                               placeholder="{{__('main.phone') . ' ' . '2' }}"  @if($info) value="{{$info -> phone2}}" @endif />
                                    </div>



                                </div>
                                <div class="row">
                                    <div class="col-4 " >
                                        <label>{{ __('main.fax') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                        <input type="text"  id="fax" name="fax"
                                               class="form-control"
                                               placeholder="{{__('main.fax')}}" @if($info) value="{{$info -> fax}}" @endif  />
                                    </div>
                                    <div class="col-4 " >
                                        <label>{{ __('main.email')    }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                        <input type="text"  id="email" name="email"
                                               class="form-control"
                                               placeholder="{{__('main.email')  }}"  @if($info) value="{{$info -> email}}" @endif />
                                    </div>

                                    <div class="col-4 " >
                                        <label>{{ __('main.website') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                        <input type="text"  id="website" name="website"
                                               class="form-control"
                                               placeholder="{{__('main.website')}}" @if($info) value="{{$info -> website}}" @endif  />
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-6 " >
                                        <label>{{ __('main.vat_no')    }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                        <input type="text"  id="taxNumber" name="taxNumber"
                                               class="form-control"
                                               placeholder="{{__('main.vat_no')  }}"  @if($info) value="{{$info -> taxNumber}}" @endif />
                                    </div>
                                    <div class="col-6 " >
                                        <label>{{ __('main.commercial_register')    }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                        <input type="text"  id="registrationNumber" name="registrationNumber"
                                               class="form-control"
                                               placeholder="{{__('main.commercial_register')  }}"  @if($info) value="{{$info -> registrationNumber}}" @endif />
                                    </div>

                                </div>


                                <div class="row">
                                    <div class="col-3 " >
                                        <label>{{ __('main.currency_ar') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                        <input type="text"  id="currency_ar" name="currency_ar"
                                               class="form-control"
                                               placeholder="{{__('main.currency_ar')}}"  @if($info) value="{{$info -> currency_ar}}" @endif/>
                                    </div>
                                    <div class="col-3 " >
                                        <label>{{ __('main.currency_en')    }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                        <input type="text"  id="currency_en" name="currency_en"
                                               class="form-control"
                                               placeholder="{{__('main.currency_en')  }}"  @if($info) value="{{$info -> currency_en}}" @endif />
                                    </div>

                                    <div class="col-3 " >
                                        <label>{{ __('main.currency_label') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                        <input type="text"  id="currency_label" name="currency_label"
                                               class="form-control"
                                               placeholder="{{__('main.currency_label')}}"  @if($info) value="{{$info -> currency_label}}" @endif/>
                                    </div>

                                    <div class="col-3 " >
                                        <label>{{ __('main.currency_label_en') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                        <input type="text"  id="currency_label_en" name="currency_label_en"
                                               class="form-control"
                                               placeholder="{{__('main.currency_label_en')}}" @if($info) value="{{$info -> currency_label_en}}" @endif />
                                    </div>




                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">{{__('main.address')}}</label>
                                            <textarea id="address" name="address" class="form-control">{{ $info ? $info -> currency_label : '' }}</textarea>
                                        </div>
                                    </div>
                                </div>




                                <div class="row">
                                    <div class="col-6">
                                        <div class="custom-file">
                                            <input type="file" class="form-control custom-file-input" id="image_url"   name="image_url"  accept="image/png, image/jpeg" >
                                            <label class="custom-file-label" for="img" id="path">{{__('main.img_choose')}}   <span style="color:red;">*</span></label>
                                        </div>

                                    </div>
                                    <div class="col-6 text-right">
                                        <img  src="   {{  $info ?  $info -> logo ?   asset('images/Category' . '/' . $info -> logo)   : '../assets/img/photo.png' : '../assets/img/photo.png'}}"   id="profile-img-tag" width="150px" height="150px" class="profile-img"/>
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
    $("#image_url").change(function(){
        readURL(this);
    });
</script>

<script type="text/javascript">
    let id = 0 ;
    $(document).ready(function()
    {
        id = 0 ;
        $(document).on('click', '#createButton', function(event) {
            console.log('clicked');
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
                    $(".modal-body #name_ar").val( "" );
                    $(".modal-body #name_en").val( "" );
                    $(".modal-body #description").val("");
                    $(".modal-body #id").val( 0 );
                    $(".modal-body #image_url").val("");
                    $(".modal-body #profile-img-tag").attr('src' , '../assets/img/photo.png' );

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



        $(document).on('click', '.editBtn', function(event) {

            id = event.currentTarget.value ;
            event.preventDefault();
            $.ajax({
                type:'get',
                url:'getCategory' + '/' + id,
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
                                if(response.image_url){
                                    var img =  '../images/Category/' + response.image_url ;

                                    $(".modal-body #profile-img-tag").attr('src' , img );
                                }

                                $(".modal-body #name_ar").val( response.name_ar );
                                $(".modal-body #name_en").val( response.name_en );
                                $(".modal-body #description").val(response.description);
                                $(".modal-body #id").val( response.id );

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

        });
        $(document).on('click', '.deleteBtn', function(event) {
            id = event.currentTarget.value ;
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
        $(document).on('click' , '.close-create' , function (event) {
            $('#createModal').modal("hide");
            id = 0 ;
        });



    });
    function confirmDelete(){
        let url = "{{ route('deleteCategory', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }
    function EditModal(id){
        $.ajax({
            type:'get',
            url:'getCategory' + '/' + id,
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
                            var img =  '../images/Category/' + response.image_url ;
                            $(".modal-body #profile-img-tag").attr('src' , img );
                            $(".modal-body #name").val( response.name );
                            $(".modal-body #code").val( response.code );
                            $(".modal-body #slug").val(response.slug);
                            $(".modal-body #description").val(response.description);
                            $(".modal-body #parent_id").val(response.parent_id);
                            $(".modal-body #id").val( response.id );
                            $(".modal-body #isGold").prop('checked' , response.isGold);


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












