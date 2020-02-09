@include('partials.topbar')

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

@endphp

<div class="main">
    @if ($newCharAdded == true)
        <div class="newCharAdded">This character has now been added to hanzibase!</div>
    @endif
   
    <div class="character_section">
        <div class="left_section">
            <div id="character-target-div"></div>
            <div class="freq freq-{{$char->freq}}">{{$char->frequencyTitle}}</div>
        </div>
        <div class="details_section">

            <div class="details_top">
                <div class="details_title">
                    <h1 class="character">{{$char->char}} {{$char->pinyin}}</h1>
                    @if ($hasSimplified)
            <span class="character_type"> (Traditional)</span>
            
            @elseif ($hasTraditional)
                <span class="character_type"> (Simplified)</span>
            @endif
                </div>
                
                <div class="similar_characters">
                    @if ($hasSimplified)
                    <a href="/character/{{$char->simp_char}}">
                        <h2>Simplifed {{$char->simp_char}}</h2>
                    </a>
                    @elseif ($hasTraditional)
                    @foreach ($trads as $trad)
                    <a href="/character/{{$trad}}">
                        <h2>Traditional {{$trad}}</h2>
                    </a>
                    @endforeach
                    @endif
                </div>


            
            
            <div class="translations">
            <h3 class="translation_title">TRANSLATION</h3>
            @php
                $translationsArray = explode(";", $char->translations);
            @endphp
            <ul class="translation_text">
                @foreach ($translationsArray as $translation)
                <li>{{$translation}}</li>
                @endforeach
            </ul>
           
    

        </div>
            <br>
            @if (!empty($char->heisig_keyword) && !empty($char->heisig_number))
            <h3 class="heisig_title">HEISIG</h3>
            <p class="heisig">{{$char->heisig_keyword}} : {{$char->heisig_number}}</p>
            @endif
            <hr>


        </div>

        <div>
            <h3>RADICAL</h3>
            <p>{{$char->radical}}</p>
            </div>

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
            showOutline: true
        });


        writer.loopCharacterAnimation();

    </script>




    @include('partials.footer')
</div>



<style>
    .newCharAdded {
        padding: 1em;
        background-color: #b8f28b;
        color: #468400;
        border: 1px solid #dfdfdf;
        margin: 0em 5em 1em;
        text-align: center;
        font-weight: 700;
        font-size: 1.25em;
        border-radius: 10px;
    }

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
        width: 27%;
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
</style>
