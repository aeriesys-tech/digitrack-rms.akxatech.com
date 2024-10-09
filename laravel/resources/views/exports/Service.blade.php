
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Export</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <table class="table table-bordered mt-4" style="border-collapse: collapse;">

            @foreach ($rows as $index => $row)
                <tr>
                    @foreach ($row as $cell)
                        @if ($index % 2 == 0)
                            <td class="text-center"><strong>{{ $cell }}</strong></td>
                        @else
                            <td>{{ $cell }}</td>
                        @endif
                    @endforeach
                </tr>

                @if ($index % 2 == 1)
                    <tr>
                        <td colspan="{{ count($row) }}" style="height: 20px;"></td>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>
</body>
</html>

