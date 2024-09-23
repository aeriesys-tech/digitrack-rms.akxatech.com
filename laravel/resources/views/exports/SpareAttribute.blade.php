<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <title>Spare Attribute Report</title>
</head>
<body>
    <table border="1" width="100%" class="table" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Field Name</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Display Name</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Field Type</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Field Values</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Field Length</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>Is Required</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>User ID</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>List Parameter ID</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach($spare_attributes as $attribute)
                <tr>
                    <td style="text-align: center;">{{ $attribute->field_name }}</td>
                    <td style="text-align: center;">{{ $attribute->display_name }}</td>
                    <td style="text-align: center;">{{ $attribute->field_type }}</td>
                    <td style="text-align: center;">{{ $attribute->field_values }}</td>
                    <td style="text-align: center;">{{ $attribute->field_length }}</td>
                    <td style="text-align: center;">{{ $attribute->is_required }}</td>
                    <td style="text-align: center;">{{ $attribute->user_id }}</td>
                    <td style="text-align: center;">{{ $attribute->list_parameter_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>