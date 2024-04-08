@extends('layouts.template')

@section('content')
  <div class="row">
    <div class="col-6">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">Data Barang</h3>
          <div class="card-tools"></div>
        </div>
        <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
          <table class="table-bordered table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Harga Jual</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($barang as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->barang_nama }}</td>
                  <td>{{ $item->harga_jual }}</td>
                  <td>
                    <a id="{{ $item->barang_id }}" href="javascript:void(0)" data-nama="{{ $item->barang_nama }}"
                      data-harga="{{ $item->harga_jual }}" data-barang="{{ $item->barang_id }}"
                      class="btn btn-primary btn-sm">Tambah</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-6">
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
            {{-- tempat untuk menampung list barang yang sudah dipilih --}}
            <div class="form-group row">
              {{-- kosong jika tidak ada barang yang dipilih --}}
              {{-- tampilkan table head --}}
              <div class="col-4"><strong>Nama Barang</strong></div>
              <div class="col-2"><strong>Jumlah</strong></div>
              <div class="col-3"><strong>Harga</strong></div>
              <div class="col-3"><strong>Aksi</strong></div>
              {{-- tampilkan list barang yang sudah dipilih --}}
              <div class="col-12" id="list-barang">
                {{-- Tampilkan tidak ada barang yang dipilih --}}
                <div class="row">
                  <div class="alert alert-secondary col-12 mt-2 text-center" role="alert">Silakan cari barang terlebih
                    dahulu</div>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-2 control-label col-form-label">Harga Total</label>
              <div class="col-10">
                <input type="number" class="form-control" id="harga_total" name="harga_total"
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
      // variabel untuk menampung list barang yang sudah dipilih
      let listBarang = [];
      let hargaTotal = 0;

      // fungsi untuk memilih barang
      function pilihBarang(barang_id, barang_nama, harga) {
        // cek apakah barang sudah ada di list
        let index = listBarang.findIndex(x => x.barang_id == barang_id);

        // jika barang sudah ada di list
        if (index >= 0) {
          // tambahkan jumlah barang
          listBarang[index].jumlah += 1;
          // update harga total
          hargaTotal += harga;
          $('#harga_total').val(hargaTotal);
        } else {
          // jika barang belum ada di list
          // tambahkan barang ke list
          listBarang.push({
            barang_id: barang_id,
            barang_nama: barang_nama,
            harga: harga,
            jumlah: 1
          });
          // update harga total
          hargaTotal += harga;
          $('#harga_total').val(hargaTotal);
        }

        // tampilkan list barang yang sudah dipilih
        tampilkanListBarang();
      }

      // fungsi untuk menghapus barang
      function hapusBarang(index) {
        let item = listBarang[index];

        // kurangi harga total
        $('#harga_total').val(parseInt($('#harga_total').val()) - item.harga);

        // jika jumlah barang hanya 1, hapus barang dari list
        if (item.jumlah === 1) {
          listBarang.splice(index, 1);
        } else {
          // jika jumlah barang lebih dari 1, kurangi jumlah barang
          item.jumlah -= 1;
        }

        // tampilkan list barang yang sudah dipilih
        tampilkanListBarang();
      }

      // fungsi untuk menampilkan list barang yang sudah dipilih
      function tampilkanListBarang() {
        let html = '';
        listBarang.forEach(function(item, index) {
          html += '<div class="row mb-2">';
          html += '<div class="col-4">' + item.barang_nama + '</div>';
          html += '<div class="col-2">' + item.jumlah + '</div>';
          html += '<div class="col-3">' + item.harga + '</div>';
          html += '<div class="col-3"><a id="delete-' + item.barang_id +
            '" href="javascript:void(0)" class="btn btn-danger btn-sm">Hapus</a></div>';
          html += '</div>';

          // add hidden input fields for each item
          html += '<input type="hidden" name="barang[]" value="' + item.barang_id + '">';
          html += '<input type="hidden" name="jumlah[]" value="' + item.jumlah + '">';
          html += '<input type="hidden" name="harga[]" value="' + item.harga + '">';
        });
        $('#list-barang').html(html);

        // bind event click untuk tombol hapus
        listBarang.forEach(function(item, index) {
          $('#delete-' + item.barang_id).click(function() {
            hapusBarang(index);
          });
        });
      }

      // ketika tombol pilih di klik
      $('a.btn-primary').click(function() {
        // ambil data dari tombol yang di klik
        let barang_id = $(this).data('barang');
        let barang_nama = $(this).data('nama');
        let harga = $(this).data('harga');

        pilihBarang(barang_id, barang_nama, harga);
      });
    });
  </script>
@endpush
