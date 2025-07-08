<!DOCTYPE html>
<html>
<head>
    <title>Barcode Scanner</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <div id="reader" width="600px"></div>

    <form method="POST" action="{{ route('barcode.store') }}">
        @csrf
        <label>Scanned Barcode:</label>
        <input type="text" id="barcode" name="code" readonly>
        <button type="submit">Save</button>
    </form>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('barcode').value = decodedText;
            html5QrcodeScanner.clear(); // Stop scanning after successful scan
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 });

        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>
