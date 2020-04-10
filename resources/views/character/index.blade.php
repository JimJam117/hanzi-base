@extends('layouts.app')

{{-- Title --}}
@section('title')
{{ $chars->currentPage() == 1 ? "Browse All Characters" : "Browse All (Page " . $chars->currentPage() .")" }}
@endsection

@section('main')
    
   
        <div id="chars"></div>
        <script src="/js/app.js"></script>
    


    @endsection
