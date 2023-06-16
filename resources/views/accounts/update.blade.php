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
    <link href="../../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="../../../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../../../assets/css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top" @if(Config::get('app.locale') == 'ar') style="direction: rtl" @endif>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    @include('layouts.side' , ['slag' => 16 , 'subSlag' => 161])
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
                    <h1 class="h3 mb-0 text-primary-800">{{__('main.accounts')}} / {{__('main.add_account')}}</h1>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <form   method="POST" action="{{ route('update_account' , $account -> id) }}">
                        @csrf

                        <div class="row" style="padding: 20px">
                            <div class="col-md-12 col-sm-12">

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ __('main.code') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <input type="text"  id="code" name="code"
                                                   class="form-control @error('code') is-invalid @enderror"
                                                   placeholder="{{ __('main.code') }}"  value="{{$account -> code}}"/>
                                            @error('code')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6 " >
                                        <div class="form-group">
                                            <label>{{ __('main.name') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span>  </label>
                                            <input type="text"  id="name" name="name"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   placeholder="{{ __('main.name') }}"  value="{{$account -> name}}"/>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">


                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ __('main.account_type') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <select class="form-control @error('type') is-invalid @enderror" id="account_type" name="type">
                                                <option value="0" @if($account -> type == 0) selected @endif>{{__('main.Root')}}</option>
                                                <option value="1" @if($account -> type == 1) selected @endif>{{__('main.General')}}</option>
                                                <option value="2" @if($account -> type == 2) selected @endif> {{__('main.Branch')}}</option>
                                                <option value="3" @if($account -> type == 3) selected @endif>{{__('main.Branch_Ledger')}}</option>
                                            </select>
                                            @error('type')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ __('main.parent_id') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <select class="form-control @error('brand') is-invalid @enderror" id="parent_id" name="parent_id" disabled>
                                                <option value="0"></option>
                                                @foreach($accounts as $brand)
                                                    <option value="{{$brand->id}}" @if($brand -> id == $account -> parent_id) selected @endif> {{$brand->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('brand')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-6 " >
                                        <div class="form-group">
                                            <label>{{ __('main.account_level') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span>  </label>
                                            <input type="text"  id="level" name="level" readonly
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   placeholder="{{ __('main.account_level') }}"  value="{{$account -> level}}"/>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ __('main.account_list') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <select class="form-control @error('type') is-invalid @enderror" id="list" name="list">
                                                <option value="1"  @if($account -> list == 1) selected @endif>{{__('main.Assets')}}</option>
                                                <option value="2"  @if($account -> list == 2) selected @endif>{{__('main.Discounts')}}</option>
                                                <option value="3"  @if($account -> list == 3) selected @endif>{{__('main.Income')}}</option>
                                                <option value="4"  @if($account -> list == 4) selected @endif>{{__('main.Expenses')}}</option>
                                            </select>
                                            @error('type')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ __('main.account_department') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <select class="form-control @error('type') is-invalid @enderror" id="department" name="department">
                                                <option value="1"  @if($account -> department == 1) selected @endif>{{__('main.Balance_Sheet')}}</option>
                                                <option value="2"  @if($account -> department == 2) selected @endif>{{__('main.Incoming_List')}}</option>
                                            </select>
                                            @error('type')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ __('main.account_side') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <select class="form-control @error('type') is-invalid @enderror" id="side" name="side">
                                                <option value="1"  @if($account -> side == 1) selected @endif>{{__('main.Credit')}}</option>
                                                <option value="2"  @if($account -> side == 2) selected @endif >{{__('main.Debit')}}</option>
                                            </select>
                                            @error('type')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
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



<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }


    $('#account_type').change(function () {
        var t = $(this).val();
        console.log(t);
        $('#parent_id').val(0).trigger('change');
        $('#account_level').val(1);
        if (t == 0) {
            $('#parent_id').attr('disabled', true);
        }
        else if (t == 1) {
            $('#parent_id').attr('disabled', false);
        }
        else if (t == 2) {
            $('#parent_id').attr('disabled', false);
        }
        else if (t == 3) {
            $('#parent_id').attr('disabled', false);
        }
    });


    $('#parent_id').change(function () {
        var parent = $(this).val();
        if(parent == 0)
            return;
        var url = '{{route('get_account_level',":id")}}';
        url = url.replace(":id",parent);
        $.ajax({
            type: "get", async: false,
            url: url,
            dataType: "json",
            success: function (data) {
                $('#level').val(+data['account']['level']+1);
                $('#list').val(+data['account']['list']).trigger('change');
                $('#department').val(+data['account']['department']).trigger('change');

            }
        });


    });

</script>
<!-- Bootstrap core JavaScript-->
<script src="../../../assets/vendor/jquery/jquery.min.js"></script>
<script src="../../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../../../assets/js/sb-admin-2.min.js"></script>


<script src="../../../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../../../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="../../../assets/js/demo/datatables-demo.js"></script>

</body>

</html>






