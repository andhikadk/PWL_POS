@extends('layouts.app')
{{-- Customize layout sections --}}
@section('subtitle', 'Kategori')
@section('content_header_title', 'Kategori')
@section('content_header_subtitle', 'Edit')
{{-- Content body: main page content --}}
@section('content')
  <div class="container">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit kategori</h3>
      </div>

      <form method="post" action="{{ url('/kategori/' . $kategori->kategori_id) }}">
        @csrf
        @method('put')
        <div class="card-body">
          <div class="form-group">
            <label for="kodeKategori">Kode Kategori</label>
            <input type="text" class="form-control" id="kodeKategori" name="kodeKategori"
              placeholder="Masukkan Kode Kategori" value="{{ $kategori->kategori_kode }}">
          </div>
          <div class="form-group">
            <label for="namaKategori">Nama Kategori</label>
            <input type="text" class="form-control" id="namaKategori" name="namaKategori"
              placeholder="Masukkan Nama Kategori" value="{{ $kategori->kategori_nama }}">
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
@endsection
