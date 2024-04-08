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
      @include('components.alert')
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
              <small class="form-text text-muted">User</small>
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
        order: [
          [0, 'desc']
        ],
        columns: [{
          data: "penjualan_id",
          className: "text-center",
          width: "5%",
        }, {
          data: "user.nama",
          orderable: false
        }, {
          data: "pembeli",
        }, {
          data: "penjualan_kode",
        }, {
          data: "penjualan_tanggal",
        }, {
          data: "total_barang",
          orderable: false
        }, {
          data: "total_harga",
          className: "text-right",
          render: $.fn.dataTable.render.number('.', ',', 0, 'Rp'),
          orderable: false
        }, {
          data: 'action',
          className: 'text-center',
          width: "15%",
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
