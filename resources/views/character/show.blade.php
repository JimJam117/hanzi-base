@include('partials.topbar')

@php
$hasSimplifed = $char->simp_char ? true : false;
$hasTraditional = $char->trad_char ? true : false;

if ($hasTraditional) {
    $trads = $char->trad_char;
    $trads = explode(",", $trads);
}
@endphp

<div class="main">
    <div class="character_section">
        <div class="left_section">
            <div id="character-target-div"></div>
            <div class="freq freq-{{$char->freq}}">{{$char->frequencyTitle}}</div>
        </div>  
        <div class="details_section">

            <div class="details_title">
                <h1 class="character">{{$char->char}} {{$char->pinyin}}</h1>
                @if ($hasSimplifed || $hasTraditional)
                <div class="similar_characters">
                    @if ($hasSimplifed)
                        <a href="#"><h2>Simplifed {{$char->simp_char}}</h2></a>
                    @endif

                    @if ($hasTraditional)
                        @foreach ($trads as $trad)
                        <a href="#"><h2>Traditional {{$trad}}</h2></a>
                        @endforeach
                    @endif
                </div>  
                @endif
                
                    
                
                
            </div>

            <h3 class="translation_title">TRANSLATION</h3>
            <p class="translations">{{$char->translations}}</p>
            <br>
            <h3 class="heisig_title">HEISIG</h3>
            <p class="heisig">{{$char->heisig_keyword}} : {{$char->heisig_number}}</p>
        <hr>
        
        
        </div>
        



        
    </div>



    <script>
        var writer = HanziWriter.create('character-target-div', 
        {!!"'" . $char->char . "'"!!}, {
            width: 300,
            height: 300,
            padding: 15,
            strokeAnimationSpeed: 1, // 5x normal speed
            delayBetweenStrokes: 50, // milliseconds
            strokeColor: '#c82929', // blue
            delayBetweenLoops: 3000,
            showOutline: true
        });

       
            writer.loopCharacterAnimation();
        

    </script>




    @include('partials.footer')
</div>



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

    .details_section{
        width:100%;
    }

    .details_title{
        display: flex;
        justify-content: space-between;
    }

    .details_title a{
        color: #227a7a;
        text-decoration: none;
    }
    
    .left_section{
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

</style>
