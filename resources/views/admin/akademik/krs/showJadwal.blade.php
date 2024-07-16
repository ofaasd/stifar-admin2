<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">

<div class="table-responsive mt-2">
    <table class="table" id="tableJadwal">
        <thead>
            <td>No.</td>
            <td>Kelas</td>
            <td>Nama Matakuliah</td>
            <!-- <td>SKS</td> -->
            <td>Hari, Waktu</td>
            <td>Ruang</td>
            <td>Kuota</td>
            <td>Aksi</td>
        </thead>
        <tbody>
        @foreach($jadwal as $j)
            <tr>
                <td>{{ $n++ }}</td>
                <td>{{ $j['kel'] }}</td>
                <td>{{ $j['nama_matkul'] }}</td>
                <!-- <td>{{ $j['sks_teori'] }}</td> -->
                <td>{{ $j['hari'] }}, {{ $j['nama_sesi'] }}</td>
                <td>{{ $j['nama_ruang'] }}</td>
                <td>{{ $j['kuota'] }}</td>
                <td>
                    <a href="{{ url('admin/masterdata/krs/input/'.$j['id'].'/'.$idmhs) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i>
                        Tambahkan
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <hr>
</div>
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script>
    const baseUrl = {!! json_encode(url('/')) !!};
    $(function() {
            $("#tableJadwal").DataTable({
                responsive: true
            })
    })
</script>
