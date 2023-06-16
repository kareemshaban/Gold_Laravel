
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
                <span style="text-align: left;">Jawaher Lulia Industry Company

            <br> C.R :   1010157466
           <br>  Vat No :   311473562500003
          <br>  Tel :   0500410200

       </span>
            </div>
            <div class="col-sm c">
                <img src="../../assets/img/logo_lol.png" class="logo">
                <label style="text-align: center; font-weight: bold">فاتورة ضريبية</label>
            </div>
            <div class="col-sm c">
           <span style="text-align: right;">شركة جواهر لوليا
           للذهب والمجوهرات

            <br>  س.ت :   1010157466
           <br>  ر.ض :   311473562500003
          <br>  تليفون :   0500410200
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
                    <th class="text-center " style="width: 20%">العيار</th>
                    <th class="text-center " style="width: 20%">الوزن</th>
                    <th class="text-center " style="width: 20%">وزن ما يعادل 21</th>
                    <th class="text-center " style="width: 20%">اجمالي الأجور</th>
                </tr>
                </thead>
                <tbody id="tbody">
                <?php $sum_total = 0 ?>
                <?php $sum_21 = 0 ?>
                <?php $sum_weight = 0 ?>
                @foreach($details as $detail)
                    <tr>
                        <td class="text-center">{{$loop -> index + 1}}</td>
                        <td class="text-center"> {{Config::get('app.locale') == 'ar' ? $detail -> karat_ar : $detail -> karat_en}} </td>
                        <td class="text-center"> {{$detail -> weight}} </td>
                        <td class="text-center"> {{$detail -> weight21}} </td>
                        <td class="text-center"> {{$detail -> net_money}} </td>
                    </tr>
                    <?php $sum_weight += $detail -> weight ?>
                    <?php $sum_total += $detail -> net_money ?>
                    <?php $sum_21 += $detail -> weight21 ?>
                @endforeach
                <tr>
                    <td colspan="3" rowspan="2" style="text-align: right; font-size: 15px !important;">
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
                    <td class="text-center"> إجمالي الأجر</td>
                    <td class="text-center">{{round($sum_total , 2)}} </td>
                </tr>
                <tr>

                    <td class="text-center"> إجمالي الذهب عيار 21</td>
                    <td class="text-center"> {{$sum_21 }} </td>
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
