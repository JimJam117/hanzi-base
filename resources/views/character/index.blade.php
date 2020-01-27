@include('partials.topbar')

<div class="main">
    @php
        
        dd($ccdb);
    @endphp
    <div class="characters_container">
        @foreach ($chars as $char)
        <a href="/character/{{$char->simp_char ? $char->simp_char : $char->trad_char}}" class="character_link">
            <h1 class="character">{{$char->simp_char ? $char->simp_char : $char->trad_char}}</h1>
            <h2 class="translations">{{$char->translations}}</h2>
            <p class="pinyin">{{$char->pinyin}}</p>
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
        padding: 1em;
        border: 2px #0000004d solid;
        margin: 2em;
        border-radius: 10px;
        transition: 0.2s;
        color: #222222;
        text-decoration: none;
    }

    .character_link:hover {
        background-color: #cd223d;
        color: white;
    }

    .characters_container {
        display: grid;
        grid-template-columns: 25% 25% 25% 25%;
    }

</style>
