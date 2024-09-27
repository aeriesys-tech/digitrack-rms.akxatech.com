<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <title>DataSource Report</title>
</head>
<body>
    <table border="1" width="100%" class="table" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>DataSource Type Id</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>DataSource Code</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>DataSource Name</strong></th>
                <th style="text-align: center; background-color: #98AFC7; color: Black"><strong>List Parameter Id</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_sources as $data_source)
                <tr>
                    <td style="text-align: center;">{{ $data_source->data_source_type_id }}</td>
                    <td style="text-align: center;">{{ $data_source->data_source_code }}</td>
                    <td style="text-align: center;">{{ $data_source->data_source_name }}</td>
                    <td style="text-align: center;">{{ $data_source->list_parameter_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>