@extends('layouts.app')


{{-- Title --}}
@section('title')
Character "{{ $char->char }}"
@endsection


@section('main')
    
@php
$hasSimplified = $char->simp_char ? true : false;
$hasTraditional = $char->trad_char ? true : false;
$hasRadical = $char->radical ? true : false;
$hasSimplifiedRadical = ($char->simp_radical != $char->radical) ? true : false;

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
@endphp


    @if ($newCharAdded == true)
        <div class="newCharAdded">This character has now been added to hanzibase!</div>
    @endif
   
    <div class="character_section">
        <div class="left_section">
            <div id="character-target-div"></div>
            <h3>{{$char->stroke_count}} Strokes</h3>
            <div class="freq freq-{{$char->freq}}">{{$char->frequencyTitle}}</div>
        </div>
        <div class="details_section">

            <div class="details_top">
                <div class="details_title">
                    <h1 class="character">{{$char->char}} {{$char->pinyin}}</h1>
                    <h1 class="character_small">{{$char->char}}</h1>
                    <h1 class="pinyin_small">{{$char->pinyin}}</h1>
                    @if ($hasSimplified)
                    <span class="character_type"> (Traditional)</span>
                    <span class="character_type_small"> (Traditional)</span>
                    @elseif ($hasTraditional)
                        <span class="character_type_small"> (Simplified)</span>
                    @endif
                </div>
                
                <div class="similar_characters">
                    <h2>Links:</h2>
                    {{-- If the char has a radical --}}
                    @if($hasRadical)

                        <form method="post" action="/search">
                            @csrf
                            <input type="hidden" name="search" value="{{$char->radical}}">
                            <input type="hidden" name="radical" value="{{true}}">

                            <button type="submit" class="radical-link">
                                <h3>Radical {{$char->radical}}</h3>
                            </button>
                        </form>

                        {{-- If the char is simplified and has a simplified radical --}}
                        @if ($hasSimplifiedRadical)

                        <form method="post" action="/search">
                            @csrf
                            <input type="hidden" name="search" value="{{$char->simp_radical}}">
                            <input type="hidden" name="radical" value="{{true}}">
                            
                            <button type="submit" class="radical-link">
                                <h3>Simplifed Radical {{$char->simp_radical}}</h3>
                            </button>
                        </form>
                        @endif

                    @endif

                    @if ($hasSimplified)
                    <a href="/character/{{$char->simp_char}}">
                        <h3>Simplifed {{$char->simp_char}}</h3>
                    </a>
                    @elseif ($hasTraditional)
                    @foreach ($trads as $trad)
                    <a href="/character/{{$trad}}">
                        <h3>Traditional {{$trad}}</h3>
                    </a>
                    @endforeach
                    @endif
                </div>




            </div>
            
            <div class="translations">
            <h3 class="translation_title">TRANSLATION</h3>
            @if ($char->translations)
                @php
                    $translationsArray = explode(";", $char->translations);
                @endphp
                <ul class="translation_text">
                    @foreach ($translationsArray as $translation)
                    <li>{{$translation}}</li>
                    @endforeach
                </ul>
            @else
                <p>No translations found</p>
            @endif
            
           </div>

            <br>
            @if (!empty($char->heisig_keyword) && !empty($char->heisig_number))
            <h3 class="heisig_title">HEISIG</h3>
            <p class="heisig">{{$char->heisig_keyword}} : {{$char->heisig_number}}</p>
            @endif
            <hr>


        </div>





    </div>



    <script>
        
        
        var writer = HanziWriter.create('character-target-div',
            {!!"'" . $char->char . "'"!!}, 
            {
            width: 300,
            height: 300,
            padding: 15,
            strokeAnimationSpeed: 1, // 5x normal speed
            delayBetweenStrokes: 50, // milliseconds
            strokeColor: '#c82929', // red
            delayBetweenLoops: 3000,
            showOutline: true,

            // if loading the character with hanziWriter fails, just load the character as a string
            onLoadCharDataError: function(reason) {
                $( document ).ready(function() {
                    document.getElementById("character-target-div").innerHTML = "{{$char->char}}"; 
                });
            }

        });
        writer.loopCharacterAnimation();
    </script>




@endsection


@section('extra-scripts')
    


<style>
    .character_section {
        background-color: #ffffff;
        min-height: 70vh;
        padding: 2em 3em;
        border-bottom: 1px solid #80808047;
        border-right: 1px solid #80808047;
        border-radius: 10px;
        display: flex;
    }
    .character_type{
        margin: 1em;
    }

    .character_small, .character_type_small, .pinyin_small{
        display: none;
    }

    .details_section {
        width: 100%;
    }
    .details_top {
        display: flex;
        justify-content: space-between;
        
    }
    .details_title{
        display: inline-flex;
    align-items: baseline;
    }
    .details_top a {
        color: #227a7a;
        text-decoration: none;
    }
    .left_section {

        text-align: center;
    }
    .freq {
        margin: 1em;
        color: white;
        font-weight: 500;
        font-family: Open Sans;
        display: inline-block;
        padding: 7px 8px;
        background: linear-gradient(#d5d5d5, #898989);
        /*border: 2px solid #cecece6b;*/
    }
    .freq-1 {
        background: linear-gradient(#84d968, #28953a);
    }
    .freq-2 {
        background: linear-gradient(#68cfd9, #289595);
    }
    .freq-3 {
        background: linear-gradient(#8689f8, #2328c0);
    }
    .freq-4 {
        background: linear-gradient(#fab98c, #ca7538);
    }
    .freq-5 {
        background: linear-gradient(#fa8c8c, #ca3838);
    }
    .translations{
        margin: 1em;
    }
    .translation_title{
        font-size: 2em;
        
    }
    
    .translation_text{
        font-size: 1.25em;
        list-style: lower-roman;
        list-style-position: inside;
    }
    .translation_text li{
        margin-top: 1em;
    }

    #character-target-div{
        font-family: "ZCOOL XiaoWei", "Noto Serif SC", "Noto Serif TC";
        font-size: 15vw;
        font-weight: lighter;
        color: #c82929;
    }

    .radical-link {
        color: #c82929;
        background: none;
        border: none;
        cursor: pointer;
    }

    @media screen and (max-width: 1350px) {
        /* Do not display the large character stuff */
       .character, .character_type {
           display: none;
       }


       .character_small, .character_type_small, .pinyin_small{
           display: initial;
       }

       .character_small{
           font-size: 6rem;
       }

       
       .translation_title{
           font-size: 1.25rem;
       }
       .translation_text{
           font-size: 1rem;
       }

       /* Used to align the top details to the right and in column formation, instead of row */
       .details_top, .details_title{
        display: flex;
        flex-direction: column;
        text-align: right;
        align-items: end;
       }

       /* Pushes the character and pinyin to the left a little to be aligned with the links */
       .details_title{
        padding: 0 1em;
       }

       /* the links section styles */
       .similar_characters{
           margin: 1.5em;
       }
       .similar_characters form, .similar_characters a h3{
           margin-top: 1em; 
       }
    }

    @media screen and (max-width: 675px) {
        .character_section{
            flex-direction: column-reverse;
        }
        .details_top, .details_title{
            text-align: center;
            align-items: center;
        }
    }

</style>

{{-- Spesific styles for this page --}}
<style>
    @media screen and (max-width: 675px) {
        .main{
            padding: 1em 0;
        }
    }

</style>

@endsection