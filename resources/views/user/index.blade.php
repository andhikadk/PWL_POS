@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">{{ $page->title }}</h3>
      <div class="card-tools">
        <a class="btn btn-sm btn-primary mt-1" href="{{ url('user/create') }}">Tambah</a>
      </div>
    </div>
    <div class="card-body">
      @include('components.alert')
      <div class="row">
        <div class="col-md-12">
          <div class="form-group row">
            <label class="col-1 control-label col-form-label">Filter: </label>
            <div class="col-3">
              <select class="form-control" name="level_id" id="level_id" required>
                <option value="">-- Semua --</option>
                @foreach ($level as $item)
                  <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                @endforeach
              </select>
              <small class="form-text text-muted">Level User</small>
            </div>
          </div>
        </div>
      </div>
      <table class="table-bordered table-striped table-hover table-sm table" id="table_user">
        <thead>
          <tr>
            <th>ID</th>
            <th>Level</th>
            <th>Username</th>
            <th>Nama</th>
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
      var dataUser = $('#table_user').DataTable({
        serverSide: true,
        ajax: {
          "url": "{{ url('user/list') }}",
          "dataType": "json",
          "type": "POST",
          "data": function(d) {
            d.level_id = $('#level_id').val();
          }
        },
        order: [
          [0, 'desc']
        ],
        columns: [{
          data: 'user_id',
          className: "text-center",
          width: "5%",
        }, {
          data: "level.level_nama",
          orderable: false
        }, {
          data: "nama",
        }, {
          data: "username",
        }, {
          data: 'action',
          className: 'text-center',
          width: "15%",
          orderable: false,
          searchable: false
        }]
      });

      $('#level_id').on('change', function() {
        dataUser.ajax.reload();
      });
    });
  </script>
@endpush
