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
    @include('layouts.side' , ['slag' => 8 , 'subSlag' => 19])
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
                    <h1 class="h3 mb-0 text-primary-800">{{__('main.work_exit')}} / {{__('main.work_exit_create')}}</h1>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <form   method="POST" action="{{ route('storeWorkExit') }}"
                            enctype="multipart/form-data" >
                        @csrf
                        <div class="row">
                            <div class="card shadow mb-4 col-9">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">{{__('main.work_exit_create')}}</h6>
                                </div>
                                <div class="card-body">


                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>{{ __('main.client') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                                <select class="form-control" id="client_id" name="client_id">

                                                    @foreach($vendors as $vendor)
                                                        <option value="{{$vendor -> id}}">{{$vendor -> name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>{{ __('main.date') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                                <input type="datetime-local"  id="date" name="date"
                                                       class="form-control"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>{{ __('main.bill_no') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                                <input type="text"  id="bill_number" name="bill_number"
                                                       class="form-control" placeholder="bill_no" readonly
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>{{ __('main.notes') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                                <textarea name="notes" id="notes" rows="3" placeholder="{{ __('main.notes') }}" class="form-control-lg" style="width: 100%"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="col-md-12" id="sticker">
                                                <div class="well well-sm">
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
                                            <div class="control-group table-group">
                                                <label class="table-label">{{__('main.items')}} </label>

                                                <div class="controls table-controls">
                                                    <table id="sTable" class="table items table-striped table-bordered table-condensed table-hover">
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

                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <input type="submit" class="btn btn-primary" id="primary" tabindex="-1"
                                                   style="width: 150px;
margin: 30px auto;" value="{{__('main.save_btn')}}"></input>

                                        </div>
                                    </div>






                                </div>
                            </div>
                            <div class="card shadow mb-4 col-3">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">{{__('main.totals')}}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row" style="align-items: center; margin-bottom: 10px;">
                                        <div class="col-6">
                                            <label
                                                style="text-align: right;float: right;"> {{__('main.items_count')}} </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" readonly class="form-control" id="items_count" name="items_count">
                                        </div>
                                    </div>
                                    <div class="row" style="align-items: center; margin-bottom: 10px;">
                                        <div class="col-6">
                                            <label
                                                style="text-align: right;float: right;"> {{__('main.total_actual_weight')}} </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" readonly class="form-control"
                                                   id="total_actual_weight">
                                        </div>
                                    </div>
                                    <div class="row" style="align-items: center; margin-bottom: 10px;">
                                        <div class="col-6">
                                            <label
                                                style="text-align: right;float: right;"> {{__('main.total_weight21')}} </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" readonly class="form-control"
                                                   id="total_weight21" name="total_weight21">
                                        </div>
                                    </div>

                                    <div class="row" style="align-items: center; margin-bottom: 10px;">
                                        <div class="col-6">
                                            <label
                                                style="text-align: right;float: right;"> {{__('main.first_total')}} </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" readonly class="form-control" id="first_total" name="first_total">
                                        </div>
                                    </div>
                                    <div class="row" style="align-items: center; margin-bottom: 10px;">
                                        <div class="col-6">
                                            <label
                                                style="text-align: right;float: right;"> {{__('main.made_Value_t')}} </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" readonly class="form-control" id="made_Value_t" name="made_Value_t">
                                        </div>
                                    </div>
                                    <div class="row" style="align-items: center; margin-bottom: 10px;">
                                        <div class="col-6">
                                            <label
                                                style="text-align: right;float: right;"> {{__('main.tax')}} </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" readonly class="form-control" id="tax_total" name="tax_total">
                                        </div>
                                    </div>
                                    <div class="row" style="align-items: center; margin-bottom: 10px;">
                                        <div class="col-6">
                                            <label style="text-align: right;float: right;"
                                            > {{__('main.net')}} </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" readonly class="form-control"  id="net_sales" name="net_sales">
                                        </div>
                                    </div>
                                    <hr class="sidebar-divider d-none d-md-block">
                                    <div class="row" style="align-items: baseline; margin-bottom: 10px;">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label
                                                    style="text-align: right;float: right;"> {{__('main.discount')}} </label>
                                                <input type="number" step="any"  class="form-control" id="discount" name="discount" placeholder="0" >
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label
                                                    style="text-align: right;float: right;"> {{__('main.net_after_discount')}} </label>
                                                <input type="text" readonly  class="form-control" id="net_after_discount" name="net_after_discount" placeholder="0">
                                            </div>
                                        </div>
                                    </div>


                                    <hr class="sidebar-divider d-none d-md-block">



                                </div>
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




<script type="text/javascript">

    var suggestionItems = {};
    var sItems = {};
    var count = 1;
  var local = 'ar' ;
    $(document).ready(function (){
        document.getElementById('items_count').value = 0  ;
        document.getElementById('total_actual_weight').value = 0;
        document.getElementById('total_weight21').value = 0;
        document.getElementById('first_total').value = 0;
        document.getElementById('made_Value_t').value = 0;
        document.getElementById('tax_total').value = 0;
        document.getElementById('net_sales').value = 0;
        document.getElementById('discount').value = 0;
        document.getElementById('net_after_discount').value = 0;
        $(document).on('change', '#discount', function () {
            var net = document.getElementById('net_sales').value;
            var net_after_discount = Number(net) - Number(this.value);
            document.getElementById('net_after_discount').value = net_after_discount ;


        });

        $(document).on('keyup', '#discount', function () {
            var net = document.getElementById('net_sales').value;
            var net_after_discount = Number(net) - Number(this.value);
            document.getElementById('net_after_discount').value = net_after_discount ;


        });

        var now = new Date();
        local = document.getElementById('local').value ;
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        now.setMilliseconds(null);
        now.setSeconds(null);

        document.getElementById('date').value = now.toISOString().slice(0, -1);
        getBillNo();
        $('input[name=add_item]').change(function() {
            console.log($('#add_item').val());
        });
        $('#add_item').on('input',function(e){
            searchProduct($('#add_item').val());
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
    function is_numeric(mixed_var) {
        var whitespace = ' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000';
        return (
            (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -1)) &&
            mixed_var !== '' &&
            !isNaN(mixed_var)
        );
    }
    function getBillNo(){

        let bill_number = document.getElementById('bill_number');
        $.ajax({
            type:'get',
            url:'{{route('get_work_exit_no')}}',
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
                        if(response[0].state == 1){
                            addItemToTable(response[0]);
                        }

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

        console.log(response);
        $data = '';
        $.each(response,function (i,item) {
            if(item.item_type == 1) {
                if(item.state == 1){
                    suggestionItems[item.id] = item;
                    if (local == 'ar') {
                        $data += '<li class="select_product" data-item-id="' + item.id + '">' + item.name_ar + '</li>';
                    } else {
                        $data += '<li class="select_product" data-item-id="' + item.id + '">' + item.name_en + '</li>';
                    }
                }

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
            alert('This Item Entry has Already been made');
            return;
        }
        else{
            var price = item.price;
            var taxType = item.tax_method;
            var taxRate = item.tax_rate == 1 ? 0 : 15;
            var itemTax = 0;
            var priceWithoutTax = 0;
            var priceWithTax = 0;
            var itemQnt = item.weight;

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
            console.log(sItems);

        }
        count++;
       loadItems();

        document.getElementById('add_item').value = '' ;
    }


    function loadItems(){

        var items_count_val = 0 ;
        var total_actual_weight_val = 0 ;
        var total_weight21_val = 0 ;
        var first_total_val = 0 ;
        var made_Value_t_val = 0 ;
        var tax_total_val =0 ;
        var net_sales_val = 0 ;
        var discount_val = 0 ;
        var net_after_discount_val =0 ;

        $('#sTable tbody').empty();
        $.each(sItems,function (i,item) {
            console.log(item);

            var newTr = $('<tr data-item-id="'+item.id+'">');
            var tr_html = local == 'ar'? '<td class="text-center"><input type="hidden" name="item_id[]" value="'+item.id+'"> <span>'+item.name_ar + '---' + (item.code)+'</span> </td>'
            :                            '<td class="text-center"><input type="hidden" name="item_id[]" value="'+item.id+'"> <span>'+item.name_en + '---' + (item.code)+'</span> </td>';
            tr_html +=  local == 'ar'?   '<td class="text-center"><input type="hidden" name="karat_id[]" value="'+item.karat_id+'"> <span>'+item.karat.name_ar+'</span> </td>'
                :                        '<td class="text-center"><input type="hidden" name="karat_id[]" value="'+item.karat_id+'"> <span>'+item.karat.name_en+'</span> </td>';
            tr_html +=   '<td><input type="text" class="form-control iPriceWTax" name="weight[]" value="'+item.weight+'" readonly></td>';
            tr_html +=   '<td><input type="text" class="form-control iQuantity" name="gram_price[]" value="'+item.price.toFixed(2)+'" readonly></td>';
            tr_html +=   '<td><input type="text" readonly="readonly" class="form-control" name="gram_manufacture[]" value="'+(item.made_Value)+'"></td>';
            tr_html +=   '<td><input type="text" readonly="readonly" class="form-control" name="gram_tax[]" value="'+(item.karat.stamp_value)+'"></td>';
            tr_html +=   '<td><input type="text" readonly="readonly" class="form-control" name="net_money[]" value="'+(item.weight*( Number(item.made_Value) +  Number(item.karat.stamp_value) + Number(item.price))).toFixed(2)+'"></td>';
            tr_html += `<td>      <button type="button" class="btn btn-labeled btn-danger deleteBtn " value=" '+item.id+' ">
                                            <span class="btn-label" style="margin-right: 10px;"><i class="fa fa-trash"></i></span></button> </td>`;

            newTr.html(tr_html);
            newTr.appendTo('#sTable');

            items_count_val += 1 ;
            total_actual_weight_val += Number(item.weight) ;
            total_weight21_val += Number(item.weight) * Number(item.karat.transform_factor);
            first_total_val += Number(item.weight)  * Number(item.price)  ;
            made_Value_t_val +=   Number(item.weight)  * Number(item.made_Value)  ;
            tax_total_val +=  Number(item.weight)  * Number(item.karat.stamp_value)  ;
            net_sales_val += (Number(item.weight) * (Number(item.made_Value) + Number(item.karat.stamp_value) + Number(item.price)));
            discount_val = document.getElementById('discount').value;
            if(!discount_val) discount_val = 0 ;
            net_after_discount_val = net_sales_val -  discount_val ;

        });
        document.getElementById('items_count').value =items_count_val.toFixed(2) ;
        document.getElementById('total_actual_weight').value = total_actual_weight_val.toFixed(2);
        document.getElementById('total_weight21').value = total_weight21_val.toFixed(2);
        document.getElementById('first_total').value = first_total_val.toFixed(2);
        document.getElementById('made_Value_t').value = made_Value_t_val.toFixed(2);
        document.getElementById('tax_total').value = tax_total_val.toFixed(2);
        document.getElementById('net_sales').value = net_sales_val.toFixed(2);
        document.getElementById('discount').value = discount_val;
        document.getElementById('net_after_discount').value = net_sales_val.toFixed(2);
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
