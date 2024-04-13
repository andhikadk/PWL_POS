@extends('layouts.template')

@section('content')
  <div class="row">
    <div class="col-12 col-md-6">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">Data Barang</h3>
          <div class="card-tools"></div>
        </div>
        <div class="card-body" style="overflow-y: auto;">
          <table class="table-bordered table-sm table" id="table_barang">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">{{ $page->title }}</h3>
          <div class="card-tools"></div>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ url('penjualan') }}" class="form-horizontal">
            @csrf
            <div class="form-group row">
              <label class="col-2 control-label col-form-label">User</label>
              <div class="col-10">
                <select class="form-control" id="user_id" name="user_id" required>
                  <option value="">- Pilih User -</option>
                  @foreach ($user as $item)
                    <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                  @endforeach
                </select>
                @error('user_id')
                  <small class="form-text text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label class="col-2 control-label col-form-label">Pembeli</label>
              <div class="col-10">
                <input type="text" class="form-control" id="pembeli" name="pembeli" value="{{ old('pembeli') }}"
                  required>
                @error('pembeli')
                  <small class="form-text text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label class="col-2 control-label col-form-label">Tanggal</label>
              <div class="col-10">
                <input type="date" class="form-control" id="penjualan_tanggal" name="penjualan_tanggal"
                  value="{{ old('penjualan_tanggal', date('Y-m-d')) }}" required>
                @error('penjualan_tanggal')
                  <small class="form-text text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <table class="table-bordered table-striped table-sm table">
                <thead>
                  <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th class="text-center">Harga</th>
                    <th class="text-center">Subtotal</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody id="list-barang">
                  <tr>
                    <td colspan="5" class="text-center">
                      <div class="alert alert-secondary mt-2" role="alert">Silakan cari barang terlebih dahulu</div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="form-group row">
              <label class="col-2 control-label col-form-label">Harga Total</label>
              <div class="col-10">
                <input type="text" class="form-control" id="harga_total" name="harga_total"
                  value="{{ old('harga_total', 0) }}" required readonly>
                @error('harga_total')
                  <small class="form-text text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label class="col-2 control-label col-form-label"></label>
              <div class="col-10">
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                <a class="btn btn-sm btn-default ml-1" href="{{ url('penjualan') }}">Kembali</a>
              </div>
            </div>
          </form>
        </div>
      </div>
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
          "url": "{{ url('penjualan/list-barang') }}",
          "dataType": "json",
          "type": "POST",
        },
        order: [
          [0, 'desc']
        ],
        columns: [{
          data: "barang_id",
          className: "text-center",
          width: "10",
        }, {
          data: "barang_nama",
          width: "100",
        }, {
          data: "harga_jual",
          render: $.fn.dataTable.render.number('.', ',', 0, 'Rp'),
          width: "50",
        }, {
          data: "stok",
          orderable: false,
          width: "20",
        }, {
          data: 'action',
          className: 'text-center',
          width: "50",
          orderable: false,
          searchable: false
        }]
      });

      let listBarang = [];
      let hargaTotal = 0;

      function pilihBarang(barang_id, barang_nama, harga, stok) {
        let index = listBarang.findIndex(x => x.barang_id == barang_id);

        if (stok === 0) {
          alert('Stok barang sudah habis');
          return;
        }

        if (index >= 0) {
          if (listBarang[index].jumlah >= stok) {
            alert('Stok barang sudah mencapai batas');
            return;
          }
          listBarang[index].jumlah += 1;
          hargaTotal += harga;
          $('#harga_total').val(formatHarga(hargaTotal));
        } else {
          listBarang.push({
            barang_id: barang_id,
            barang_nama: barang_nama,
            harga: harga,
            jumlah: 1
          });
          hargaTotal += harga;
          $('#harga_total').val(formatHarga(hargaTotal));
        }

        tampilkanListBarang();
      }

      function hapusBarang(index) {
        let item = listBarang[index];

        hargaTotal -= item.harga;
        $('#harga_total').val(formatHarga(hargaTotal));

        if (item.jumlah === 1) {
          listBarang.splice(index, 1);
        } else {
          item.jumlah -= 1;
        }

        tampilkanListBarang();
      }

      function tampilkanListBarang() {
        let html = '';
        listBarang.forEach(function(item, index) {
          html += '<tr>';
          html += '<td>' + item.barang_nama + '</td>';
          html += '<td>' + item.jumlah + '</td>';
          html += '<td class="text-right">' + formatHarga(item.harga) + '</td>';
          html += '<td class="text-right">' + formatHarga(item.harga * item.jumlah) + '</td>';
          html += '<td class="text-center"><a id="delete-' + item.barang_id +
            '" href="javascript:void(0)" class="btn btn-danger btn-sm">Hapus</a></td>';
          html += '</tr>';

          html += '<input type="hidden" name="barang[]" value="' + item.barang_id + '">';
          html += '<input type="hidden" name="jumlah[]" value="' + item.jumlah + '">';
          html += '<input type="hidden" name="harga[]" value="' + item.harga + '">';
        });
        $('#list-barang').html(html);

        listBarang.forEach(function(item, index) {
          $('#delete-' + item.barang_id).click(function() {
            hapusBarang(index);
          });
        });
      }

      function formatHarga(harga) {
        return 'Rp' + harga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
      }

      $(document).on('click', 'a.btn-primary', function() {
        let barang_id = $(this).data('barang');
        let barang_nama = $(this).data('nama');
        let harga = $(this).data('harga');
        let stok = $(this).data('stok');

        pilihBarang(barang_id, barang_nama, harga, stok);
      });
    });
  </script>
@endpush
