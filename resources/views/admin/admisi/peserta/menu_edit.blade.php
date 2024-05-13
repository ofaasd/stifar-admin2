<a href="{{URL::to('admin/admisi/peserta/' . $id . '/edit')}}" class="btn {{($action == 'edit')?'btn-success':'btn-primary'}}">Data Pribadi</a>
<a href="{{URL::to('admin/admisi/peserta/' . $id . '/edit_gelombang')}}" class="btn {{($action == 'edit_gelombang')?'btn-success':'btn-primary'}}">Gelombang Pendaftaran</a>
<a href="{{URL::to('admin/admisi/peserta/' . $id . '/edit_asal_sekolah')}}" class="btn {{($action == 'edit_asal_sekolah')?'btn-success':'btn-primary'}}">Asal Sekolah</a>
<a href="{{URL::to('admin/admisi/peserta/' . $id . '/edit_file_pendukung')}}" class="btn {{($action == 'edit_file_pendukung')?'btn-success':'btn-primary'}}">File Pendukung</a>
