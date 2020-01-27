@include('partials.topbar')

@php
$charToDisplay = $char->simp_char ? $char->simp_char : $char->trad_char;
@endphp

<div class="main">
    <div class="character_section">
        <h1 class="character">{{$charToDisplay}}</h1>
        <h2 class="translations">{{$char->translations}}</h2>
        <p class="pinyin">{{$char->pinyin}}</p>
        <h1>{{$char->frequencyTitle}} ({{$char->freq}})</h1>

        <div id="character-target-div"></div>
        <button id="animate-button">Animate</button> 
    </div>

    @php
   
echo ("<script>
    var writer = HanziWriter.create('character-target-div', '$charToDisplay', {
        width: 100,
        height: 100,
        padding: 5,
        showOutline: true
    });

    document.getElementById('animate-button').addEventListener('click', function () {
        writer.animateCharacter();
    });

</script>");

@endphp


    @include('partials.footer')
</div>



<style>
    .character_section {
        background-color: #ffffff;
        min-height: 70vh;
        padding: 2em 3em;
        border: 2px solid #80808047;
        border-radius: 10px;
    }

    .character {
        font-size: 10em;
    }

</style>
