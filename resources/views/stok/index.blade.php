@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">{{ $page->title }}</h3>
      <div class="card-tools">
        <a class="btn btn-sm btn-primary mt-1" href="{{ url('stok/create') }}">Tambah</a>
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
      <table class="table-bordered table-striped table-hover table-sm table" id="table_stok">
        <thead>
          <tr>
            <th>ID</th>
            <th>Barang</th>
            <th>User</th>
            <th>Stok Tanggal</th>
            <th>Stok Jumlah</th>
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
      var dataStok = $('#table_stok').DataTable({
        serverSide: true,
        ajax: {
          "url": "{{ url('stok/list') }}",
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
          data: 'stok_id',
          className: "text-center",
          width: "5%",
        }, {
          data: "barang.barang_nama",
          orderable: false,
        }, {
          data: "user.nama",
        }, {
          data: "stok_tanggal",
        }, {
          data: "stok_jumlah",
        }, {
          data: 'action',
          className: 'text-center',
          width: "15%",
          orderable: false,
          searchable: false
        }]
      });

      $('#user_id').on('change', function() {
        dataStok.ajax.reload();
      });
    });
  </script>
@endpush
