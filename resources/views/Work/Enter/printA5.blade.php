
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <link rel="stylesheet" href=" {{asset('assets/css/bootstrap.min.css')}}">

    <link rel="stylesheet" href="../../assets/css/printA5.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
<body>
<header style="width: 100% ; height: 3cm; border: solid 1px black">

</header>

<div class="container" >
    <h3 class="text-center" style="display: block; margin: 10px auto ; font-weight: bold; width: 100%">فاتورة شراء ذهب مشغول </h3>

    <div class="row" style="width: 100%;">
        <div class="col-6">
            <table  style="width: 100%; direction: rtl" class="table-bordered">
                <thead>
                <tr>
                    <th class="text-center" style="padding: 5px">تاريخ الفاتورة</th>
                    <th class="text-center" style="padding: 5px">{{\Carbon\Carbon::parse($bill -> date) -> format('d- m -Y') }}</th>
                </tr>
                <tr>
                    <th class="text-center" style="padding: 5px">المورد </th>
                    <th class="text-center"  style="padding: 5px">{{$bill -> vendor_name}}</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="col-6">
            <table style="width: 100% ; direction: rtl" class="table-bordered">
                <thead>
                <tr>
                    <th class="text-center" style="padding: 5px">رقم الفاتورة</th>
                    <th class="text-center"  style="padding: 5px">{{$bill -> bill_number}}</th>
                </tr>
                <tr>
                    <th class="text-center" style="padding: 5px">نوع الفاتورة</th>
                    <th class="text-center"  style="padding: 5px">فاتورة نقدية</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="row" style="width: 90%; display: block; margin: auto ; height: 2px; background: black; margin: 10px"></div>
    <div class="row" style="width: 100%; margin-top: 10px">
        <div class="col-12">
            <table  class="table-bordered" style="width: 100% ; direction: rtl">
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
                      <td class="text-center" colspan="2"> اجمالي الفاتورة</td>
                      <td class="text-center">{{$sum_weight}}</td>
                      <td class="text-center">{{$sum_21}}</td>
                      <td class="text-center">{{$sum_total}}</td>
                  </tr>
                <tr>
                    <td class="text-center" colspan="4"> اجمالي الخصم</td>
                    <td class="text-center">{{$bill -> discount}}</td>
                </tr>
                <tr>
                    <td class="text-center" colspan="4"> اجمالي الضريبة</td>
                    <td class="text-center">{{$bill -> tax}}</td>
                </tr>
                <tr>
                    <td class="text-center" colspan="2">  صافي النقدية</td>
                    <td class="text-center" colspan="2">  فقط لاغير  </td>
                    <td class="text-center">{{$bill -> net_money}}</td>
                </tr>






                </tbody>
            </table>
        </div>

    </div>

</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>


    $(document).ready(function () {
        console.log('ready');
        print();

    });



</script>
</body>
</html>
