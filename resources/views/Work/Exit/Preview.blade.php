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
                    <h1 class="h3 mb-0 text-primary-800 no-print">{{__('main.work_exit')}}
                        / {{__('main.work_exit_preview')}}</h1>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                   <div class="row">
                       <div class="card shadow mb-4 col-9">
                           <div class="card-header py-3">
                               <h6 class="m-0 font-weight-bold text-primary no-print">{{__('main.work_exit_preview')}}</h6>
                               <br>
                               <a href="{{route('workExitPrint' , $bill -> id)}}" target="_blank">

                                   <button type="button" class="btn btn-info no-print" >Print</button>
                               </a>

                           </div>
                           <div class="card-body">


                               <div class="row">
                                   <div class="col-4">
                                       <div class="form-group">
                                           <label>{{ __('main.client') }} <span
                                                   style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                           <input type="text" readonly name="client_id" id="client_id" class="form-control"
                                           value="{{$bill ->  vendor_name ? $bill ->  vendor_name : 'لقد قمت بحذف العميل'}}">
                                       </div>
                                   </div>
                                   <div class="col-4">
                                       <div class="form-group">
                                           <label>{{ __('main.date') }} <span
                                                   style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                           <input type="datetime-local" id="date" name="date"
                                                  class="form-control" disabled value="{{$bill -> date}}"
                                           />
                                       </div>
                                   </div>
                                   <div class="col-4">
                                       <div class="form-group">
                                           <label>{{ __('main.bill_no') }} <span
                                                   style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                           <input type="text" id="bill_number" name="bill_number"
                                                  class="form-control" placeholder="bill_no" readonly value="{{$bill -> bill_number}}"
                                           />
                                       </div>
                                   </div>
                               </div>
                               <div class="row">
                                   <div class="col-12">
                                       <div class="form-group">
                                           <label>{{ __('main.notes') }} <span
                                                   style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                           <textarea name="notes" id="notes" rows="3" placeholder="{{ __('main.notes') }}"
                                                     class="form-control-lg" style="width: 100%" readonly></textarea>
                                       </div>
                                   </div>
                               </div>



                               <div class="row">
                                   <div class="col-md-12">
                                       <div class="control-group table-group">
                                           <label class="table-label">{{__('main.items')}} </label>

                                           <div class="controls table-controls">
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

                                                   </tr>
                                                   </thead>
                                                   <tbody id="tbody">
                                                   @foreach($details as $detail)
                                                       <tr>
                                                           <td class="text-center"> {{Config::get('app.locale') == 'ar' ? $detail -> item_ar : $detail -> item_en}} </td>
                                                           <td class="text-center"> {{Config::get('app.locale') == 'ar' ? $detail -> karat_ar : $detail -> karat_en}} </td>
                                                           <td class="text-center"> {{$detail -> weight}} </td>
                                                           <td class="text-center" > {{$detail -> gram_price}} </td>
                                                           <td class="text-center" > {{$detail -> gram_manufacture}} </td>
                                                           <td class="text-center"> {{$detail -> gram_tax}} </td>
                                                           <td class="text-center"> {{$detail -> net_money}} </td>
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
                                       <?php $sum_count = 0 ?>
                                       <?php $sum_weight = 0 ?>
                                       <?php $sum_weight21 = 0 ?>
                                       <?php $sum_total = 0 ?>
                                       <?php $sum_tax = 0 ?>
                                       <?php $sum_made = 0 ?>

                                       @foreach($details as $item)
                                               <?php $sum_count += 1 ?>
                                               <?php $sum_weight += $item -> weight ?>
                                           <?php $sum_weight21 += $item -> weight * $item -> transform_factor ?>
                                           <?php $sum_total += $item -> weight * $item -> gram_price ?>
                                               <?php $sum_tax += $item -> weight * $item -> gram_tax ?>
                                               <?php $sum_made += $item -> weight * $item -> gram_manufacture ?>


                                           @endforeach

                                       <input type="text" readonly class="form-control" id="items_count" name="items_count" value="{{$sum_count}}">
                                   </div>
                               </div>
                               <div class="row" style="align-items: center; margin-bottom: 10px;">
                                   <div class="col-6">
                                       <label
                                           style="text-align: right;float: right;"> {{__('main.total_actual_weight')}} </label>
                                   </div>
                                   <div class="col-6">
                                       <input type="text" readonly class="form-control"
                                              id="total_actual_weight" value="{{$sum_weight}}">
                                   </div>
                               </div>
                               <div class="row" style="align-items: center; margin-bottom: 10px;">
                                   <div class="col-6">
                                       <label
                                           style="text-align: right;float: right;"> {{__('main.total_weight21')}} </label>
                                   </div>
                                   <div class="col-6">
                                       <input type="text" readonly class="form-control"
                                              id="total_weight21" name="total_weight21" value="{{$sum_weight21}}">
                                   </div>
                               </div>

                               <div class="row" style="align-items: center; margin-bottom: 10px;">
                                   <div class="col-6">
                                       <label
                                           style="text-align: right;float: right;"> {{__('main.first_total')}} </label>
                                   </div>
                                   <div class="col-6">
                                       <input type="text" readonly class="form-control" id="first_total" name="first_total" value="{{$sum_total}}">
                                   </div>
                               </div>
                               <div class="row" style="align-items: center; margin-bottom: 10px;">
                                   <div class="col-6">
                                       <label
                                           style="text-align: right;float: right;"> {{__('main.made_Value_t')}} </label>
                                   </div>
                                   <div class="col-6">
                                       <input type="text" readonly class="form-control" id="made_Value_t" name="made_Value_t" value="{{$sum_made}}">
                                   </div>
                               </div>
                               <div class="row" style="align-items: center; margin-bottom: 10px;">
                                   <div class="col-6">
                                       <label
                                           style="text-align: right;float: right;"> {{__('main.taxgold')}} </label>
                                   </div>
                                   <div class="col-6">
                                       <input type="text" readonly class="form-control" id="tax_total" name="tax_total" value="{{$sum_tax}}">
                                   </div>
                               </div>

                               <div class="row" style="align-items: center; margin-bottom: 10px;">
                                   <div class="col-6">
                                       <label
                                           style="text-align: right;float: right;"> {{__('main.additional_tax')}} </label>
                                   </div>
                                   <div class="col-6">
                                       <input type="text" readonly class="form-control" id="tax" name="tax" value="{{$bill -> tax}}">
                                   </div>
                               </div>


                               <div class="row" style="align-items: center; margin-bottom: 10px;">
                                   <div class="col-6">
                                       <label style="text-align: right;float: right;"
                                       > {{__('main.net')}} </label>
                                   </div>
                                   <div class="col-6">
                                       <input type="text" readonly class="form-control"  id="net_sales" name="net_sales" value="{{$bill -> total_money}}">
                                   </div>
                               </div>
                               <hr class="sidebar-divider d-none d-md-block">
                               <div class="row" style="align-items: baseline; margin-bottom: 10px;">
                                   <div class="col-6">
                                       <div class="form-group">
                                           <label
                                               style="text-align: right;float: right;"> {{__('main.discount')}} </label>
                                           <input type="number" step="any" readonly class="form-control" id="discount" name="discount" value="{{$bill -> discount}}">
                                       </div>
                                   </div>
                                   <div class="col-6">
                                       <div class="form-group">
                                           <label
                                               style="text-align: right;float: right;"> {{__('main.net_after_discount')}} </label>
                                           <input type="text" readonly  class="form-control" id="net_after_discount" name="net_after_discount" value="{{$bill -> net_money}}">
                                       </div>
                                   </div>
                               </div>


                               <hr class="sidebar-divider d-none d-md-block">



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


<script>


    $(document).ready(function () {
      //  printPage();

    });

    function printPage(){
        var css = '@page { size: landscape; }',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet){
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);

        window.print();
    }

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
