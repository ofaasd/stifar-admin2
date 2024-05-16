@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<!-- Include stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Admisi</li>
    <li class="breadcrumb-item">Daftar Soal</li>
    <li class="breadcrumb-item active">Create Daftar Soal</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <a href="{{URL::to('admin/admisi/daftar_soal')}}" class="btn btn-primary"><i class="fa fa-left-arrow"></i> Kembali</a>
                    </div>
                    <div class="card-body">
                        <form action="javascript:void(0)" method="POST" id="formCreate">
                            @csrf
                            <input type="hidden" name="id" value="0" id="id">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-2">
                                        <label for="soal">Soal</label>
                                        <div id="soal"></div>
                                    </div>
                                    <div class="mb-2" style="margin-top:30px;">
                                        <label for="pilihana">Pilihan A</label>
                                        <div id="pilihana"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label for="pilihanb">Pilihan B</label>
                                        <div id="pilihanb"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label for="pilihanc">Pilihan C</label>
                                        <div id="pilihanc"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label for="pilihand">Pilihan D</label>
                                        <div id="pilihand"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label for="pilihane">Pilihan E</label>
                                        <div id="pilihane"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label for="kunci">Kunci Jawaban</label>
                                        <input type="text" name="kunci" id="kunci" class="form-control" placeholder="Hanya Abjad nya saja | cth : A">
                                    </div>
                                    <div class="mb-2">
                                        <input type="submit" value="Simpan" class="btn btn-primary" id="save">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script>
        const baseUrl = {!! json_encode(url('/')) !!};

        const quill = new Quill('#soal', {
            modules: {
                toolbar: [
                ['bold', 'italic'],
                ['link', 'blockquote', 'code-block', 'image'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ],
            },
            theme: 'snow',
        });
        const quill2 = new Quill('#pilihana', {
            theme: 'snow'
        });
        const quill3 = new Quill('#pilihanb', {
            theme: 'snow'
        });
        const quill4 = new Quill('#pilihanc', {
            theme: 'snow'
        });
        const quill5 = new Quill('#pilihand', {
            theme: 'snow'
        });
        const quill6 = new Quill('#pilihane', {
            theme: 'snow'
        });
        document.addEventListener('DOMContentLoaded', function () {
           $("#formCreate").submit(function(){
            //alert("masuk sini");
            event.preventDefault();
            const form = document.getElementById('formCreate');
            const myFormData = new FormData(form);
            // Append Quill content before submitting
            myFormData.append('soal', quill.getSemanticHTML());
            myFormData.append('pilihan1', quill2.getSemanticHTML());
            myFormData.append('pilihan2', quill3.getSemanticHTML());
            myFormData.append('pilihan3', quill4.getSemanticHTML());
            myFormData.append('pilihan4', quill5.getSemanticHTML());
            myFormData.append('pilihan5', quill6.getSemanticHTML());
            $.ajax({
                data: myFormData,
                url: ''.concat(baseUrl).concat('/admin/admisi/daftar_soal'),
                type: 'POST',
                processData: false,
                contentType: false,
                success: function success(status) {

                    $("#tambahModal").modal('hide');

                    // sweetalert
                    swal({
                    icon: 'success',
                    title: 'Successfully '.concat(status, '!'),
                    text: ''.concat(status, 'Created Successfully.'),
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                    });
                    //window.location.href = '{{URL::to("admin/admisi/daftar_soal")}}';
                },
                error: function error(err) {

                    swal({
                    title: 'Duplicate Entry!',
                    text: title + ' Not Saved !',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                    });
                }
            });
        });
        @if(!empty($id))
            const object = [{
                insert : `{!! $soal->soal !!}`
            }]
            const object1 = [{
                insert : `{!! $pilihan[1] !!}`
            }]
            const object2 = [{
                insert : `{!! $pilihan[2] !!}`
            }]
            const object3 = [{
                insert : `{!! $pilihan[3] !!}`
            }]
            const object4 = [{
                insert : `{!! $pilihan[4] !!}`
            }]
            const object5 = [{
                insert : `{!! $pilihan[5] !!}`
            }]
            quill.clipboard.dangerouslyPasteHTML(0,object[0].insert);
            quill2.clipboard.dangerouslyPasteHTML(0,object1[0].insert);
            quill3.clipboard.dangerouslyPasteHTML(0,object2[0].insert);
            quill4.clipboard.dangerouslyPasteHTML(0,object3[0].insert);
            quill5.clipboard.dangerouslyPasteHTML(0,object4[0].insert);
            quill6.clipboard.dangerouslyPasteHTML(0,object5[0].insert);

            $("#kunci").val("{{$kunci->kunci}}");
            $("#id").val("{{$id}}");
        @endif
        });
    </script>

@endsection
