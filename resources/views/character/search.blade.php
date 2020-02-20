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
            " have now been added to hanzibase!</div>   
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
    @endif
    <div class="characters_container">
        
        @foreach ($results as $char)

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

    {{ $results->links() }}



@endsection
