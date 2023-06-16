
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/print.css">
    <script src="../../assets/js/print.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        .c{

            display: flex;
            justify-content: center;
            margin: 0;
            flex-direction: column;
            padding: 6px;
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <div class="col-sm c">
                <span style="text-align: left;">{{$company ? $company -> name_en : ''}}

            <br> C.R :   {{$company ? $company -> registrationNumber : ''}}
           <br>  Vat No :   {{$company ? $company -> taxNumber : ''}}
          <br>  Tel :   {{ $company ? $company -> phone : ''}}

       </span>
            </div>
            <div class="col-sm c">
                <label style="text-align: center; font-weight: bold">فاتورة ضريبية</label>
            </div>
            <div class="col-sm c">
           <span style="text-align: right;">{{$company ? $company -> name_en : ''}}

            <br>  س.ت : {{$company ? $company -> taxNumber : ''}}
           <br>  ر.ض :  {{$company ? $company -> registrationNumber : ''}}
          <br>  تليفون :   {{$company ? $company -> phone : ''}}
           </span>
            </div>
        </div>
    </div>

</header>
<article>
    <div class="container">
        <div class="row" style="margin-bottom: 30px">
            <div class="col-sm ">
                <table>
                    <thead>
                    <tr>
                        <th class="text-center">التاريخ</th>
                        <th class="text-center" >{{\Carbon\Carbon::parse($bill -> date) -> format('d - m - Y')}}</th>
                    </tr>
                    <tr>
                        <th class="text-center">رقم الفاتورة</th>
                        <th class="text-center">{{$bill -> bill_number}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="col-sm ">
                <table>
                    <thead>
                    <tr>
                        <th class="text-center">العميل</th>
                        <th class="text-center">{{$bill -> vendor_name}}</th>
                    </tr>
                    <tr>
                        <th class="text-center">الرقم الضريبي</th>
                        <th class="text-center">{{$bill -> vendor_vat_no}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="row" style="border: solid 1px black">
            <table style="width: 100%">
                <thead>
                <tr>
                    <th class="text-center" style="width: 10%">م</th>
                    <th class="text-center " style="width: 20%">كود الصنف</th>
                    <th class="text-center " style="width: 20%">بيان الصنف</th>
                    <th class="text-center " style="width: 20%">العيار</th>
                    <th class="text-center " style="width: 20%">الوزن</th>
                    <th class="text-center " style="width: 20%">الأجور</th>
                    <th class="text-center " style="width: 20%">الضريبة</th>
                    <th class="text-center " style="width: 20%">سعر الجرام</th>
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
                         <td class="text-center"> {{$detail -> item_code}} </td>
                        <td class="text-center"> {{Config::get('app.locale') == 'ar' ? $detail -> item_ar : $detail -> item_en}} </td>
                        <td class="text-center"> {{Config::get('app.locale') == 'ar' ? $detail -> karat_ar : $detail -> karat_en}} </td>
                        <td class="text-center"> {{$detail -> weight}} </td>
                        <td class="text-center" > {{$detail -> weight * $detail -> gram_manufacture}} </td>
                        <td class="text-center"> {{$detail -> weight * $detail -> gram_tax}} </td>
                        <td class="text-center" > {{$detail -> gram_price}} </td>
                        <td class="text-center"> {{$detail -> net_money}} </td>
                    </tr>
                    <?php $sum_weight += $detail -> weight ?>
                    <?php $sum_total += $detail -> weight * $detail -> gram_price ?>
                    <?php $sum_tax += $detail -> weight * $detail -> gram_tax ?>
                @endforeach
                <tr>
                    <td colspan="7" rowspan="4" style="text-align: right; font-size: 15px !important;">
                        {{$sum_weight}}   إجمالي الوزن القائم من الذهب
                        <br>
                        {{$bill -> total21_gold}}   إجمالي الذهب منسوب إلي عبار 21
                        <br>
                        <table style="width: 50%">
                            <thead>
                            @foreach($karats as $karat)
                            <tr>

                                    <?php $sum_val = 0 ?>
                                    @foreach( $grouped_ar  as $group => $items)
                                            <?php $sum_val = 0 ?>
                                        @if($group == $karat -> name_ar)
                                                    @foreach($items as $item)
                                                    <?php $sum_val += $item -> weight ?>
                                                    @endforeach

                                          @else
                                            @break
                                                @endif
                                    @endforeach
                                    <th class="text-center">{{$karat -> name_ar}}</th>
                                    <th class="text-center">{{$sum_val}}</th>

                            </tr>
                            @endforeach
                            </thead>
                        </table>
                        <br>
                        <span>أقر انا السيد .................. بأني استلمت بضاعة من شركة جواهر لوليا للصناعة مقابل وزنها صافي عيار 21 (.......) ومقابلها اجور(........) علي سبيل الأمانة ولا تخلو مسؤليتي من هذه الامانة الا بإبراز سند قبض </span>
                    </td>
                    <td class="text-center"> الاجمالي بدون ضريبة</td>
                    <td class="text-center">{{round($sum_total , 2)}} </td>
                </tr>
                <tr>

                    <td class="text-center"> الخصم</td>
                    <td class="text-center"> {{$bill -> discount}} </td>
                </tr>
                <tr>

                    <td class="text-center"> الضريبة</td>
                    <td class="text-center"> {{$sum_tax}}</td>
                </tr>
                <tr>

                    <td class="text-center"> الصافي</td>
                    <td class="text-center"> {{$bill -> net_money}}</td>
                </tr>


                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-sm" style="text-align: right">
                <label style="text-align: left; font-size: 16px; font-weight: bold">البائع</label>
                <br>
                <span>الإسم </span>
                <br>
                <span>التوقيع </span>
            </div>
            <div class="col-sm" style="text-align: right">
                <label style="text-align: right; font-size: 16px; font-weight: bold">المستلم</label>
                <br>
                <span>الإسم </span>
                <br>
                <span>التوقيع </span>
            </div>
        </div>
        <img id='barcode'
             src="https://api.qrserver.com/v1/create-qr-code/?data=http://127.0.0.1:8000/ar/workQrcode/{{$bill -> id}}&amp;size=100x100"
             alt=""
             title="HELLO"
             width="100"
             height="100"
             style="width: 80px; height: auto; display: block; margin: 5px auto;">
    </div>

</article>

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
