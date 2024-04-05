@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">{{ $page->title }}</h3>
      <div class="card-tools">
        <a class="btn btn-sm btn-primary mt-1" href="{{ url('penjualan/create') }}">Tambah</a>
      </div>
    </div>
    <div class="card-body">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
      @endif
      <div class="row">
        <div class="col-md-12">
          <div class="form-group row">
            <label class="col-1 control-label col-form-label">Filter: </label>
            <div class="col-3">
              <select class="form-control" name="user_id" id="user_id" required>
                <option value="">-- Semua --</option>
                @foreach ($user as $item)
                  <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                @endforeach
              </select>
              <small class="form-text text-muted">User Penjualan</small>
            </div>
          </div>
        </div>
      </div>
      <table class="table-bordered table-striped table-hover table-sm table" id="table_penjualan">
        <thead>
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Pembeli</th>
            <th>Kode Penjualan</th>
            <th>Tanggal Penjualan</th>
            <th>Total Barang</th>
            <th>Total Harga</th>
            <th>Aksi</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

@push('css')
@endpush
@push('js')
  <script>
    $(document).ready(function() {
      var dataPenjualan = $('#table_penjualan').DataTable({
        serverSide: true,
        ajax: {
          "url": "{{ url('penjualan/list') }}",
          "dataType": "json",
          "type": "POST",
          "data": function(d) {
            d.user_id = $('#user_id').val();
          }
        },
        columns: [{
          data: "DT_RowIndex",
          className: "text-center",
          orderable: false,
          searchable: false
        }, {
          data: "user.nama",
          className: "",
        }, {
          data: "pembeli",
          className: "",
        }, {
          data: "penjualan_kode",
          className: "",
        }, {
          data: "penjualan_tanggal",
          className: "",
        }, {
          data: "total_barang",
          className: "",
        }, {
          data: "total_harga",
          className: "",
        }, {
          data: "action",
          className: "",
          orderable: false,
          searchable: false
        }]
      });

      $('#user_id').on('change', function() {
        dataPenjualan.ajax.reload();
      });
    });
  </script>
@endpush
