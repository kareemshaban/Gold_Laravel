<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Barcode Printer Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/barcodes/JsBarcode.code128.min.js"></script>
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
    <script src="html2canvas.min.js"></script>
</head>

<body>
<h1>Barcode Printer Demo</h1>

<div id="printable">
    <img class="barcode" data-value="A1234-567"></img>
</div>

<button type="button" onclick="printBarcode()">
    Print barcode
</button>

<script>
    JsBarcode(".barcode", "A1245-789", {
        width: 1,
        height: 30,
        textAlign: "left",
        fontOptions: "bold",
        margin: 10,
        fontSize: 20
    })

    let barcodeSVGs = document.getElementsByClassName("barcode")

    // for (let el of barcodeSVGs) {
    //   el.setAttribute("width", "100%")
    //   el.setAttribute("height", "100%")
    // }

    function printBarcode() {
        // printJS('printable', 'html')

        let printFrame = document.createElement("iframe")
        let printableElement = document.getElementById("printable")
        //
        // // printframe.setattribute("style", "visibility: hidden; height: 0; width: 0; position: absolute;")
        printFrame.setAttribute("id", "printjs")
        printFrame.srcdoc = "<html><head><title>document</title></head><body style='margin: 0;'>" +
            printableElement.outerHTML + "<style>@page { size: A4; } #printable { margin-left: 2.85cm; width: 1.6cm; height: 0.1cm; } #printable .barcode { width: 100%; }</style> </body></html>"

        document.body.appendChild(printFrame)

        let iframeElement = document.getElementById("printjs")
        iframeElement.focus()
        iframeElement.contentWindow.print()
        //
        // printframe.contentwindow.print()
        //
        // my_window = window.open('', 'mywindow', 'status=1,width=350,height=150');
        // my_window.document.write('<html><head><title>Print Me</title></head>');
        // my_window.document.write('<body onafterprint="self.close()">');
        // my_window.document.write(printablEelement.innerHTML);
        // my_window.document.write('</body></html>');
        // my_window.print();
    }
</script>
</body>
</html>
