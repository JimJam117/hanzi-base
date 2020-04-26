@extends('layouts.app')

{{-- Title --}}
@section('title')
HanziBase
@endsection


@section('landing')
<div class="landing-area">
    <div class="landing-area-content">
        <h1>HanziBase</h1>
        <p>Free resource for learning Chinese characters</p>
        <div class="search-container">
            <form action="/search" method="POST">
                @csrf
                <input onfocus="toggleSearchMsgVisablityOn" onblur="toggleSearchMsgVisablityOff" id="search" type="text" placeholder="Jia, 家, home..." name="query" autocomplete="off" maxlength="32" required="required">
                <label style="display: none;" for="search">Search field</label>
                <button aria-label="Search Button" type="submit"><i class="fa fa-search"></i></button>
            </form>
            <div id="searchMsg" class="search-msg">Search using Pinyin, unicode Chinese characters or translations</div>
        </div>       
    </div>
</div>
@endsection
    
@section('main')
<div class="home-section">
    <div class="nihao">
        <h2 class="stat-title"><span class='red'>{{$charCount}}</span> Characters on HanziBase</h2>
        <div id="writer"></div>
    </div>
    <div class="title-section">
        <h2 class="stat-title"><span class='red'>{{$charCount}}</span> Characters on HanziBase</h2>
        <p>HanziBase is a free resource for learning Chinese characters, both simplifed and traditional. You can search by character, pinyin, translation or Heisig keyword (if you are using <em>Remembering the Hanzi</em>) to find a character's details.</p>
    </div>
</div>

<div class="home-section">
    <div class="title-section">
        <h2 class="how-it-works-title red">How It Works</h2>
        <p>When you search for a character by typing in at least one unicode Chinese Character into the search, Hanzibase will attempt to find it within the local database. If a character is not within the website's database, it will attempt to find information about it through the <a href="http://ccdb.hemiola.com/">CCDB API</a> as well as the <a href="https://glosbe.com/a-api">Glosbe Translation API</a>. This content is then added to the local database for future use.</p>
    </div>
    <i class="fas fa-cloud-download-alt feature-icon-large"></i>
</div>


<div class="home-section reverse-section">
    <div class="title-section">
        <h2 class="how-it-works-title red">Easy to Use</h2>
        <p>Hanzibase has filters you can apply when searching to make finding the character you're looking for easier. Each character's radical will be in the top left corner, and pinyin, translations and heisig data is underneath the character.</p> 
    </div>

    {{-- Example character Link --}}
    <div class="character_link_example">
        <div class="details top-details ">Radical</div>
        <h2 class="character">字</h2>
        <p class="pinyin">pinyin</p>
        <h3>translations</h3>
        
        <div class="details bottom-details">
                <div>heisig keyword (heisig number)</div>
        </div>
    </div>
</div>



<h2 class="sub-features-title red">Features</h2>

<div class="sub-features">
        <h2 class="feature-title">Translations</h2>
        <h2 class="feature-title">Stroke Order</h2>
        <h2 class="feature-title">Radicals</h2>

        <i class="fas fa-language feature-icon"></i>
    <i class="fas fa-pen-fancy feature-icon"></i>
    <i class="fas fa-star feature-icon"></i>

        <p class="feature-text">
            Some characters may have multiple translations, and they will all be listed on the character page. Heisig keywords are listed as seperate from API generated translations.
        </p>
      
        <p class="feature-text">
            The amazing <a href="https://hanziwriter.org/">HanziWriter javascript library</a> is used to render all characters that support it, providing the stroke order of the character. Characters will also have information about the total number of strokes, as well as the radical.
        </p>
    
        <p class="feature-text">
            Every character either has a radical or is a radical. Some radicals will have both a simplifed and traditional variant, and the radicals conterpart will be displayed as a link on the character page if this is the case. You can filter in the search to show only radicals.
        </p>

</div>

<div class="sub-features-small">
    <div class="feature">
        <h2>Translations</h2>
        <i class="fas fa-language"></i>
        <p>
            Some characters may have multiple translations, and they will all be listed on the character page. Heisig keywords are listed as seperate from API generated translations.
        </p>
    </div>
    <div class="feature">
        <h2>Stroke Order</h2>
        </i><i class="fas fa-pen-fancy"></i>
        <p>
            The amazing <a href="https://hanziwriter.org/">HanziWriter javascript library</a> is used to render all characters that support it, providing the stroke order of the character. Characters will also have information about the total number of strokes, as well as the radical.
        </p>
    </div>
    <div class="feature">
        <h2>Radicals</h2>
        <i class="fas fa-star feature-icon"></i>
        <p>
            Every character either has a radical or is a radical. Some radicals will have both a simplifed and traditional variant, and the radicals conterpart will be displayed as a link on the character page if this is the case. You can filter in the search to show only radicals.
        </p>
    </div>
</div>

<div class="bottom-links-section">
    <a class="filters-button" href="/browse">Browse All Characters <i class="filter-arrow fas fa-arrow-circle-right text-red"></i></a>
    <a class="filters-button" href="/random">Random Character <i class="filter-arrow fas fa-arrow-circle-right text-red"></i></a>
</div>






<div id="top-of-site-pixel-anchor"></div>
@endsection
        
        
      
     

@section('extra-scripts')
    

<!--Style-->
<style>
    /**/
    #top-of-site-pixel-anchor {
        position: absolute;
        width: 1px;
        height: 1px;
        top: 500px;
        left: 0;
    }

    body{
        background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(12, 12, 12, 0.73)), url(bk.webp);
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
    }

</style>

<!--Script-->



<script defer async>
    function toggleSearchMsgVisablityOn() {
        (document.getElementById('searchMsg')).className = "searchMsg visable";
    }
    function toggleSearchMsgVisablityOff() {
        (document.getElementById('searchMsg')).className = "searchMsg";
    }


    var CurrentChar = "\u6211";

    var chars = [
        '的', // 1
        '一', // 2
        '是', // 3
        '不', // 4
        '了', // 5
        '人', // 6
        '我', // 7
        '在', // 8
        '有', // 9
        '他', // 10

        '这', // 11
        '为', // 12
        '之', // 13
        '大', // 14
        '来', // 15
        '以', // 16
        '个', // 17
        '中', // 18
        '上', // 19
        '们', // 20

        '到', // 21
        '说', // 22
        '国', // 23
        '和', // 24
        '地', // 25
        '也', // 26
        '子', // 27
        '时', // 28
        '道', // 29
        '出', // 30

    ]
    
    Array.prototype.random = function () {
        return this[Math.floor((Math.random()*this.length))];
    }

    window.onload = function() {
        var size = $('#writer').width();

        

        var writer = HanziWriter.create('writer', '好', {
            width: size,
            height: size,
            padding: 0,
            showCharacter: false,
            strokeAnimationSpeed: 1, // 5x normal speed
            delayBetweenStrokes: 1000, // milliseconds
            strokeColor: '#c82929', // red
            delayBetweenLoops: 1000,
            showOutline: false
        });

        function getNewChar() {
            // search the chars obj for current char
            var currentCharInCharsObj = chars.find(x => x.char === CurrentChar);
            var newChar;

            while(true) {
                newChar = chars.random();
                console.log(newChar);

                if(newChar != currentCharInCharsObj){
                    break;
                }
            }
            
            
            SetChar(newChar);

        }
        getNewChar();


        function charLoop() {
            writer.animateCharacter({ onComplete: function() { 
                setTimeout(function() {
                    getNewChar();
                }, 5000);
         }});
        }

        function SetChar(char) {
            CurrentChar = char;
            writer.setCharacter(char);
            charLoop();
        }
        
        
    };

</script>

<script defer>
    if (
        "IntersectionObserver" in window &&
        "IntersectionObserverEntry" in window &&
        "intersectionRatio" in window.IntersectionObserverEntry.prototype
    ) 
    {
        let observer = new IntersectionObserver(entries => {
          if (entries[0].boundingClientRect.y < 0) {
            document.body.classList.add("topbar-not-at-top");
          } else {
            document.body.classList.remove("topbar-not-at-top");
          }
    });
        observer.observe(document.querySelector("#top-of-site-pixel-anchor"));
    }
</script>

@endsection

