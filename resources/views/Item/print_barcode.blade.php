<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title class="no-print">Gold MS</title>

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
    @include('layouts.side' , ['slag' => 3 , 'subSlag' => 401])
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
                <div class="d-sm-flex align-items-center justify-content-between mb-4 " style="padding: 8px">
                    <h1 class="h3 mb-0 text-primary-800 no-print">{{__('main.item_list')}} / {{__('main.print_barcode')}}</h1>
                </div>




                        <div class="modal-body" id="paymentBody">
                            <form   method="POST" action="{{ route('preview_barcode') }}" class="no-print">
                                @csrf

                                <div class="row">
                                    <div class="col-12">
                                        <div class="col-md-12" id="sticker">
                                            <div class="well well-sm" @if(Config::get('app.locale') == 'ar')style="direction: rtl;" @endif>
                                                <div class="form-group" style="margin-bottom:0;">
                                                    <div class="input-group wide-tip">
                                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                                            <i class="fa fa-2x fa-barcode addIcon"></i></div>
                                                        <input style="border-radius: 0 !important;padding-left: 10px;padding-right: 10px;"
                                                               type="text" name="add_item" value="" class="form-control input-lg ui-autocomplete-input" id="add_item" placeholder="{{__('main.add_item_hint')}}" autocomplete="off">

                                                    </div>

                                                </div>
                                                <ul class="suggestions" id="products_suggestions" style="display: block">

                                                </ul>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="card mb-4">
                                            <div class="card-header pb-0">
                                                <h4 class="table-label text-center">{{__('main.items')}} </h4>
                                            </div>

                                            <div class="card-body px-0 pt-0 pb-2">
                                                <div class="table-responsive p-0">

                                                    <table id="sTable" style="width:100%" class="table align-items-center mb-0">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center">{{__('main.item_name_code')}}</th>
                                                            <th class=" text-center col-md-1">{{__('main.quantity')}} </th>
                                                            <th class="text-center" style="max-width: 30px !important; text-align: center;">
                                                                <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tbody"></tbody>
                                                        <tfoot></tfoot>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-header pb-0">
                                        <h4 class="table-label text-center">{{__('main.select_print_options')}} </h4>
                                    </div>
                                    <div class="card-body px-0 pt-0 pb-2">
                                        <div class="row" style="display: flex; justify-content: center">
                                            <div class="col-2" >
                                                <div class="form-group checkRow">
                                                    <label>{{ __('main.weight') }}</label>
                                                    <input type="checkbox"   id="weight" name="weight" checked value="1"
                                                           class="form-check"/>
                                                </div>
                                            </div>
                                            <div class="col-2" >
                                                <div class="form-group checkRow">
                                                    <label>{{ __('main.karat') }}</label>
                                                    <input type="checkbox"   id="karat" name="karat" checked value="1"
                                                           class="form-check"/>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-6" style="display: block; margin: 20px auto; text-align: center;">
                                        <button type="submit" class="btn btn-labeled btn-primary"  >
                                            {{__('main.print_barcode')}}</button>
                                    </div>
                                </div>

                            </form>
                            <div id="barcode-con" style="text-align: center">
                                @if(!empty($data))
                                    <button type="button" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="طباعة"><i class="icon fa fa-print"></i> طباعة</button>
                                    @foreach ($data as $index=>$item)

                                        @for ($r = 1; $r <= $item['quantity']; $r++)
                                            <div class="item style50" style="width:2in;height: 1in;border:0;">
                                                <div class="div50" style="width:2in;height:1in;border: 1px dotted #CCC;padding-top:0.025in;">
                                                        <span class="barcode_name" style="display: block; font-family: arial;font-size: 11px;font-weight:bold;color: black;">
                                                            {{ Config::get('app.locale') == 'ar' ?  $item['name_ar'] : $item['name_en']}}</span>

                                                    @if ($item['weight'])
                                                        <span class="barcode_price"  style="font-family: arial;font-size: 11px;font-weight:bold;color: black;">الوزن :
                                                    {{$item['weight']}}
                                                       جرام
                                                    </span>
                                                    @endif
                                                    <p style="font-family: 'Libre Barcode 39' !important;  font-size: 30px;color: black;padding: 0px;margin: 0px;line-height: 1.2;">{{$item['barcode']}}</p>
                                                </div>
                                            </div>
                                        @endfor
                                    @endforeach
                                    <button type="button" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="طباعة"><i class="icon fa fa-print"></i>طباعة</button>
                                @else
                                    <h3>{{__('main.no_product_selected')}}</h3>
                                @endif
                            </div>
                        </div>




                <input type="hidden" value="{{Config::get('app.locale') }}" id="local">
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

    $("#image_url").change(function () {
        readURL(this);
    });

</script>

<script type="text/javascript">
    let id = 0;


    $(document).ready(function () {
        id = 0;




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


<script type="text/javascript">

    var suggestionItems = {};
    var sItems = {};
    var count = 1;

    $(document).ready(function() {

        $('#add_item').on('input',function(e){
            searchProduct($('#add_item').val());
        });

        $(document).on('click' , '.cancel-modal' , function (event) {
            $('#deleteModal').modal("hide");
            id = 0 ;
        });

        $(document).on('click' , '.deleteBtn' , function (event) {
            var row = $(this).parent().parent().index();

            var row1 = $(this).closest('tr');
            var item_id = row1.attr('data-item-id');
            delete sItems[item_id];
            loadItems();
            // var table = document.getElementById('tbody');
            // table.deleteRow(row);
        });

        $(document).on('click', '.select_product', function () {
            var row = $(this).closest('li');
            var item_id = row.attr('data-item-id');
            addItemToTable(suggestionItems[item_id]);
            document.getElementById('products_suggestions').innerHTML = '';
            suggestionItems = {};
        });

    });

    function searchProduct(code){
        var url = '{{route('getProduct',":id")}}';
        url = url.replace(":id",code);
        $.ajax({
            type:'get',
            url:url,
            dataType: 'json',

            success:function(response){

                document.getElementById('products_suggestions').innerHTML = '';
                if(response){
                    if(response.length == 1){
                        //addItemToTable
                        addItemToTable(response[0]);
                    }else if(response.length > 1){

                        showSuggestions(response);
                    } else if(response.id){
                        showSuggestions(response);
                    } else {
                        //showNotFoundAlert
                        openDialog();
                        document.getElementById('add_item').value = '' ;
                    }
                } else {
                    //showNotFoundAlert
                    openDialog();
                    document.getElementById('add_item').value = '' ;
                }
            }
        });
    }

    function showSuggestions(response) {
        var name = '';
        var local = document.getElementById('local').value;

        $data = '';
        $.each(response,function (i,item) {
            if(local == 'ar'){
                name = item.name_ar;
            } else {
                name = item.name_en;
            }

            suggestionItems[item.id] = item;
            $data +='<li class="select_product" data-item-id="'+item.id+'">'+name+'</li>';
        });
        document.getElementById('products_suggestions').innerHTML = $data;
    }

    function openDialog(){
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
    }
    function addItemToTable(item){
        if(count == 1){
            sItems = {};
        }

        if(sItems[item.id]){
            sItems[item.id].qnt = sItems[item.id].qnt +1;
        }
        else{
            item.qnt = item.quantity > 0 ? item.quantity : 1;
            sItems[item.id] = item;
        }
        count++;
        loadItems();

        document.getElementById('add_item').value = '' ;
    }

    var old_row_qty=0;
    var old_row_price = 0;
    var old_row_w_price = 0;

    $(document)
        .on('focus','.iQuantity',function () {
            old_row_qty = $(this).val();
        })
        .on('change','.iQuantity',function () {
            var row = $(this).closest('tr');
            if(!is_numeric($(this).val()) || parseFloat($(this).val()) < 0){
                $(this).val(old_row_qty);
                alert('wrong value');
                return;
            }

            var newQty = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');

            sItems[item_id].qnt= newQty;
            loadItems();

        });



    function is_numeric(mixed_var) {
        var whitespace = ' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000';
        return (
            (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -1)) &&
            mixed_var !== '' &&
            !isNaN(mixed_var)
        );
    }
    function loadItems(){

        var name = '';
        var local = document.getElementById('local').value;


        $('#sTable tbody').empty();
        $.each(sItems,function (i,item) {
            console.log(item);
            if(local == 'ar'){
                name = item.name_ar;
            } else {
                name = item.name_en;
            }
            var newTr = $('<tr data-item-id="'+item.id+'">');
            var tr_html ='<td class="text-center"><input type="hidden" name="product_id[]" value="'+item.id+'"> <span>'+ name+ '---' + (item.code)+'</span> </td>';
            tr_html +=   '<td class="text-center"><input type="text" class="form-control iQuantity" name="qnt[]" value="'+item.qnt.toFixed(2)+'"></td>';
            tr_html += `<td class="text-center">      <button type="button" class="btn btn-labeled btn-danger deleteBtn " value=" '+item.id+' ">
                                            <span class="btn-label" style="margin-right: 10px;"><i class="fa fa-trash"></i></span></button> </td>`;

            newTr.html(tr_html);
            newTr.appendTo('#sTable');
        });

    }
</script>


<script src="../../assets/js/core/popper.min.js"></script>
<script src="../../assets/js/core/bootstrap.min.js"></script>
<script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>

</body>

</html>






