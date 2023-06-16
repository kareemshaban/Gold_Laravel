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
    @include('layouts.side' , ['slag' => 9 , 'subSlag' => 15])
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
                    <h1 class="h3 mb-0 text-primary-800 no-print">{{__('main.old_gold_exit')}} / {{__('main.exit_old_preview')}}</h1>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                  <div class="row">
                      <div class="card shadow mb-4 col-9">
                          <div class="card-header py-3">
                              <a href="{{route('oldExitPrint' , $bill -> id)}}" target="_blank">

                                  <button type="button" class="btn btn-info no-print" >Print</button>
                              </a>
                              <h6 class="m-0 font-weight-bold text-primary no-print">{{__('main.exit_old_preview')}}</h6> <br>
                          </div>
                          <div class="card-body">


                              <div class="row">
                                  <div class="col-4">
                                      <div class="form-group">
                                          <label>{{ __('main.supplier') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                          <select class="form-control" id="supplier_id" name="supplier_id" disabled>

                                              @foreach($vendors as $vendor)
                                                  <option value="{{$vendor -> id}}" @if($vendor -> id == $bill -> supplier_id) selected @endif >{{$vendor -> name}}</option>
                                              @endforeach
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-4">
                                      <div class="form-group">
                                          <label>{{ __('main.date') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                          <input type="datetime-local"  id="date" name="date"
                                                 class="form-control" value="{{$bill -> date}}" readonly
                                          />
                                      </div>
                                  </div>
                                  <div class="col-4">
                                      <div class="form-group">
                                          <label>{{ __('main.bill_no') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                          <input type="text"  id="bill_number" name="bill_number"
                                                 class="form-control" placeholder="bill_no" value="{{$bill -> bill_number}}" readonly
                                          />
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-12">
                                      <div class="form-group">
                                          <label>{{ __('main.notes') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                          <textarea name="notes" id="notes" rows="3" placeholder="{{ __('main.notes') }}" class="form-control-lg" style="width: 100%" disabled>{{$bill ->notes }}</textarea>
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

                                                      <th class="text-center">{{__('main.karat')}}</th>
                                                      <th class="text-center">{{__('main.weight')}}</th>
                                                      <th class="text-center">{{__('main.total_weight21')}} </th>
                                                      <th class="text-center" hidden>{{__('main.total_money')}}</th>
                                                      <th class="text-center" hidden>{{__('main.net_money')}}</th>
                                                      <th class="text-center">{{__('main.net_weight')}}</th>
                                                  </tr>
                                                  </thead>
                                                  <tbody id="tbody">
                                                  @foreach($details as $detail)
                                                      <tr>
                                                          <td class="text-center"> {{Config::get('app.locale') == 'ar' ? $detail -> karat_ar : $detail -> karat_en}} </td>
                                                          <td class="text-center"> {{$detail -> weight}} </td>
                                                          <td class="text-center"> {{$detail -> weight21}} </td>
                                                          <td class="text-center" hidden> {{$detail -> made_money}} </td>
                                                          <td class="text-center" hidden> {{$detail -> net_money}} </td>
                                                          <td class="text-center"> {{$detail -> net_weight}} </td>

                                                      </tr>
                                                  @endforeach
                                                  </tbody>
                                                  <tfoot></tfoot>
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
                                          style="text-align: right;float: right;"> {{__('main.total_actual_weight')}} </label>
                                  </div>
                                  <div class="col-6">
                                      <?php $sum_weight = 0  ; $sum_weight21 = 0 ; ?>
                                      @foreach($details as $item)
                                          <?php $sum_weight +=  $item -> weight  ;
                                          $sum_weight21 +=  $item -> weight21 ; ?>
                                      @endforeach

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
                                             id="total_weight21" value="{{$sum_weight21}}">
                                  </div>
                              </div>


                              <div class="row" style="align-items: center; margin-bottom: 10px;" >
                                  <div class="col-6">
                                      <label style="text-align: right;float: right;"
                                      > {{__('main.total_tax')}} </label>
                                  </div>
                                  <div class="col-6">
                                      <input type="text" readonly class="form-control"  id="tax" value="{{$bill -> tax}}">
                                  </div>
                              </div>

                              <hr class="sidebar-divider d-none d-md-block">
                              <div class="row" style="align-items: baseline; margin-bottom: 10px;" hidden>
                                  <div class="col-6">
                                      <div class="form-group">
                                          <label
                                              style="text-align: right;float: right;"> {{__('main.discount')}} </label>
                                          <input type="number" step="any"  class="form-control" id="discount" name="discount" placeholder="0">
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


                </div>


            </div>
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
