@include('partials.topbar')

<div class="main">

    <div class="notfound">
        <h1>很抱歉！</h1>
        @isset($char)
        <h2>The character you were looking "{{$char}}" for couldn't be found</h2>
        @else
        <h2>The character or page you were looking for couldn't be found</h2>  
        @endisset
    
    <p>If you suspect that this is an error, please contact the admin jamesparrow101@googlemail.com</p>
    </div>
    

    @include('partials.footer')
</div>

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