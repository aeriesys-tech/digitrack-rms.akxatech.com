<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset QRCode</title>
</head>
<body>
    <img src="{{ $data_uri }}" alt="QR Code">
    <p style="padding-left:75px">{{ $asset_code }}</p>
    <p>Please Scan the QR Code to get the asset details...</p>
</body>
</html>
