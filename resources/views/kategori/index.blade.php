@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">{{ $page->title }}</h3>
      <div class="card-tools">
        <a class="btn btn-sm btn-primary mt-1" href="{{ url('kategori/create') }}">Tambah</a>
      </div>
    </div>
    <div class="card-body">
      @include('components.alert')
      <div class="row">
        <div class="col-md-12">
          <div class="form-group row">
            <label class="col-1 control-label col-form-label">Filter</label>
            <div class="col-3">
              <select type="text" class="form-control" id="kategori_kode" name="kategori_kode" required>
                <option value="">- Semua -</option>
                @foreach ($kategori as $item)
                  <option value="{{ $item->kategori_kode }}">{{ $item->kategori_kode }}</option>
                @endforeach
              </select>
              <small class="form-text text-muted">Kode Kategori</small>
            </div>
          </div>
        </div>
      </div>
      <table class="table-bordered table-striped table-hover table-sm table" id="table_kategori">
        <thead>
          <tr>
            <th>ID</th>
            <th>Kode Kategori</th>
            <th>Nama Kategori</th>
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
      var dataKategori = $('#table_kategori').DataTable({
        serverSide: true,
        ajax: {
          "url": "{{ url('kategori/list') }}",
          "dataType": "json",
          "type": "POST",
          "data": function(d) {
            d.kategori_kode = $("#kategori_kode").val();
          }
        },
        order: [
          [0, 'desc']
        ],
        columns: [{
            data: 'kategori_id',
            className: "text-center",
            width: "5%",
          },
          {
            data: 'kategori_kode',
          },
          {
            data: 'kategori_nama',
          },
          {
            data: 'action',
            className: 'text-center',
            width: "15%",
            orderable: false,
            searchable: false
          }
        ]
      });
      $('#kategori_kode').change(function() {
        dataKategori.ajax.reload();
      });
    });
  </script>
@endpush
