<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
    <title>Gold MS</title>

    <!-- Custom fonts for this template-->
    <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="../../assets/css/sb-admin-2.css" rel="stylesheet">

    <style>

        .header{
            border: solid 1px black !important;
        }
        .header h3{
            width: fit-content !important;
            display: block !important;
            margin: 5px auto !important;
        }
        .header_label{
            margin-right: 15px !important;
            font-size: 18px !important;
            font-weight: bold !important;
        }
        .header_label span {
            font-weight: normal !important;
        }
    </style>




</head>

<body  class="A5">

<section class="sheet padding-10mm">

        <div class="row header" >
            <div class="row" style="width: 100%;">
                <h3 class="text-center">{{__('main.pos')}}</h3>
            </div>

            <div class="row">
                <div class="col-6 text-right">

                    <label class="header_label">رقم الفاتورة:
                        <span>0001214</span>
                    </label>

                </div>

                <div class="col-6 text-left">

                    <label class="header_label">رقم الفاتورة:
                        <span>0001214</span>
                    </label>

                </div>

            </div>


        </div>



</section>


<script type="text/javascript">

    $(document).ready(function () {
        printPage();

    });


    function printPage() {
        var css = '@page { size: A5 landscape; }',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet) {
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



<!-- Page level custom scripts -->
<script src="../../assets/js/demo/datatables-demo.js"></script>

</body>

</html>
