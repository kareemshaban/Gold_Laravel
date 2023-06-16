
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <link rel="stylesheet" href=" {{asset('assets/css/bootstrap.min.css')}}">

    <link rel="stylesheet" href="../../assets/css/printA5.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        *{
            padding: 0 !important;
        }
        .row{
            width: 95% !important;
            margin: auto !important;
        }
        th{
            font-size: 11px;
            font-weight: bold;
        }
        td{
            font-size: 10px;
            font-weight: normal;
        }
    </style>

</head>
<body>
<header style="width: 95% ; display: block; margin: auto ; height: 3cm;" >

</header>

<div class="container"  style="width: 100%  !important; display: block; margin: 0 auto ; padding: 0">
    <div class="row" style="width: 100%;">
        <div class="col-5">
            <table  style="width: 100%;  direction: rtl" class="table-bordered">
                <thead>
                <tr>
                    <th class="text-center" style="padding: 5px">تاريخ الفاتورة</th>
                    <th class="text-center" style="padding: 5px">{{\Carbon\Carbon::parse($bill -> date) -> format('d- m -Y') }}</th>
                </tr>
                <tr>
                    <th class="text-center" style="padding: 5px">رقم الفاتورة</th>
                    <th class="text-center"  style="padding: 5px">{{$bill -> bill_number}}</th>


                </tr>
                </thead>
            </table>
        </div>
        <div class="col-2">
            <img id='barcode'
                 src="https://api.qrserver.com/v1/create-qr-code/?data='https://super.seasonsge.net/api/rawaie/?date={{\Carbon\Carbon::parse($bill -> date) -> format('d-m-Y')}}&money={{$bill -> net_money}}&gold={{$bill -> total21_gold}}'"
                 alt=""
                 title="HELLO"
                 width="50"
                 height="50"
                 style="width: 50px; height: auto; display: block; margin: 5px auto;">
        </div>
        <div class="col-5">
            <table style="width: 100% ; direction: rtl" class="table-bordered">
                <thead>
                <tr>
                    <th class="text-center" style="padding: 5px">اسم العميل </th>
                    <th class="text-center"  style="padding: 5px">{{$bill -> vendor_name != 'عميل نقدي افتراضي'  ?$bill -> vendor_name : $bill  -> bill_client_name }}</th>
                </tr>
                <tr>
                    <th class="text-center" style="padding: 5px"> نوع الفاتورة</th>
                    <th class="text-center" style="padding: 5px">
                        <?php $billType = '';  ?>
                        @foreach($payments as $payment)

                            @if($payment -> payment_method == 0)
                                @if($billType != '')
                                    <?php $billType .= '--' . 'فاتورة نقدية'  ?>
                                @else
                                    <?php $billType .= '' . 'فاتورة نقدية'  ?>
                                @endif

                            @else
                                @if($billType != '')
                                    <?php $billType .= '--' . 'فاتورة شبكة'  ?>
                                @else
                                    <?php $billType .= '' . 'فاتورة شبكة'  ?>
                                @endif

                            @endif
                        @endforeach
                        {{$billType}}
                    </th>
                </tr>
                </thead>
            </table>
        </div>

    </div>
    <h6 class="text-center" style="display: block; margin:5px auto ; font-weight: bold; width: 90%">فاتورة ضريبية مبسطة</h6>


    <div class="row" style="width: 100%; margin-top: 10px">
        <div class="col-12">
            <table style="width: 100% ; direction: rtl" class="table-bordered">
                <thead>
                <tr>
                    <th class="text-center" style="width: 10%">م</th>
                    <th class="text-center " style="width: 20%">العيار</th>
                    <th class="text-center " style="width: 20%">سعر الجرام</th>
                    <th class="text-center " style="width: 20%">الوزن</th>
                    <th class="text-center " style="width: 20%">وزن ما يعادل 21</th>
                    <th class="text-center " style="width: 20%">اجمالي النقدية</th>
                </tr>
                </thead>
                <tbody id="tbody">
                <?php $sum_total = 0 ?>
                <?php $sum_tax = 0 ?>
                <?php $sum_weight = 0 ?>
                @foreach($details as $detail)
                    <tr>
                        <td class="text-center">{{$loop -> index + 1}}</td>
                        <td class="text-center"> {{Config::get('app.locale') == 'ar' ? $detail -> karat_ar : $detail -> karat_en}} </td>
                        <td class="text-center"> {{$detail -> gram_price}} </td>
                        <td class="text-center"> {{$detail -> weight}} </td>
                        <td class="text-center"> {{$detail -> weight21}} </td>
                        <td class="text-center"> {{$detail -> net_money}} </td>
                    </tr>
                @endforeach

                <tr>
                    <td class="text-center"  colspan="2">{{$bill ->total_money}}</td>
                    <td class="text-center" colspan="2">الاجمالي قبل الخصم
                        <br>(Total Before Discount)</td>

                    <td class="text-center" colspan="2" >   ملاحظات الفاتورة
                    </td>
                </tr>
                <tr>
                    <td class="text-center"  colspan="2">{{$bill -> discount}}</td>
                    <td class="text-center" colspan="2">الخصم
                        <br>(Discount Value)
                    </td>

                    <td class="text-center" colspan="2" rowspan="3" >  </td>
                </tr>
                <tr>
                    <td class="text-center"  colspan="2">{{$bill -> net_money}}</td>
                    <td class="text-center"  colspan="2">الاجمالي بعد الخصم
                        <br>(Total After Discount)
                    </td>


                </tr>
                <tr>
                    <td class="text-center"  colspan="6">{{$amar}}</td>

                </tr>





                </tbody>
            </table>


        </div>

    </div>
    <div class="row" style="direction:rtl">
        <div class="col-6 text-center">
            <span> اسم البائع</span> <br>
            <span>{{auth() -> user() -> name}}</span>
        </div>
        <div class="col-6 text-center">
            <span>  مدير الفرع</span> <br>
            <span>........</span>
        </div>
    </div>



</div>



<script>


    $(document).ready(function () {
        printPage();

    });

    function printPage(){


        window.print();
    }

</script>
</body>
</html>


