<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">

<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>

<script>
    @if (session('success'))
        setTimeout(() => {
            swal('{{ session("success") }}', '', 'success');
        }, 1000);
    @elseif (session()->has('error'))
        swal('{{ session("error") }}', '', 'warning');
    @endif
</script>
