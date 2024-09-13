@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{'Daftar Dosen Pembimbing'}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">{{'Daftar Dosen Pembimbing'}}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary mb-3" id="">Tambah Dosen Pembimbing</button>
                    <div class="table-responsive">
                      <table class="display" id="pembimbing-table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Nip</th>
                            <th>Nama</th>
                            <th>Kuota</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
              </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script>
    $(document).ready(function () {
    
        $('#pembimbing-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.pembimbing.listDosen') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nip', name: 'nip' },
                { data: 'nama', name: 'nama' },
                { data: 'kuota', name: 'kuota' },
                { data: 'button', name: 'button', orderable: false, searchable: false }
            ],
            language: {
                emptyTable: "Tidak ada data dosen pembimbing yang tersedia." // Pesan ketika data kosong
            }
            });
            });
            



</script>
@endsection
