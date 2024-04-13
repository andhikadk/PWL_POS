@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">{{ $page->title }}</h3>
      <div class="card-tools">
        <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
      </div>
    </div>
    <div class="card-body">
      @include('components.alert')
      <div class="row">
        <div class="col-md-12">
          <div class="form-group row">
            <label class="col-1 control-label col-form-label">Filter: </label>
            <div class="col-3">
              <select class="form-control" name="kategori_id" id="kategori_id" required>
                <option value="">-- Semua --</option>
                @foreach ($kategori as $item)
                  <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                @endforeach
              </select>
              <small class="form-text text-muted">Kategori Barang</small>
            </div>
          </div>
        </div>
      </div>
      <table class="table-bordered table-striped table-hover table-sm table" id="table_barang">
        <thead>
          <tr>
            <th>ID</th>
            <th>Kategori</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Stok</th>
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
      var dataBarang = $('#table_barang').DataTable({
        serverSide: true,
        ajax: {
          "url": "{{ url('barang/list') }}",
          "dataType": "json",
          "type": "POST",
          "data": function(d) {
            d.kategori_id = $('#kategori_id').val();
          }
        },
        order: [
          [0, 'desc']
        ],
        columns: [{
            data: "barang_id",
            className: "text-center",
            width: "5%",
          },
          {
            data: "kategori.kategori_nama",
            orderable: false,
          },
          {
            data: "barang_kode",
          },
          {
            data: "barang_nama",
          },
          {
            data: "harga_beli",
            className: "text-right",
            render: $.fn.dataTable.render.number('.', ',', 0, 'Rp'),
          },
          {
            data: "harga_jual",
            className: "text-right",
            render: $.fn.dataTable.render.number('.', ',', 0, 'Rp'),
          },
          {
            data: "stok",
            className: "text-center",
          },
          {
            data: 'action',
            className: 'text-center',
            width: "15%",
            orderable: false,
            searchable: false
          }
        ],
      });

      $('#kategori_id').on('change', function() {
        dataBarang.ajax.reload();
      });
    });
  </script>
  </script>
@endpush
