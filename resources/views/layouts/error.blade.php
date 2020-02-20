@extends('layouts.app')


@section('main')
<div class="notfound">
    <h1>很抱歉！</h1>

    @yield('error')

    <p>If you suspect that you're seeing this page in error, please contact the admin jamesparrow101@googlemail.com</p>
    
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