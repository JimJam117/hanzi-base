@extends('layouts.error')

{{-- Title --}}
@section('title')
Character {{$char ? "\"$char\"" : ""}} Not Found
@endsection

@section('error')
    @if (isset($char))
        <h2>The character you were looking for "{{$char}}" couldn't be found</h2>
        <h3>It could be the input provided isn't a character, or it isn't available via the APIs HanziBase uses.</h3>
    @else
        <h2>The character or page you were looking for couldn't be found</h2>  
    @endif
@endsection