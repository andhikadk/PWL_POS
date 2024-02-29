<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Barang</title>
</head>

<body>
    <h1>Data Barang</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>ID Kategori</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
        </tr>
        @foreach ($data as $d)
            <tr>
                <td>{{ $d->barang_id }}</td>
                <td>{{ $d->kategori_id }}</td>
                <td>{{ $d->barang_kode }}</td>
                <td>{{ $d->barang_nama }}</td>
                <td>{{ $d->harga_beli }}</td>
                <td>{{ $d->harga_jual }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
