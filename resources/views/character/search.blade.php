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

   @if (isset($isRadicalSearch))
    <h1>Results for Radical: {{$search}}</h1>
   @else
    <h1>Results for "{{$search}}"</h1>
   @endif
    

    {{-- If there are no results --}}
    @if ($results->count() == 0)
        <div class="noResults">Sorry, no results found ;(</div>
    @else
        <div id="chars" data-search={{ $search }}></div>
            <script src="/js/app.js"></script>
    @endif

  

@endsection

@section('extra-scripts')  
<style>
    .noResults{
        text-align: center;
        padding: 3em 1em;
        font-size: 2rem;
    }
</style>
@endsection
