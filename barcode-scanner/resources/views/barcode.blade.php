<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barcode Scanner</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col justify-center items-center p-4">

<div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-6 space-y-6">
    <h1 class="text-2xl font-bold text-center text-blue-700">Barcode Scanner</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- SCANNER UI -->
    <div class="flex flex-col items-center gap-3">
        <div id="reader" class="w-full border border-gray-300 rounded-xl overflow-hidden"></div>

        <div class="flex flex-col sm:flex-row justify-center gap-2 mt-3 w-full">
            <button id="start-scan-btn"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition duration-200">
                📷 Start Scanning
            </button>
            <button id="scan-image-btn"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-md transition duration-200">
                🖼️ Scan an Image File
            </button>
        </div>
    </div>

    <!-- FORM -->
    <form method="POST" action="{{ route('barcode.store') }}" class="space-y-4">
        @csrf
        <div>
            <label for="barcode" class="block text-sm font-medium text-gray-700">Scanned Barcode</label>
            <input type="text" id="barcode" name="code" readonly
                   class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition duration-200">
            Save Barcode
        </button>
    </form>
</div>

<script>
    let html5QrCode;
    const readerId = "reader";
    const scanBtn = document.getElementById('start-scan-btn');
    const imageBtn = document.getElementById('scan-image-btn');
    const barcodeInput = document.getElementById('barcode');

    // Create hidden file input
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.style.display = 'none';
    document.body.appendChild(fileInput);

    // Start/Stop Live Scanning
    scanBtn.addEventListener('click', async () => {
        if (html5QrCode && html5QrCode._isScanning) {
            await html5QrCode.stop();
            html5QrCode.clear();
            scanBtn.innerText = "📷 Start Scanning";
        } else {
            html5QrCode = new Html5Qrcode(readerId);
            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 15,
                    qrbox: { width: 250, height: 250 },
                    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
                    formatsToSupport: [
                        Html5QrcodeSupportedFormats.QR_CODE,
                        Html5QrcodeSupportedFormats.CODE_128,
                        Html5QrcodeSupportedFormats.CODE_39,
                        Html5QrcodeSupportedFormats.EAN_13,
                        Html5QrcodeSupportedFormats.UPC_A,
                        Html5QrcodeSupportedFormats.UPC_E,
                    ]
                },
                (decodedText) => {
                    barcodeInput.value = decodedText;
                    html5QrCode.stop();
                    scanBtn.innerText = "📷 Start Scanning";
                },
                (errorMessage) => {
                    // Optional: console.log(errorMessage);
                }
            );
            scanBtn.innerText = "⏹ Stop Scanning";
        }
    });

    // Image Scan Button opens file dialog
    imageBtn.addEventListener('click', () => {
        fileInput.click();
    });

    // Scan selected image file
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        html5QrCode = new Html5Qrcode(readerId);
        html5QrCode.scanFile(file, true)
            .then(decodedText => {
                barcodeInput.value = decodedText;
                html5QrCode.clear();
                fileInput.value = ""; // Reset file input
            })
            .catch(err => {
                alert("No barcode found in image.");
                fileInput.value = "";
            });
    });
</script>


</body>
</html>
