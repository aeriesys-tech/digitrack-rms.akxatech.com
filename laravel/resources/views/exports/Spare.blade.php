<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <title>Spare Report</title>
</head>
<body>
    <table border="1" width="100%" class="table" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Spare Type Id</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Spare Code</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Spare Name</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>List Parameter Id</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach($spares as $spare)
                <tr>
                    <td style="text-align: center;">{{ $spare->spare_type_id }}</td>
                    <td style="text-align: center;">{{ $spare->spare_code }}</td>
                    <td style="text-align: center;">{{ $spare->spare_name }}</td>
                    <td style="text-align: center;">{{ $spare->list_parameter_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>