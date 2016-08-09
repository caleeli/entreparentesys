@extends('layouts.app')

@push('css-head')
<link href="/css/dropzone.min.css" rel="stylesheet" />
<style>
.dropzone { margin-bottom: 3rem; }
.dropzone {border: 2px dashed #0087F7;border-radius: 5px;background: white;}
.dropzone .dz-message { font-weight: 400; }
.dropzone .dz-message .note { font-size: 0.8em; font-weight: 200; display: block; margin-top: 1.4rem; }
</style>
@endpush
@section('header')
<script type="text/javascript" src="/js/dropzone.min.js"></script>
@endsection
@section('content')
<form action="{{route('import-excel.store')}}" class="dropzone">
  <div class="fallback">
    <input name="file" type="file" />
  </div>
</form>
@endsection
