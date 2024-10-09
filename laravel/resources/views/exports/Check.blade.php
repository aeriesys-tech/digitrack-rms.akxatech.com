<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <title>Check Report</title>
</head>
<body>
    <table border="1" width="100%" class="table" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Field Name</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Field Type</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Default Value</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Is Required</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>UCL</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>LCL</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Field Values</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Order</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Department Id</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach($checks as $check)
                <tr>
                    <td style="text-align: center;">{{ $check->field_name }}</td>
                    <td style="text-align: center;">{{ $check->field_type }}</td>
                    <td style="text-align: center;">{{ $check->default_value }}</td>
                    <td style="text-align: center;">{{ $check->is_required }}</td>
                    <td style="text-align: center;">{{ $check->ucl }}</td>
                    <td style="text-align: center;">{{ $check->lcl }}</td>
                    <td style="text-align: center;">{{ $check->field_values }}</td>
                    <td style="text-align: center;">{{ $check->order }}</td>
                    <td style="text-align: center;">{{ $check->department_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>