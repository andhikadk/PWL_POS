@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">{{ $page->title }}</h3>
      <div class="card-tools"></div>
    </div>
    <div class="card-body">
      @empty($penjualan)
        <div class="alert alert-danger alert-dismissible">
          <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
          Data yang Anda cari tidak ditemukan.
        </div>
      @else
        <table class="table-bordered table-striped table-hover sm table table">
          <tr>
            <th>ID</th>
            <td>{{ $penjualan->penjualan_id }}</td>
          </tr>
          <tr>
            <th>User</th>
            <td>{{ $penjualan->user->nama }}</td>
          </tr>
          <tr>
            <th>Kode Penjualan</th>
            <td>{{ $penjualan->penjualan_kode }}</td>
          </tr>
          <tr>
            <th>Nama Pembeli</th>
            <td>{{ $penjualan->pembeli }}</td>
          </tr>
          <tr>
            <th>Tanggal Penjualan</th>
            <td>{{ $penjualan->penjualan_tanggal }}</td>
          </tr>
          <tr>
            <th>Detail Penjualan</th>
            <td>
              <table class="table-bordered table-striped table-hover sm table table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($penjualan_detail as $item)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $item->barang->barang_nama }}</td>
                      <td>{{ 'Rp' . number_format($item->harga, 0, ',', '.') }}</td>
                      <td>{{ $item->jumlah }}</td>
                      <td>{{ 'Rp' . number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <th>Total Harga</th>
            <td>{{ $total_penjualan }}</td>
          </tr>
        </table>
      @endempty
      <a href="{{ url('penjualan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
    </div>
  </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
