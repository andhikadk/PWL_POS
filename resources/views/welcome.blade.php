@extends('layouts.template')

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Halo, apa kabar!</h3>
      <div class="card-tools"></div>
    </div>
    <div class="card-body">
      <p>Selamat datang semua, ini adalah halaman utama dari aplikasi ini.</p>
      <a href="{{ url('penjualan/create') }}" class="btn btn-primary">Mulai Transaksi</a>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-3 col-6">
      <div class="small-box bg-primary">
        <div class="inner">
          <h3>{{ $total_barang }}</h3>
          <p>Total Barang</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="{{ url('barang') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-lg-3 col-6">

      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ 'Rp' . number_format($total_transaksi, 0, ',', '.') }}</h3>
          <p>Total Transaksi</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="{{ url('penjualan') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-lg-3 col-6">

      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ $jumlah_transaksi }}</h3>
          <p>Jumlah Transaksi</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="{{ url('penjualan') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-lg-3 col-6">

      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ 'Rp' . number_format($total_aset, 0, ',', '.') }}</h3>
          <p>Total Aset</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="{{ url('barang') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>

  </div>
@endsection
