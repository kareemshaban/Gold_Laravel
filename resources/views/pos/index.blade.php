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
    @include('layouts.side' , ['slag' => 15 , 'subSlag' => 151])
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            @include('layouts.header')
            <!-- End of Topbar -->

            <!-- Begin Page Content -->


            <div class="container-fluid ">

                @include('flash-message')

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home"
                                type="button" role="tab" aria-controls="home"
                                aria-selected="true">{{__('main.pos_sales')}}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button"
                                role="tab" aria-controls="profile" aria-selected="false">{{__('main.pos_purchase')}}
                        </button>
                    </li>

                </ul>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form method="POST" action="{{ route('store_pos') }}"
                                  enctype="multipart/form-data" id="pos_sales_form">
                                @csrf
                                <div class="row">
                                    <div class="card shadow mb-4 col-9">
                                        <div class="card-header py-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h6 class="m-0 font-weight-bold text-primary text-right">{{__('main.pos_sales')}}</h6>
                                                </div>
                                                <div class="col-6">
                                                    <a href="{{route('pos_sales')}}">
                                                        <button class="btn btn-info" type="button">{{__('main.show_pos_sales')}}</button>
                                                    </a>

                                                </div>
                                            </div>


                                        </div>
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label style="float: right;">{{ __('main.bill_date') }} <span
                                                                style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                        </label>
                                                        <input type="datetime-local" id="bill_date" name="bill_date"
                                                               class="form-control"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label style="float: right;">{{ __('main.bill_number') }} <span
                                                                style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                        </label>
                                                        <input type="text" id="bill_number" name="bill_number"
                                                               class="form-control" placeholder="bill_number" readonly
                                                        />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-6 ">
                                                    <div class="form-group">
                                                        <label style="float: right;">{{ __('main.clients') }} <span
                                                                style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                        </label>
                                                        <select class="form-control mr-sm-2"
                                                                name="customer_id" id="customer_id">
                                                            <option value="0" selected>Choose...</option>
                                                            @foreach ($customers as $item)
                                                                <option
                                                                    value="{{$item -> id}}"> {{ $item -> name}}</option>

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6 ">
                                                    <div class="form-group">
                                                        <label style="float: right;">{{ __('main.document_type') }} <span
                                                                style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                        </label>
                                                        <select class="form-control mr-sm-2"
                                                                name="document_type" id="document_type">
                                                            <option value="1" >{{__('main.new_gold')}}</option>
                                                            <option value="2" >{{__('main.old_gold')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="bill_client_name">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label style="float: right;">{{ __('main.bill_client_name') }} <span
                                                                style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                        </label>
                                                      <input class="form-control" name="bill_client_name" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label style="float: right;">{{ __('main.notes') }} <span
                                                                style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                        </label>
                                                        <textarea name="notes" id="notes" rows="3"
                                                                  placeholder="{{ __('main.notes') }}"
                                                                  class="form-control-lg"
                                                                  style="width: 100%"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="document_type1">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="col-md-12" id="sticker">
                                                            <div class="well well-sm"
                                                                 @if(Config::get('app.locale') == 'ar')style="direction: rtl;" @endif>
                                                                <div class="form-group" style="margin-bottom:0;">
                                                                    <div class="input-group wide-tip">
                                                                        <div class="input-group-addon"
                                                                             style="padding-left: 10px; padding-right: 10px;">
                                                                            <i class="fa fa-2x fa-barcode addIcon"></i>
                                                                        </div>
                                                                        <input
                                                                            style="border-radius: 0 !important;padding-left: 10px;padding-right: 10px;"
                                                                            type="text" name="add_item" value=""
                                                                            class="form-control input-lg ui-autocomplete-input"
                                                                            id="add_item"
                                                                            placeholder="{{__('main.add_item_hint')}}"
                                                                            autocomplete="off">

                                                                    </div>

                                                                </div>
                                                                <ul class="suggestions" id="products_suggestions"
                                                                    style="display: block">

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
                                                                            <th style="max-width: 30px !important; text-align: center;"
                                                                                class="text-center">
                                                                                <i class="fa fa-trash-o"
                                                                                   style="opacity:0.5; filter:alpha(opacity=50);"></i>
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
                                            </div>
                                            <div class="document_type2">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="col-md-12" id="sticker">
                                                            <div class="well well-sm">
                                                                <div class="form-group" style="margin-bottom:0;">
                                                                    <div class="input-group wide-tip">
                                                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                                                            <i class="fa fa-2x fa-search addIcon"></i>
                                                                        </div>
                                                                        <select class="form-control" id="karat_select0" name="karat_select0">

                                                                            @foreach($karats as $karat)
                                                                                <option value="{{$karat -> id}}">{{ Config::get('app.locale') == 'en' ?$karat -> name_en : $karat -> name_ar}}</option>

                                                                            @endforeach
                                                                        </select>
                                                                        <div style="margin-left: 20px ; margin-right: 20px;">
                                                                            <button type="button" class="btn btn-labeled btn-primary " id="createButton0">
                                                                                <span class="btn-label" style="margin-right: 10px;"><i class="fa fa-download"></i></span>{{__('main.select_ele')}}</button>
                                                                        </div>

                                                                    </div>

                                                                </div>

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
                                                                <table id="sTable0" class="table items table-striped table-bordered table-condensed table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th hidden>id</th>
                                                                        <th class="text-center" >{{__('main.karat')}}</th>
                                                                        <th class="text-center" >{{__('main.weight')}}</th>
                                                                        <th class="text-center" >{{__('main.total_weight21')}} </th>
                                                                        <th class="text-center" >{{__('main.price_gram')}}</th>
                                                                        <th class="text-center" >{{__('main.gram_made_value')}}</th>
                                                                        <th class="text-center" >{{__('main.gram_tax')}}</th>
                                                                        <th class="text-center" > {{__('main.net_money')}}</th>
                                                                        <th class="text-center" >{{__('main.net_weight')}}</th>
                                                                        <th style="max-width: 30px !important; text-align: center;" class="text-center">
                                                                            <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                                                        </th>
                                                                        <th hidden>factor</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="tbody0"></tbody>
                                                                    <tfoot></tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                    <div class="card shadow mb-4 col-3">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">{{__('main.sales_invoice_total')}}</h6>
                                        </div>
                                        <div class="card-body ">
                                            <div class="row document_type1" style="align-items: center; margin-bottom: 10px;">
                                                <div class="col-6">
                                                    <label
                                                        style="text-align: right;float: right;"> {{__('main.items_count')}} </label>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control" id="items_count">
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
                                                    <input type="text" readonly class="form-control" id="first_total">
                                                </div>
                                            </div>
                                            <div class="row" style="align-items: center; margin-bottom: 10px;">
                                                <div class="col-6">
                                                    <label
                                                        style="text-align: right;float: right;"> {{__('main.made_Value_t')}} </label>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control" id="made_Value_t">
                                                </div>
                                            </div>
                                            <div class="row" style="align-items: center; margin-bottom: 10px;">
                                                <div class="col-6">
                                                    <label
                                                        style="text-align: right;float: right;"> {{__('main.taxgold')  }} </label>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control" id="tax_total">
                                                </div>
                                            </div>
                                            <div class="row" style="align-items: center; margin-bottom: 10px;">
                                                <div class="col-6">
                                                    <label
                                                        style="text-align: right;float: right;"> {{__('main.additional_tax')  }} </label>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control" id="tax" name="tax">
                                                </div>
                                            </div>

                                            <div class="row" style="align-items: center; margin-bottom: 10px;">
                                                <div class="col-6">
                                                    <label style="text-align: right;float: right;"
                                                          > {{__('main.net')}} </label>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control"  id="net_sales">
                                                </div>
                                            </div>
                                            <hr class="sidebar-divider d-none d-md-block">
                                            <div class="row" style="align-items: baseline; margin-bottom: 10px;">
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

                                            <div class="row" hidden style="align-items: center; margin-bottom: 10px;">
                                                <div class="form-group">
                                                    <label
                                                        style="text-align: right;float: right;"> {{__('main.paid')}} </label>
                                                    <input type="number" step="any"  class="form-control" id="paid" name="paid" placeholder="0">
                                                </div>
                                            </div>
                                            <hr class="sidebar-divider d-none d-md-block">


                                            <div class="show_modal1">

                                            </div>



                                        </div>








                                        <div class="row">
                                            <div class="col-md-6 text-center" style="display: block; margin: auto;">
                                                <input type="button" class="btn btn-warning" id="pos_sales_btn"
                                                       tabindex="-1"
                                                       style="width: 150px;
                                                   margin: 30px auto;" value="{{__('main.pay')}}"></input>

                                            </div>
                                        </div>
                                    </div>

                                </div>



                            </form>
                        </div>








                        <!--purchase TAB-->
                        <div class="tab-pane fade show " id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <form method="POST" action="{{ route('store_pos_purchase') }}"
                                  enctype="multipart/form-data" id="pos_purchase_form">
                                @csrf
                         <div class="row">
                             <div class="card shadow mb-4 col-9">
                                 <div class="card-header py-3">
                                     <div class="row">
                                         <div class="col-6">
                                             <h6 class="m-0 font-weight-bold text-primary text-right">{{__('main.pos_purchase')}}</h6>
                                         </div>
                                         <div class="col-6">
                                            <a href="{{route('pos_purchase')}}">
                                                <button class="btn btn-info" type="button">{{__('main.show_pos_purchase')}}</button>
                                            </a>

                                         </div>
                                     </div>

                                 </div>
                                 <div class="card-body">




                                         <div class="row">

                                             <div class="col-6">
                                                 <div class="form-group">
                                                     <label style="float: right;">{{ __('main.bill_date') }} <span
                                                             style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                     </label>
                                                     <input type="datetime-local" id="bill_date2" name="bill_date2"
                                                            class="form-control"
                                                     />
                                                 </div>
                                             </div>
                                             <div class="col-6">
                                                 <div class="form-group">
                                                     <label style="float: right;">{{ __('main.bill_number') }} <span
                                                             style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                     </label>
                                                     <input type="text" id="bill_number2" name="bill_number2"
                                                            class="form-control" placeholder="bill_number" readonly
                                                     />
                                                 </div>
                                             </div>

                                         </div>
                                         <div class="row">
                                             <div class="col-6 ">
                                                 <div class="form-group">
                                                     <label style="float: right;">{{ __('main.clients') }} <span
                                                             style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                     </label>
                                                     <select class="form-control mr-sm-2"
                                                             name="customer_id2" id="customer_id2">
                                                         <option value="0" selected>Choose...</option>
                                                         @foreach ($customers as $item)
                                                             <option
                                                                 value="{{$item -> id}}"> {{ $item -> name}}</option>

                                                         @endforeach
                                                     </select>
                                                 </div>
                                             </div>
                                             <div class="col-6 ">
                                                 <div class="form-group">
                                                     <label style="float: right;">{{ __('main.document_type') }} <span
                                                             style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                     </label>
                                                     <select class="form-control mr-sm-2"
                                                             name="document_type2" id="document_type2">
                                                         <option value="2" >{{__('main.old_gold')}}</option>
                                                     </select>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="row">
                                             <div class="col-12">
                                                 <div class="form-group">
                                                     <label style="float: right;">{{ __('main.notes') }} <span
                                                             style="color:red; font-size:20px; font-weight:bold;">*</span>
                                                     </label>
                                                     <textarea name="notes2" id="notes2" rows="3"
                                                               placeholder="{{ __('main.notes') }}"
                                                               class="form-control-lg"
                                                               style="width: 100%"></textarea>
                                                 </div>
                                             </div>

                                         </div>


                                         <div class="row">
                                             <div class="col-12">
                                                 <div class="col-md-12" id="sticker">
                                                     <div class="well well-sm">
                                                         <div class="form-group" style="margin-bottom:0;">
                                                             <div class="input-group wide-tip">
                                                                 <div class="input-group-addon"
                                                                      style="padding-left: 10px; padding-right: 10px;">
                                                                     <i class="fa fa-2x fa-search addIcon"></i>
                                                                 </div>
                                                                 <select class="form-control" id="karat_select2"
                                                                         name="karat_select2">

                                                                     @foreach($karats as $karat)
                                                                         <option
                                                                             value="{{$karat -> id}}">{{ Config::get('app.locale') == 'en' ?$karat -> name_en : $karat -> name_ar}}</option>

                                                                     @endforeach
                                                                 </select>
                                                                 <div style="margin-left: 20px ; margin-right: 20px;">
                                                                     <button type="button"
                                                                             class="btn btn-labeled btn-primary "
                                                                             id="createButton2">
                                                                        <span class="btn-label"
                                                                              style="margin-right: 10px;"><i
                                                                                class="fa fa-download"></i></span>{{__('main.select_ele')}}
                                                                     </button>
                                                                 </div>

                                                             </div>

                                                         </div>

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
                                                         <table id="sTable2"
                                                                class="table items table-striped table-bordered table-condensed table-hover">
                                                             <thead>
                                                             <tr>
                                                                 <th hidden>id</th>
                                                                 <th>{{__('main.karat')}}</th>
                                                                 <th class="text-center">{{__('main.weight')}}</th>
                                                                 <th class="text-center">{{__('main.total_weight21')}} </th>
                                                                 <th class="text-center" >{{__('main.price_gram')}}</th>
                                                                 <th class="text-center">{{__('main.net_money')}}</th>
                                                                 <th class="text-center">{{__('main.net_weight')}}</th>
                                                                 <th style="max-width: 30px !important; text-align: center;"
                                                                     class="text-center">
                                                                     <i class="fa fa-trash-o"
                                                                        style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                                                 </th>
                                                                 <th hidden>factor</th>
                                                             </tr>
                                                             </thead>
                                                             <tbody id="tbody2"></tbody>
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
                                     <h6 class="m-0 font-weight-bold text-primary">{{__('main.purchase_invoice_total')}}</h6>
                                 </div>
                                 <div class="card-body ">

                                     <div class="row" style="align-items: center; margin-bottom: 10px;">
                                         <div class="col-6">
                                             <label
                                                 style="text-align: right;float: right;"> {{__('main.total_actual_weight')}} </label>
                                         </div>
                                         <div class="col-6">
                                             <input type="text" readonly class="form-control"
                                                    id="total_actual_weight_purchase">
                                         </div>
                                     </div>
                                     <div class="row" style="align-items: center; margin-bottom: 10px;">
                                         <div class="col-6">
                                             <label
                                                 style="text-align: right;float: right;"> {{__('main.total_weight21')}} </label>
                                         </div>
                                         <div class="col-6">
                                             <input type="text" readonly class="form-control"
                                                    id="total_weight21_purchase" name="total_weight21_purchase">
                                         </div>
                                     </div>


                                     <div class="row" style="align-items: center; margin-bottom: 10px;">
                                         <div class="col-6">
                                             <label style="text-align: right;float: right;"
                                             > {{__('main.net')}} </label>
                                         </div>
                                         <div class="col-6">
                                             <input type="text" readonly class="form-control"  id="net_purchase">
                                         </div>
                                     </div>


                                     <div class="row" style="align-items: center; margin-bottom: 10px;" hidden>
                                         <div class="col-6">
                                             <label style="text-align: right;float: right;"
                                             > {{__('main.total_tax')}} </label>
                                         </div>
                                         <div class="col-6">
                                             <input type="text" readonly class="form-control"  id="tax2" name="tax" value="0">
                                         </div>
                                     </div>

                                     <hr class="sidebar-divider d-none d-md-block">
                                     <div class="row" style="align-items: baseline; margin-bottom: 10px;">
                                         <div class="col-6">
                                             <div class="form-group">
                                                 <label
                                                     style="text-align: right;float: right;"> {{__('main.discount')}} </label>
                                                 <input type="number" step="any"  class="form-control" id="discount2" name="discount2" placeholder="0">
                                             </div>
                                         </div>
                                         <div class="col-6">
                                             <div class="form-group">
                                                 <label
                                                     style="text-align: right;float: right;"> {{__('main.net_after_discount')}} </label>
                                                 <input type="text" readonly  class="form-control" id="net_after_discount2" name="net_after_discount2" placeholder="0">
                                             </div>
                                         </div>
                                     </div>


                                     <hr class="sidebar-divider d-none d-md-block">





                                 </div>



                                 <div class="show_modal2">

                                 </div>




                                 <div class="row">
                                     <div class="col-md-6 text-center" style="display: block; margin: auto;">
                                         <input type="button" class="btn btn-warning" id="pos_purchase_btn"
                                                tabindex="-1"
                                                style="width: 150px;
                                                   margin: 30px auto;" value="{{__('main.pay')}}"></input>

                                     </div>
                                 </div>
                             </div>
                         </div>

                            </form>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /.container-fluid -->
            <input id="local" value="{{Config::get('app.locale')}}" hidden>
            <input id="taxPer" value="{{$setting -> enabled == 1 ? $setting -> value : 0}}" hidden>
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
    window.onload = function() {

    };
    $(document).ready(function () {
      //bill_client_name
       if($('#customer_id').prop('selectedIndex') == 1){
           $('#bill_client_name').slideDown();
       } else {
           $('#bill_client_name').slideUp();
       }

        $('#customer_id').change(function (){
            if(this.selectedIndex == 1){
                $('#bill_client_name').slideDown();
            } else {
                $('#bill_client_name').slideUp();
            }
        });

        $(document).on('click', '#payment_btn', function () {

            console.log($('#home-tab').classList);
            console.log($('#profile-tab').classList);
            const money = document.getElementById('money').value;
            const cash = document.getElementById('cash').value;
            const visa = document.getElementById('visa').value;
            const type = document.getElementById('type').value;
            if(Number(money) == (  Number(cash) + Number(visa) ) ){

                if(type == '1'){
                    document.getElementById('pos_sales_form').submit();
                } else {
                    document.getElementById('pos_purchase_form').submit();
                }

              //
            //
            } else {
                alert($('<div>{{trans('main.paid_must_equal_net')}}</div>').text());
            }


        });

        $(document).on('change', '#cash', function () {
            const money = document.getElementById('money').value;
            var visa = Number(money) - Number(this.value);
            document.getElementById('visa').value = visa ;

        });
        $(document).on('keyup', '#cash', function () {
            const money = document.getElementById('money').value;
            var visa = Number(money) - Number(this.value);
            document.getElementById('visa').value = visa ;
        });

        document.getElementById('document_type').value = 1 ;
        $('.document_type1').slideDown();
        $('.document_type2').slideUp();
        $(document).on('change', '#document_type', function () {
            getBillNo();
           if(this.value == 1){
               $('.document_type1').slideDown();
               $('.document_type2').slideUp();
               $('#sTable0 tbody').empty();
               document.getElementById('items_count').value = 0  ;
               document.getElementById('total_actual_weight').value = 0;
               document.getElementById('total_weight21').value = 0;
               document.getElementById('first_total').value = 0;
               document.getElementById('made_Value_t').value = 0;
               document.getElementById('tax_total').value = 0;
               document.getElementById('net_sales').value = 0;
               document.getElementById('discount').value = 0;
               document.getElementById('net_after_discount').value = 0;
               document.getElementById('tax').value = 0;
               document.getElementById('tax2').value = 0;

           }  else {
               $('.document_type2').slideDown();
               $('.document_type1').slideUp();
               $('#sTable tbody').empty();
               document.getElementById('items_count').value = 0  ;
               document.getElementById('total_actual_weight').value = 0;
               document.getElementById('total_weight21').value = 0;
               document.getElementById('first_total').value = 0;
               document.getElementById('made_Value_t').value = 0;
               document.getElementById('tax_total').value = 0;
               document.getElementById('net_sales').value = 0;
               document.getElementById('discount').value = 0;
               document.getElementById('net_after_discount').value = 0;
               document.getElementById('tax').value = 0;
               document.getElementById('tax2').value = 0;
           }
        });


        document.getElementById('items_count').value = 0  ;
        document.getElementById('total_actual_weight').value = 0;
        document.getElementById('total_weight21').value = 0;
        document.getElementById('first_total').value = 0;
        document.getElementById('made_Value_t').value = 0;
        document.getElementById('tax_total').value = 0;
        document.getElementById('net_sales').value = 0;
        document.getElementById('discount').value = 0;
        document.getElementById('net_after_discount').value = 0;
        document.getElementById('tax').value = 0;
        document.getElementById('tax2').value = 0;

        document.getElementById('total_actual_weight_purchase').value = 0;
        document.getElementById('total_weight21_purchase').value = 0;
        document.getElementById('net_purchase').value = 0;

        document.getElementById('discount2').value = 0;
        document.getElementById('net_after_discount2').value =0;


        document.getElementById('paid').value = 0;
        $(document).on('change', '#discount', function () {
           var net = document.getElementById('net_sales').value;
           var tax = document.getElementById('tax').value ;

           var net_after_discount = Number(net) - Number(this.value) + Number(tax);
            document.getElementById('net_after_discount').value = net_after_discount.toFixed(2) ;


        });
        $(document).on('keyup', '#discount', function () {
            var net = document.getElementById('net_sales').value;
            var tax = document.getElementById('tax').value ;

            var net_after_discount = Number(net) - Number(this.value) + Number(tax);
            document.getElementById('net_after_discount').value = net_after_discount.toFixed(2) ;
        });


        $(document).on('change', '#discount2', function () {
            var net = document.getElementById('net_purchase').value;
            var tax = document.getElementById('tax2').value ;
            var net_after_discount = Number(net) - Number(this.value) + Number(tax);
            document.getElementById('net_after_discount2').value = net_after_discount.toFixed(2) ;


        });
        $(document).on('keyup', '#discount2', function () {
            var net = document.getElementById('net_purchase').value;
            var tax = document.getElementById('tax2').value ;
            var net_after_discount = Number(net) - Number(this.value) + Number(tax);
            document.getElementById('net_after_discount2').value = net_after_discount.toFixed(2) ;
        });



        $(document).on('click', '#pos_sales_btn', function () {
           var rows =  0 ;
             var document_type = document.getElementById('document_type').value ;
              rows = document_type == 1 ? ($('#sTable tbody tr').length) : ($('#tbody0 tr').length);
            console.log(rows);
          //  var paid =  document.getElementById('paid').value;
            var net_after_discount = document.getElementById('net_after_discount').value;
            var client = document.getElementById('customer_id').value ;
            if(client > 0) {
                if (rows > 0){
                    if (/*Number(paid) - Number(net_after_discount) == 0*/ true) {
                        openPaymentModal(net_after_discount , 1);

                        localStorage.setItem('openModal', net_after_discount);
                    } else {
                        alert($('<div>{{trans('main.paid_must_equal_net')}}</div>').text());
                    }
            } else {
                    alert($('<div>{{trans('main.no_bill_details')}}</div>').text());
                }
            } else {
                alert($('<div>{{trans('main.select_client')}}</div>').text());
            }


        });



        $(document).on('click', '#pos_purchase_btn', function () {
            var rows =  0 ;
            rows =  ($('#sTable2 tbody tr').length) ;

            var net_after_discount = document.getElementById('net_after_discount2').value;
            var client = document.getElementById('customer_id2').value ;
            if(client > 0) {
                if (rows > 0){
                    if (/*Number(paid) - Number(net_after_discount) == 0*/ true) {
                        openPaymentModal(net_after_discount , 2);

                        localStorage.setItem('openModal', net_after_discount);
                    } else {
                        alert($('<div>{{trans('main.paid_must_equal_net')}}</div>').text());
                    }
                } else {
                    alert($('<div>{{trans('main.no_bill_details')}}</div>').text());
                }
            } else {
                alert($('<div>{{trans('main.select_client')}}</div>').text());
            }


        });



        var now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        now.setMilliseconds(null);
        now.setSeconds(null);

        document.getElementById('bill_date').value = now.toISOString().slice(0, -1);
        ;
        document.getElementById('bill_date2').value = now.toISOString().slice(0, -1);
        ;
        getBillNo();
        $('#warehouse_id').change(function () {
            getBillNo();
        });


        $('input[name=add_item]').change(function () {

        });
        $('#add_item').on('input', function (e) {
           // console.log($('#add_item').val());
            searchProduct($('#add_item').val());

        });


        $(document).on('click', '.cancel-modal', function (event) {
            $('#deleteModal').modal("hide");
            id = 0;
        });

        $(document).on('click', '.deleteBtn', function (event) {
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


        });

        $('#createButton0').click(function () {
            const karat_select = document.getElementById('karat_select0').value;
            $.ajax({
                type: 'get',
                url: 'getKarat/' + karat_select,
                dataType: 'json',

                success: function (response) {

                    AddRowToTable(response , 'tbody0');
                }
            });
        });

        $('#createButton2').click(function () {
            const karat_select = document.getElementById('karat_select2').value;
            $.ajax({
                type: 'get',
                url: 'getKarat/' + karat_select,
                dataType: 'json',

                success: function (response) {

                    AddRowToTable(response , 'tbody2');
                }
            });
        });


    });

    function AddRowToTable(karat , id ) {
        const local = document.getElementById('local').value;
        const table = document.getElementById(id);
        var repeate = document.getElementById( id + '-tr' + karat.id);
        if(!repeate) {
            var row = table.insertRow(-1);
            row.id = id + '-tr' + karat.id;
            row.className = "text-center";
            var cell0 = row.insertCell(0);
            var cell1 = row.insertCell(1);
            var cell2 = row.insertCell(2);
            var cell3 = row.insertCell(3);
            var cell4 = row.insertCell(4);
            var cell5 = row.insertCell(5);
            var cell6 = row.insertCell(6);
            var cell7 = row.insertCell(7);
            var cell8 = row.insertCell(8);
            if(id == 'tbody0'){
                var cell9 = row.insertCell(9);
                var cell10 = row.insertCell(10);
                cell0.hidden = true ;
                cell10.hidden = true ;
            } else {
                cell0.hidden = true ;
                cell8.hidden = true ;
            }


            cell1.className = 'text-center';
            cell2.className = 'text-center';
            cell3.className = 'text-center';
            cell4.className = 'text-center';
            cell5.className = 'text-center';
            cell6.className = 'text-center';
            cell7.className = 'text-center';
            cell8.className = 'text-center';
            if(id == 'tbody0'){
                cell9.className = 'text-center';
            }

            if(id == 'tbody0'){
                cell0.innerHTML = '<input name="karat_id_old[]" value="'+karat.id+'" hidden>';
                cell1.innerHTML = local == 'ar' ?  karat.name_ar : karat.name_en;
                cell2.innerHTML = `<td><input class="form-control iQuantity" type="text" step="any" name="weight_old[]"  /> </td>`;
                cell3.innerHTML = `<td><input class="form-control" type="number" step="any" name="weight21_old[]"  readonly/> </td>`;
                cell4.innerHTML = '<td><input class="form-control iPriceOldd" type="number" step="any" name="gram_price_old[]"  value="'+ karat.price+'" /> </td>';
                cell5.innerHTML = '<td><input class="form-control iMadeValue" type="text" step="any" name="gram_manufacture_old[]"  value="0" /> </td>';
                cell6.innerHTML = '<td><input class="form-control iTax" type="text" step="any" name="gram_tax_old[]"  value="0" /> </td>';
                cell7.innerHTML = `<td><input class="form-control" type="number" step="any" name="net_money_old[]"  readonly value="0" /> </td>`;
                cell8.innerHTML = `<td><input class="form-control" type="number" step="any" name="net_weight_old[]"  readonly/> </td>`;
                cell9.innerHTML = `<td>      <button type="button" class="btn btn-labeled btn-danger deleteBtn0 " value=" '+item.id+' ">
                                            <span class="btn-label" style="margin-right: 10px;"><i class="fa fa-trash"></i></span>{{__('main.delete')}}</button> </td>`;
                cell10.innerHTML = '<input name="factor[]" value="'+karat.transform_factor+'" hidden>';
            } else {
                cell0.innerHTML = '<input name="karat_id_old2[]" value="'+karat.id+'" hidden>';
                cell1.innerHTML = local == 'ar' ?  karat.name_ar : karat.name_en;
                cell2.innerHTML = `<td><input class="form-control iWeight" type="text" step="any" name="weight_old2[]"  /> </td>`;
                cell3.innerHTML = `<td><input class="form-control" type="number" step="any" name="weight21_old2[]"  readonly/> </td>`;
                cell4.innerHTML = '<td><input class="form-control iPriceOld" type="number" step="any" name="gram_price_old[]"  value="0" /> </td>';
                cell5.innerHTML = `<td><input class="form-control" type="number" step="any" name="net_money_old2[]"  readonly value="0" /> </td>`;
                cell6.innerHTML = `<td><input class="form-control" type="number" step="any" name="net_weight_old2[]"  readonly/> </td>`;

                cell7.innerHTML = `<td>      <button type="button" class="btn btn-labeled btn-danger deleteBtn2 " value=" '+item.id+' ">
                                            <span class="btn-label" style="margin-right: 10px;"><i class="fa fa-trash"></i></span>{{__('main.delete')}}</button> </td>`;
                cell8.innerHTML = '<input name="factor[]" value="'+karat.transform_factor+'" hidden>';

            }

        } else {
            alert('sorry , this item is already added to table !');
        }

    }

    function is_numeric(mixed_var) {
        var whitespace = ' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000';
        return (
            (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -1)) &&
            mixed_var !== '' &&
            !isNaN(mixed_var)
        );
    }

    function getBillNo() {
        let bill_number = document.getElementById('bill_number');
        let bill_number2 = document.getElementById('bill_number2');
        let type = document.getElementById('document_type').value;

        $.ajax({
            type: 'get',
            url: '/get_sales_pos_no/' + type,
            dataType: 'json',

            success: function (response) {
                console.log(response);

                if (response) {
                    bill_number.value = response;
                } else {
                    bill_number.value = '';
                }
            }
        });
        $.ajax({
            type: 'get',
            url: '/get_purchase_pos_no',
            dataType: 'json',

            success: function (response) {
                console.log(response);

                if (response) {
                    bill_number2.value = response;
                } else {
                    bill_number2.value = '';
                }
            }
        });


    }


    function searchProduct(code) {
        console.log(code);
        var url = '{{route('getProduct',":id")}}';
        url = url.replace(":id", code);
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',


            success: function (response) {
                 console.log(response);
                document.getElementById('products_suggestions').innerHTML = '';
                if (response) {
                    if (response.length == 1) {
                        //addItemToTable
                        if (response[0].state == 1) {
                            addItemToTable(response[0]);
                        }

                    } else if (response.length > 1) {

                        showSuggestions(response);
                    } else if (response.id) {
                        showSuggestions(response);
                    } else {
                        //showNotFoundAlert
                        openDialog();
                        document.getElementById('add_item').value = '';
                    }
                } else {
                    //showNotFoundAlert
                    openDialog();
                    document.getElementById('add_item').value = '';
                }
            },
            error: function (err){
                console.log( JSON.parse(JSON.stringify(err.responseText)) );
            }
        });
    }

    function showSuggestions(response) {

        console.log(response);
        $data = '';
        $.each(response, function (i, item) {
            if (item.item_type == 1 || item.item_type == 3) {
                if (item.state == 1) {
                    suggestionItems[item.id] = item;
                    if (local == 'ar') {
                        $data += '<li class="select_product" data-item-id="' + item.id + '">' + item.name_ar + '--' + item.code + '</li>';
                    } else {
                        $data +='<li class="select_product" data-item-id="'+item.id+'">'+item.name_ar + '--' + item.code  +'</li>';
                    }
                }

            }
        });
        document.getElementById('products_suggestions').innerHTML = $data;
    }


    function openPaymentModal(id , type){
        console.log('money modal');
        let url = "{{ route('pos_payment_show', [':id' , ':type']) }}";
        url = url.replace(':id', id);
        url = url.replace(':type', type);
        $.get( url, function( data ) {
            if(type == 1){
                $( ".show_modal1" ).html( data );
            } else {
                $( ".show_modal2" ).html( data );
            }

            $('#paymentsModal').modal({backdrop: 'static', keyboard: false} ,'show');
        });
    }
    function openDialog() {
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
        });
    }

    function addItemToTable(item) {
        suggestionItems = {};
        $('#products_suggestions').empty();
        suggestionItems = {};
        if (count == 1) {
            sItems = {};
        }

        if (sItems[item.id]) {
            alert('This Item Entry has Already been made');
            return;
        } else {
            var price = item.price;
            var taxType = item.tax_method;
            var taxRate = item.tax_rate == 1 ? 0 : 15;
            var itemTax = 0;
            var priceWithoutTax = 0;
            var priceWithTax = 0;
            var itemQnt = item.weight;

            if (taxType == 1) {
                //included
                priceWithTax = price;
                priceWithoutTax = (price / (1 + (taxRate / 100)));
                itemTax = priceWithTax - priceWithoutTax;
            } else {
                //excluded
                itemTax = price * (taxRate / 100);
                priceWithoutTax = price;
                priceWithTax = price + itemTax;
            }

            sItems[item.id] = item;
            console.log(sItems);

        }
        count++;
        loadItems();

        document.getElementById('add_item').value = '';
        $('#add_item').focus();
    }





    var old_row_qty = 0;
    var old_row_price = 0;
    var old_row_w_price = 0;


    $(document).on('change','.iQuantity',function () {
        var row = $(this).closest('tr');
        if(!is_numeric($(this).val()) || parseFloat($(this).val()) < 0){
            $(this).val(0);
            alert('wrong value');
            return;
        }
        const factor = row[0].cells[10].firstChild.value;
        const weight21 = $(this).val() * factor ;
        const tax = row[0].cells[6].firstChild.value;
        const price = row[0].cells[4].firstChild.value;
        const made = row[0].cells[5].firstChild.value;

        const total = Number(this.value) * (Number(price) + Number(made) + Number(tax));
        row[0].cells[7].firstChild.value = total.toFixed(2) ;

        row[0].cells[3].firstChild.value = weight21.toFixed('2') ;
        row[0].cells[8].firstChild.value = $(this).val()  ;
        calcTotals();

    });
    $(document).on('keyup','.iQuantity',function () {
        var row = $(this).closest('tr');
        if(!is_numeric($(this).val()) || parseFloat($(this).val()) < 0){
            $(this).val(0);
            alert('wrong value');
            return;
        }
        const factor = row[0].cells[10].firstChild.value;
        const weight21 = $(this).val() * factor ;
        const tax = row[0].cells[6].firstChild.value;
        const price = row[0].cells[4].firstChild.value;
        const made = row[0].cells[5].firstChild.value;

        const total = Number(this.value) * (Number(price) + Number(made) + Number(tax));
        row[0].cells[7].firstChild.value = total.toFixed(2) ;

        row[0].cells[3].firstChild.value = weight21.toFixed(2) ;
        row[0].cells[8].firstChild.value = $(this).val()  ;
        calcTotals();

    });


    $(document).on('change','.iMadeValue',function () {
        var row = $(this).closest('tr');
        if(!is_numeric($(this).val()) || parseFloat($(this).val()) < 0){
            $(this).val(0);
            alert('wrong value');
            return;
        }
        const weight = row[0].cells[2].firstChild.value;
        const tax = row[0].cells[6].firstChild.value;
        const price = row[0].cells[4].firstChild.value;
        const total = Number(weight) * (Number(tax) + Number(price) + Number(this.value))

        row[0].cells[7].firstChild.value = total.toFixed(2) ;

        calcTotals();

    });
    $(document).on('keyup','.iMadeValue',function () {
        var row = $(this).closest('tr');
        if(!is_numeric($(this).val()) || parseFloat($(this).val()) < 0){
            $(this).val(0);
            alert('wrong value');
            return;
        }
        const weight = row[0].cells[2].firstChild.value;
        const tax = row[0].cells[6].firstChild.value;
        const price = row[0].cells[4].firstChild.value;
        const total = Number(weight) * (Number(tax) + Number(price) + Number(this.value))

        row[0].cells[7].firstChild.value = total.toFixed(2) ;

        calcTotals();

    });


    $(document).on('change','.iTax',function () {
        var row = $(this).closest('tr');
        if(!is_numeric($(this).val()) || parseFloat($(this).val()) < 0){
            $(this).val(0);
            alert('wrong value');
            return;
        }
        const weight = row[0].cells[2].firstChild.value;
        const made = row[0].cells[5].firstChild.value;
        const price = row[0].cells[4].firstChild.value;
        const total = Number(weight) * (Number(made) + Number(price) + Number(this.value))

        row[0].cells[7].firstChild.value = total.toFixed(2) ;

        calcTotals();

    });
    $(document).on('keyup','.iTax',function () {
        var row = $(this).closest('tr');
        if(!is_numeric($(this).val()) || parseFloat($(this).val()) < 0){
            $(this).val(0);
            alert('wrong value');
            return;
        }
        const weight = row[0].cells[2].firstChild.value;
        const made = row[0].cells[5].firstChild.value;
        const price = row[0].cells[4].firstChild.value;
        const total = Number(weight) * (Number(made) + Number(price) + Number(this.value))

        row[0].cells[7].firstChild.value = total.toFixed(2) ;

        calcTotals();

    });


    $(document).on('keyup', '.iWeight', function () {
        var row = $(this).closest('tr');
        if(!is_numeric($(this).val()) || parseFloat($(this).val()) < 0){
            $(this).val(0);
            alert('wrong value');
            return;
        }
        const factor = row[0].cells[8].firstChild.value;
        const weight21 = $(this).val() * factor ;

        const price = row[0].cells[4].firstChild.value;


        const total = Number(this.value) * (Number(price) );
        row[0].cells[5].firstChild.value = total.toFixed(2) ;

        row[0].cells[3].firstChild.value = weight21.toFixed(2) ;
        row[0].cells[6].firstChild.value = $(this).val()  ;
        calcTotals2();


    });
    $(document).on('changer', '.iWeight', function () {
        var row = $(this).closest('tr');
        if(!is_numeric($(this).val()) || parseFloat($(this).val()) < 0){
            $(this).val(0);
            alert('wrong value');
            return;
        }
        const factor = row[0].cells[8].firstChild.value;
        const weight21 = $(this).val() * factor ;

        const price = row[0].cells[4].firstChild.value;


        const total = Number(this.value) * (Number(price) );
        row[0].cells[5].firstChild.value = total.toFixed(2) ;

        row[0].cells[3].firstChild.value = weight21.toFixed(2) ;
        row[0].cells[6].firstChild.value = $(this).val()  ;
        calcTotals2();


    });

    $(document).on('change', '.iMoney', function () {
        var row = $(this).closest('tr');
        if (!is_numeric($(this).val()) || parseFloat($(this).val()) < 0) {
            $(this).val(0);
            alert('wrong value');
            return;
        }

        row[0].cells[5].firstChild.value = $(this).val();


    });
    $(document).on('keyup', '.iMoney', function () {
        var row = $(this).closest('tr');
        if (!is_numeric($(this).val()) || parseFloat($(this).val()) < 0) {
            $(this).val(0);
            alert('wrong value');
            return;
        }

        row[0].cells[5].firstChild.value = $(this).val();


    });
    $(document).on('click', '.deleteBtn2', function (event) {
        var row = $(this).parent().parent().index();
        console.log(row);
        var table = document.getElementById('tbody2');
        table.deleteRow(row);
    });


    $(document).on('click', '.deleteBtn0', function (event) {
        var row = $(this).parent().parent().index();
        console.log(row);
        var table = document.getElementById('tbody0');
        table.deleteRow(row);
        calcTotals();
    });


    $(document).on('click', '.deleteBtn2', function (event) {
        var row = $(this).parent().parent().index();
        console.log(row);
        var table = document.getElementById('tbody2');
        table.deleteRow(row);
        calcTotals2();
    });


    $(document).on('change', '.iPriceOld', function () {
            if (!is_numeric($(this).val()) || parseFloat($(this).val()) < 0) {
                $(this).val(0);
                alert('wrong value');
                return;
            }
          var row = $(this).closest('tr');
           var weight = row[0].cells[2].firstChild.value;
           var total = weight *  $(this).val();
            row[0].cells[5].firstChild.value = total ;
            calcTotals2();

        });
    $(document).on('keyup', '.iPriceOld', function () {
        if (!is_numeric($(this).val()) || parseFloat($(this).val()) < 0) {
            $(this).val(0);
            alert('wrong value');
            return;
        }
        var row = $(this).closest('tr');
        var weight = row[0].cells[2].firstChild.value;
        var total = weight *  $(this).val();
        row[0].cells[5].firstChild.value = total ;
        calcTotals2();


    });



    $(document).on('change', '.iPriceOldd', function () {
        if (!is_numeric($(this).val()) || parseFloat($(this).val()) < 0) {
            $(this).val(0);
            alert('wrong value');
            return;
        }
        var row = $(this).closest('tr');
        const weight = row[0].cells[2].firstChild.value;
        const tax = row[0].cells[6].firstChild.value;
        const madeValue = row[0].cells[5].firstChild.value;
        const total = Number(weight) * (Number(tax) + Number(madeValue) + Number(this.value))

        row[0].cells[7].firstChild.value = total.toFixed(2) ;

        calcTotals();

    });
    $(document).on('keyup', '.iPriceOldd', function () {
        if (!is_numeric($(this).val()) || parseFloat($(this).val()) < 0) {
            $(this).val(0);
            alert('wrong value');
            return;
        }
        var row = $(this).closest('tr');
        const weight = row[0].cells[2].firstChild.value;
        const tax = row[0].cells[6].firstChild.value;
        const madeValue = row[0].cells[5].firstChild.value;
        const total = Number(weight) * (Number(tax) + Number(madeValue) + Number(this.value))

        row[0].cells[7].firstChild.value = total.toFixed(2) ;

        calcTotals();


    });




    $(document)
        .on('focus', '.iPriceWTax', function () {
            old_row_w_price = $(this).val();
        })
        .on('change', '.iPriceWTax', function () {
            var row = $(this).closest('tr');
            if (!is_numeric($(this).val()) || parseFloat($(this).val()) < 0) {
                $(this).val(old_row_w_price);
                alert('wrong value');
                return;
            }

            var newQty = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');

            var item_tax = sItems[item_id].item_tax;
            var priceWithoutTax = newQty;
            if (item_tax > 0) {
                priceWithoutTax = newQty / 1.15;
                item_tax = priceWithoutTax * 0.15;
            }
            sItems[item_id].price_withoute_tax = priceWithoutTax;
            sItems[item_id].price_with_tax = newQty;
            sItems[item_id].item_tax = item_tax;
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


    function loadItems() {

        var items_count_val = 0 ;
        var total_actual_weight_val = 0 ;
        var total_weight21_val = 0 ;
        var first_total_val = 0 ;
        var made_Value_t_val = 0 ;
        var tax_total_val =0 ;
        var net_sales_val = 0 ;
        var discount_val = 0 ;
        var net_after_discount_val =0 ;
        var paid_val = 0 ;

        $('#sTable tbody').empty();
        $.each(sItems, function (i, item) {
            console.log(item);

            var newTr = $('<tr data-item-id="' + item.id + '">');
            var tr_html = local == 'ar' ? '<td class="text-center"><input type="hidden" name="item_id[]" value="' + item.id + '"> <span>' + item.name_ar + '---' + (item.code) + '</span> </td>'
                : '<td class="text-center"><input type="hidden" name="item_id[]" value="' + item.id + '"> <span>' + item.name_ar + '---' + (item.code) + '</span> </td>';
            tr_html += local == 'ar' ? '<td class="text-center"><input type="hidden" name="karat_id[]" value="' + item.karat_id + '"> <span>' + item.karat.name_ar + '</span> </td>'
                : '<td class="text-center"><input type="hidden" name="karat_id[]" value="' + item.karat_id + '"> <span>' + item.karat.name_en + '</span> </td>';
            tr_html += '<td><input type="text" class="form-control iPriceWTax" name="weight[]" value="' + item.weight + '" readonly></td>';
            tr_html += '<td><input type="text" class="form-control iQuantity" name="gram_price[]" value="' + item.price.toFixed(2) + '" readonly></td>';
            tr_html += '<td><input type="text" readonly="readonly" class="form-control" name="gram_manufacture[]" value="' + (item.made_Value) + '"></td>';
            tr_html += '<td><input type="text" readonly="readonly" class="form-control" name="gram_tax[]" value="' + (item.karat.stamp_value) + '"></td>';
            tr_html += '<td><input type="text" readonly="readonly" class="form-control" name="net_money[]" value="' + (item.weight * (Number(item.made_Value) + Number(item.karat.stamp_value) + Number(item.price))).toFixed(2) + '"></td>';
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
            paid_val = 0 ;




        });
        document.getElementById('items_count').value =items_count_val.toFixed(2) ;
        document.getElementById('total_actual_weight').value = total_actual_weight_val.toFixed(2);
        document.getElementById('total_weight21').value = total_weight21_val.toFixed(2);
        document.getElementById('first_total').value = first_total_val.toFixed(2);
        document.getElementById('made_Value_t').value = made_Value_t_val.toFixed(2);
        document.getElementById('tax_total').value = tax_total_val.toFixed(2);
        document.getElementById('net_sales').value = net_sales_val.toFixed(2);
        document.getElementById('discount').value = discount_val;

        document.getElementById('paid').value = paid_val.toFixed(2);

        var taxPer = document.getElementById('taxPer').value ;
        console.log(taxPer);
        var tax = net_sales_val * (taxPer / 100);
        document.getElementById('tax').value = tax.toFixed(2);
        document.getElementById('net_after_discount').value = (Number(net_sales_val) - Number (discount_val) + Number(tax)).toFixed(2);

        $('#products_suggestions').empty();
    }

    function calcTotals2(){
        console.log('calcTotals2');
        var weight = 0 ;
        var weight21 = 0;
        var net = 0 ;
        var discount_val = 0 ;

        $( "#sTable2 tbody tr ").each( function( index ) {
            var row = $(this).closest('tr');

            weight += Number(row[0].cells[2].firstChild.value);
            weight21 += Number(row[0].cells[3].firstChild.value);
            net += Number(row[0].cells[5].firstChild.value);
            discount_val = document.getElementById('discount2').value;

        });
        document.getElementById('total_actual_weight_purchase').value = weight.toFixed(2);
        document.getElementById('total_weight21_purchase').value = weight21.toFixed(2);
        document.getElementById('net_purchase').value = net.toFixed(2);

        document.getElementById('discount2').value = discount_val;


        var taxPer = document.getElementById('taxPer').value ;
        // var tax = net * (taxPer /100);
        // document.getElementById('tax2').value = tax ;
        tax = 0 ;
        document.getElementById('tax2').value = tax ;
        var nett = Number(net) - Number(discount_val) + Number(tax) ;
        console.log(nett);
        document.getElementById('net_after_discount2').value = (Number(net) - Number(discount_val) + Number(tax)).toFixed(2);

    }
    function calcTotals(){
        var weight = 0 ;
        var weight21 = 0;
        var made = 0 ;
        var tax = 0 ;
        var first_total_val = 0 ;
        var net = 0 ;
        var discount_val = 0 ;
        $( "#sTable0 tbody tr ").each( function( index ) {
            var row = $(this).closest('tr');

            weight += Number(row[0].cells[2].firstChild.value);
            weight21 += Number(row[0].cells[3].firstChild.value);
            made += Number(row[0].cells[5].firstChild.value);
            tax += Number(row[0].cells[6].firstChild.value);
            first_total_val += Number(row[0].cells[2].firstChild.value) * Number(row[0].cells[4].firstChild.value);
            net += Number(row[0].cells[7].firstChild.value);
            discount_val = document.getElementById('discount').value;

        });
        document.getElementById('total_actual_weight').value = weight.toFixed(2) ;
        document.getElementById('total_weight21').value = weight21.toFixed(2) ;

        document.getElementById('first_total').value = first_total_val.toFixed(2);
        document.getElementById('made_Value_t').value = ( Number(made) * Number(weight)).toFixed(2);
        document.getElementById('tax_total').value = ( Number(tax) * Number(weight)).toFixed(2);
        document.getElementById('net_sales').value = net.toFixed(2);
        document.getElementById('discount').value = discount_val;

        document.getElementById('paid').value = 0;

        var taxPer = document.getElementById('taxPer').value ;
        var tax = net * (taxPer /100);
        document.getElementById('tax').value = tax ;



        document.getElementById('net_after_discount').value = (Number(net) - Number(discount_val) + Number(tax)).toFixed(2);
    }
    function A7A(){
        console.log('A7A');
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



