@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">{{ $page->title }}</h3>
      <div class="card-tools">
        <a class="btn btn-sm btn-primary mt-1" href="{{ url('level/create') }}">Tambah</a>
      </div>
    </div>
    <div class="card-body">
      @include('components.alert')
      <div class="row">
        <div class="col-md-12">
          <div class="form-group row">
            <label class="col-1 control-label col-form-label">Filter</label>
            <div class="col-3">
              <select type="text" class="form-control" id="level_kode" name="level_kode" required>
                <option value="">- Semua -</option>
                @foreach ($level as $item)
                  <option value="{{ $item->level_kode }}">{{ $item->level_kode }}</option>
                @endforeach
              </select>
              <small class="form-text text-muted">Kode Level</small>
            </div>
          </div>
        </div>
      </div>
      <table class="table-bordered table-striped table-hover table-sm table" id="table_level">
        <thead>
          <tr>
            <th>ID</th>
            <th>Kode Level</th>
            <th>Nama Level</th>
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
      var dataLevel = $('#table_level').DataTable({
        serverSide: true,
        ajax: {
          "url": "{{ url('level/list') }}",
          "dataType": "json",
          "type": "POST",
          "data": function(d) {
            d.level_kode = $("#level_kode").val();
          }
        },
        order: [
          [0, 'desc']
        ],
        columns: [{
            data: 'level_id',
            className: "text-center",
            width: "5%",
          },
          {
            data: 'level_kode',
          },
          {
            data: 'level_nama',
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
      $('#level_kode').change(function() {
        dataLevel.ajax.reload();
      });
    });
  </script>
@endpush
