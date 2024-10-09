<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <title>Spare Headings</title>
</head>
<body>
    <table border="1" width="100%" class="table" style="border-collapse: collapse;">
        <thead>
            <tr>
                @foreach($rows[0] as $header)
                    <th><strong>{{ $header }}</strong></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($rows[1] as $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
</body>
</html>