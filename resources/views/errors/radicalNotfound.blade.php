@extends('layouts.error')

{{-- Title --}}
@section('title')
Radical {{$char ? "\"$char\"" : ""}} Not Found
@endsection

@section('error')
    @if(isset($char))
        <h2>The radical you were looking for "{{$char}}" couldn't be found</h2>
        <h3>It could be the character you're searching with isn't a radical</h3>
    @else
        <h2>The character or page you were looking for couldn't be found</h2>  
    @endif
@endsection