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
    @include('layouts.side' , ['slag' => 13 , 'subSlag' => 23])
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
                    <h1 class="h3 mb-0 text-primary-800">{{__('main.sales')}} / {{__('main.sales_create')}}</h1>
                </div>

                <div class="card-body px-0 pt-0 pb-2">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{__('main.sales_create')}}</h6>
                        </div>
                        <div class="card-body">
                            <form   method="POST" action="{{ route('store_sale') }}"
                                    enctype="multipart/form-data" >
                                @csrf

                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>{{ __('main.bill_date') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <input type="datetime-local"  id="bill_date" name="bill_date"
                                                   class="form-control"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>{{ __('main.bill_number') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <input type="text"  id="bill_number" name="bill_number"
                                                   class="form-control" placeholder="bill_number" readonly
                                            />
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-4 " >
                                        <div class="form-group">
                                            <label>{{ __('main.warehouse') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <select class="form-control mr-sm-2"
                                                    name="warehouse_id" id="warehouse_id">
                                                <option  value="0" selected>Choose...</option>
                                                @foreach ($warehouses as $item)
                                                    <option value="{{$item -> id}}"  > {{ $item -> name}}</option>

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-4 " >
                                        <div class="form-group">
                                            <label>{{ __('main.clients') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <select class="form-control mr-sm-2"
                                                    name="customer_id" id="customer_id">
                                                <option  value="0" selected>Choose...</option>
                                                @foreach ($customers as $item)
                                                    <option value="{{$item -> id}}"> {{ $item -> name}}</option>

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

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
                                                            <th class="text-center">{{__('main.price_without_tax')}}</th>
                                                            <th class="text-center">{{__('main.price_with_tax')}}</th>
                                                            <th class="text-center">{{__('main.quantity')}} </th>
                                                            <th class="text-center">{{__('main.total_without_tax')}}</th>
                                                            <th class="text-center">{{__('main.tax')}}</th>
                                                            <th class="text-center">{{__('main.net')}}</th>
                                                            <th style="max-width: 30px !important; text-align: center;" class="text-center">
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
                                <div class="row" style="display: flex ; align-items: center">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>{{ __('main.additional_service') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <input  name="additional_service" id="additional_service"  placeholder="0" class="form-control" type="number" />
                                        </div>
                                    </div>


                                    <div class="col-8">
                                        <div class="form-group">
                                            <label>{{ __('main.notes') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                            <textarea name="notes" id="notes" rows="3" placeholder="{{ __('main.notes') }}" class="form-control-lg" style="width: 100%"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <input type="submit" class="btn btn-primary" id="primary" tabindex="-1"
                                               style="width: 150px;
margin: 30px auto;" value="{{__('main.save_btn')}}"></input>

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
    var suggestionItems = {};
    var sItems = {};
    var count = 1;
    $(document).ready(function (){
        var now = new Date();

        document.getElementById('bill_date').valueAsDate = now;
        getBillNo();
        $('#warehouse_id').change(function (){
            getBillNo();
        });


        $('input[name=add_item]').change(function() {
            console.log($('#add_item').val());
        });
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
        });

        $(document).on('click', '.select_product', function () {
            var row = $(this).closest('li');
            var item_id = row.attr('data-item-id');
            addItemToTable(suggestionItems[item_id]);
            document.getElementById('products_suggestions').innerHTML = '';
            suggestionItems = {};
        });
    });
    function is_numeric(mixed_var) {
        var whitespace = ' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000';
        return (
            (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -1)) &&
            mixed_var !== '' &&
            !isNaN(mixed_var)
        );
    }
    function getBillNo(){
        let id = document.getElementById('warehouse_id').value ;
        let bill_number = document.getElementById('bill_number');
        $.ajax({
            type:'get',
            url:'/get_sales_no/' + id,
            dataType: 'json',

            success:function(response){
                console.log(response);

                if(response){
                    bill_number.value = response ;
                } else {
                    bill_number.value = '' ;
                }
            }
        });
    }


    function searchProduct(code){
        $.ajax({
            type:'get',
            url:'/getItemPro' + '/' + code,
            dataType: 'json',

            success:function(response){
                console.log(response);
                document.getElementById('products_suggestions').innerHTML = '';
                if(response){
                    if(response.length == 1){
                        //addItemToTable
                        if(response[0].item_type == 2){
                            addItemToTable(response[0]);
                        }

                    }else if(response.length > 1){
                        showSuggestions(response);
                    } else if(response.id){
                        //showSuggestions(response);
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

        $data = '';
        $.each(response,function (i,item) {
            if(item.item_type == 2) {
                suggestionItems[item.id] = item;
                $data += '<li class="select_product" data-item-id="' + item.id + '">' + item.name_ar + '</li>';
            }
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
            var price = item.cost;
            var taxType = item.tax_method;
            var taxRate = item.tax_rate == 1 ? 0 : 15;
            var itemTax = 0;
            var priceWithoutTax = 0;
            var priceWithTax = 0;
            var itemQnt = 1;

            if(taxType == 1){
                //included
                priceWithTax = price;
                priceWithoutTax = (price / (1+(taxRate/100)));
                itemTax = priceWithTax - priceWithoutTax;
            }else{
                //excluded
                itemTax = price * (taxRate/100);
                priceWithoutTax = price;
                priceWithTax = price + itemTax;
            }

            sItems[item.id] = item;
            sItems[item.id].price_with_tax =  parseInt(priceWithoutTax)  + parseInt(sItems[item.id].tax) ;
            console.log(sItems[item.id].tax);
            sItems[item.id].price_withoute_tax = priceWithoutTax;
            sItems[item.id].item_tax = itemTax;
            sItems[item.id].qnt = 1;

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

            console.log(newQty);
            console.log(item_id);
            sItems[item_id].qnt= newQty;
            loadItems();

        });

    $(document)
        .on('focus','.iPrice',function () {
            old_row_price = $(this).val();
        })
        .on('change','.iPrice',function () {
            var row = $(this).closest('tr');
            if(!is_numeric($(this).val()) || parseFloat($(this).val()) < 0){
                $(this).val(old_row_price);
                alert('wrong value');
                return;
            }

            var newQty = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');


            var item_tax =sItems[item_id].item_tax;
            var priceWithTax = newQty;
            if(item_tax > 0){
                priceWithTax = newQty * 1.15;
                item_tax = newQty * 0.15;
            }
            sItems[item_id].price_withoute_tax= newQty;
            sItems[item_id].price_with_tax= priceWithTax;
            sItems[item_id].item_tax= item_tax;
            loadItems();

        });

    $(document)
        .on('focus','.iPriceWTax',function () {
            old_row_w_price = $(this).val();
        })
        .on('change','.iPriceWTax',function () {
            var row = $(this).closest('tr');
            if(!is_numeric($(this).val()) || parseFloat($(this).val()) < 0){
                $(this).val(old_row_w_price);
                alert('wrong value');
                return;
            }

            var newQty = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');

            var item_tax =sItems[item_id].item_tax;
            var priceWithoutTax = newQty;
            if(item_tax > 0){
                priceWithoutTax = newQty / 1.15;
                item_tax = priceWithoutTax * 0.15;
            }
            sItems[item_id].price_withoute_tax= priceWithoutTax;
            sItems[item_id].price_with_tax= newQty;
            sItems[item_id].item_tax= item_tax;
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

        var total = 0 ;
        $('#sTable tbody').empty();
        $.each(sItems,function (i,item) {
            console.log(item);

            var newTr = $('<tr data-item-id="'+item.id+'">');
            var tr_html ='<td><input type="hidden" name="product_id[]" value="'+item.id+'"> <span>'+item.name_ar + '---' + (item.code)+'</span> </td>';
            tr_html +=   '<td><input type="text" class="form-control iPrice" name="price_without_tax[]" value="'+Number(item.price_withoute_tax).toFixed(2)+'"></td>';
            tr_html +=   '<td><input type="text" class="form-control iPriceWTax" name="price_with_tax[]" value="'+ Number(item.price_with_tax).toFixed(2)+'"></td>';
            tr_html +=   '<td><input type="text" class="form-control iQuantity" name="qnt[]" value="'+item.qnt+'"></td>';
            tr_html +=   '<td><input type="text" readonly="readonly" class="form-control" name="total[]" value="'+(item.price_withoute_tax*item.qnt).toFixed(2)+'"></td>';
            tr_html +=   '<td><input type="text" readonly="readonly" class="form-control" name="tax[]" value="'+(item.tax*item.qnt).toFixed(2)+'"></td>';
            tr_html +=   '<td><input type="text" readonly="readonly" class="form-control" name="net[]" value="'+(item.price_with_tax*item.qnt).toFixed(2)+'"></td>';
            tr_html += `<td>      <button type="button" class="btn btn-labeled btn-danger deleteBtn " value=" '+item.id+' ">
                                            <span class="btn-label" style="margin-right: 10px;"><i class="fa fa-trash"></i></span></button> </td>`;
            total += (item.price_with_tax*item.qnt);
            newTr.html(tr_html);
            newTr.appendTo('#sTable');
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



