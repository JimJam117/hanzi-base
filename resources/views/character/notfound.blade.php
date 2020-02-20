@extends('layouts.app')

{{-- Title --}}
@section('title')
Character "{{$char}}" Not Found
@endsection


@section('main')
<div class="notfound">
    <h1>很抱歉！</h1>

    @if(isset($notRadical) && isset($char))
        <h2>The radical you were looking for "{{$char}}" couldn't be found</h2>
    @elseif (isset($char))
        <h2>The character you were looking for "{{$char}}" couldn't be found</h2>
    @else
        <h2>The character or page you were looking for couldn't be found</h2>  
    @endif

    <p>If you suspect that this is an error, please contact the admin jamesparrow101@googlemail.com</p>
    
</div>
@endsection
   
    


@section('extra-scripts')
    <style>
.notfound {
    text-align: center;
    min-height: 70vh;
}

.notfound h1{
    font-family: ZCOOL XiaoWei;
    font-size: 10em;
    font-weight: 100;
    margin-bottom: 2rem;

}

.notfound p {
    padding: 5rem;
}

</style>
@endsection