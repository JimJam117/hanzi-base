@extends('layouts.app')

{{-- Title --}}
@section('title')
results for "{{$search}}"
@endsection

@section('main')
    
   @if (isset($newCharArray))

        <div class="newCharAdded">New characters "
            @foreach ($newCharArray as $item)
                {{ $item['original'] }}
            @endforeach
            " have now been added to HanziBase!</div>   
   {{-- @elseif() --}}
       
   @endif


    <h1>Characters with Radical {{$search}}</h1>
        


    


    <div id="chars" data-radical={{ $search }}></div>
    <script src="/js/app.js"></script>
    
@endsection
