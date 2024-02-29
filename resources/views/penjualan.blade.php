<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Penjualan</title>
</head>

<body>
    <h1>Data Penjualan</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>ID User</th>
            <th>Pembeli</th>
            <th>Kode Penjualan</th>
            <th>Tanggal Penjualan</th>
        </tr>
        @foreach ($data as $d)
            <tr>
                <td>{{ $d->penjualan_id }}</td>
                <td>{{ $d->user_id }}</td>
                <td>{{ $d->pembeli }}</td>
                <td>{{ $d->penjualan_kode }}</td>
                <td>{{ $d->penjualan_tanggal }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
