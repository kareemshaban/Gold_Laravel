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

    <style>
        tr , th {
            border: solid 1px gray;
        }
    </style>
</head>

<body id="page-top" @if(Config::get('app.locale') == 'ar') style="direction: rtl" @endif>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <i class="fas fa-smile text-success" style="font-size: 80px ; text-align: center ; display: block; margin: 15px auto  "></i>
            <h2 class="text-center text-success" style="font-weight: bold">هذا الكيو أر كود متوافق مع هيئة الزكاة والضرائب والجمارك</h2>
        </div>
    </div>
    <div class="row" style="border: solid 1px black">
        <h3 class="text-right" style="color: gray ; margin: 10px;"> تفاصيل الفاتورة</h3>
        <table style="width: 100%;" class="border">
            <thead>
            <tr>
                <th class="text-center"> إسم البائع </th>
                <th class="text-center">شركة روائع الماسية
                    للذهب والمجوهرات </th>
            </tr>
            <tr>
                <th class="text-center"> الرقم الضريبي </th>
                <th class="text-center"> 311474274800003 </th>
            </tr>
            <tr>
                <th class="text-center"> تاريخ االفاتورة </th>
                <th class="text-center"> {{$bill -> date}}</th>
            </tr>
            <tr>
                <th class="text-center"> إجمالي نقدية الفاتورة </th>
                <th class="text-center"> {{$bill -> net_money}}</th>
            </tr>
            <tr>
                <th class="text-center"> إجمالي ذهب عيار 21  </th>
                <th class="text-center"> {{$bill -> total21_gold}}</th>
            </tr>
            </thead>
        </table>

    </div>
</div>




<script>


    $(document).ready(function () {
        //  printPage();

    });



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
