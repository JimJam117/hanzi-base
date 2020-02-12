@include('partials.topbar')

<div class="main">
   @if ($input_characters_are_hanzi)
        <div class="newCharAdded">New characters have now been added to hanzibase!</div>
   @endif

    <h1>Results for "{{$search}}"</h1>
    <div class="characters_container">
        @foreach ($results as $char)
        <a href="/character/{{$char->char}}" class="character_link">
            <h1 class="character">{{$char->char}}</h1>
            <h2 class="pinyin">{{$char->pinyin}}</h2>
        </a>
        @endforeach
    </div>

    {{ $results->links() }}

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

    .pinyin{
        padding: 0.25em 1em 1em;
    }

</style>
