@include('partials.topbar')

@php

        

     
@endphp

<div class="main">
    @php
        
        //dd($ccdb);
    @endphp
    <div class="characters_container">
        @foreach ($chars as $char)

        {{-- Check if char has trad/simp --}}
        @php
            $hasSimplified = $char->simp_char ? true : false;
            $hasTraditional = $char->trad_char ? true : false;

            if ($hasTraditional) {
            $trads = $char->trad_char;
            $trads = explode(",", $trads);
            }

            // if has same trad as char, then does not have a traditional version
            foreach ($trads as $trad) {
                if ($trad == $char->char){
                    $hasTraditional = false;
                }
            }
            // if the char is the same as the simp_char, then does not have simplified version
            if($char->char == $char->simp_char) {
                $hasSimplified = false;
            }

            // only get two or three words from translations
            $s = substr($char->translations, 0, 20);
            $translations = substr($s, 0, strrpos($s, ' '));

            // if there is a last letter that is a ; , . remove the last letter
            $lastLetter = substr($translations, -1);
            if ($lastLetter == ";" || $lastLetter == "." || $lastLetter == ",") {
                $translations = substr($translations, 0, -1);
            }

            // add some dots to the end
            $translations .= "..";

        @endphp


        {{-- The character link --}}
        <a href="/character/{{$char->char}}" class="character_link">

            {{-- Top details, radical and trad/simp --}}
            <div class="top-details"> 
                <p>{{ $char->radical }}</p>
                <p>
                    @if ($hasSimplified) Trad
                    @elseif ($hasTraditional) Simp
                    @endif
                </p>
            </div>

            <h2 class="character">{{$char->char}}</h2>

            {{-- Translations or heisig --}}
            <h3>
                @if ($char->heisig_keyword)
                H {{ $char->heisig_keyword }} ({{ $char->heisig_number }})
                @else
                    {{ $translations }}
                @endif
            </h3>
            
            {{-- Pinyin --}}
            <p class="pinyin">{{ $char->pinyin }}</p>
        </a>
        @endforeach
    </div>

    {{-- paginator links --}}
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
        max-width: 215px;
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
