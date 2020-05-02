@extends('layouts.app')

{{-- Title --}}
@section('title')
results for "{{$search}}"
@endsection

@section('main')
    
   @if (isset($newCharArray))
        <div class="newCharAdded">New characters "{{ $newCharString }}" have now been added to HanziBase!</div>   
   {{-- @elseif() --}}
       
   @endif

   @if($tooManyRequests)
            <div class="failedRequests">New characters "
            @foreach ( $failedRequestsArray as $item )
                {{ $item }}
            @endforeach
            " could not be added right now due to rate limits. Please try again later.</div>  
   @endif

   @if (isset($isRadicalSearch))
    <h1>Results for Radical: {{$search}}</h1>
   @else
    <h1>Results for "{{$search}}"</h1>
   @endif


        
        <div id="chars" data-search="{{ $search }}" data-newChars="{{ $newCharString ?? null }}"></div>
            <script src="/js/app.js"></script>

@endsection

