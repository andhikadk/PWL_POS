@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'User')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'User')

@section('content')
  <div class="container">
    <div class="card">
      <div class="card-header">Manage User</div>
      <div class="card-body">
        <a href="{{ url('/user/create') }}" class="btn btn-primary mb-3">Tambah User</a>
        {{ $dataTable->table() }}
      </div>
    </div>
  </div>
  </div>
@endsection

@push('scripts')
  {{ $dataTable->scripts() }}
@endpush
