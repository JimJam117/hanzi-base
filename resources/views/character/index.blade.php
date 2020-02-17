@include('partials.topbar')

<div class="main">
    @php
        
        //dd($ccdb);
    @endphp
    <div class="characters_container">
        @foreach ($chars as $char)
        <a href="/character/{{$char->char}}" class="character_link">
            <h1 class="character">{{$char->char}}</h1>
            <h2 class="pinyin">{{$char->pinyin}}</h2>
        </a>
        @endforeach
    </div>

    {{ $chars->links() }}

    @include('partials.footer')
</div>

<style>
    .character_link,
    .character_link:visited {
        background-color: white;
        text-align: center;
        border: 2px #0000004d solid;
        border-radius: 10px;
        transition: 0.2s;
        color: #222222;
        text-decoration: none;
        width: 100%;
        max-width: 200px;
        margin: 1rem auto;

    }

    .character_link:hover {
        background-color: #cd223d;
        color: white;
    }

    .characters_container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
        grid-gap: 1rem;
    }

    .pinyin{
        padding: 0.25em 1em 1em;
    }

</style>
