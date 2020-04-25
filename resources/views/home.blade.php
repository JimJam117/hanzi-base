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
                <input id="search" type="text" placeholder="Jia, 家, home..." name="query" autocomplete="off" maxlength="32" required="required">
                <label style="display: none;" for="search">Search field</label>
                <button aria-label="Search Button" type="submit"><i class="fa fa-search"></i></button>
            </form>
            <div class="search-msg">Search using Pinyin, unicode Chinese characters or translations</div>
        </div>       
    </div>
</div>
@endsection
    
@section('main')
<div class="main-section">
    <div class="nihao">
        <h2 class="stat-title"><span class='red'>{{$charCount}}</span> Characters on HanziBase</h2>
        <div id="writer"></div>
    </div>
    <div class="title-section">
        <h2 class="stat-title"><span class='red'>{{$charCount}}</span> Characters on HanziBase</h2>
        <p>HanziBase is a free resource for learning Chinese characters, both simplifed and traditional. You can search by character, pinyin, translation or Heisig keyword (if you are using <em>Remembering the Hanzi</em>) to find a character's details.</p>
        <br>
        <p></p>
    </div>
</div>

<div class="main-section">
    <div class="title-section">
        <h2 class="how-it-works-title red">How It Works</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt fugiat impedit nemo eveniet mollitia eos alias sed, hic expedita repudiandae sunt ipsa laudantium accusamus soluta.</p>
    </div>

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


{{-- 
<div class="sub-features">
        <h2 class="feature-title">Translations</h2>
        <h2 class="feature-title">Stroke Order</h2>
        <h2 class="feature-title">Generated Content</h2>

        <i class="fas fa-language feature-icon"></i>
    <i class="fas fa-pen-fancy feature-icon"></i>
    <i class="fas fa-cloud feature-icon"></i>

        <p class="feature-text">
            Some characters may have multiple translations, and they will all be listed on the character page. Heisig keywords are listed as seperate from API generated translations.
        </p>
      
        <p class="feature-text">
            The amazing <a href="https://hanziwriter.org/">HanziWriter javascript library</a> is used to render all characters that support it, providing the stroke order of the character. Characters will also have information about the total number of strokes, as well as the radical.
        </p>
    
        <p class="feature-text">
            If a character is not within the websites database, it will attempt to find information about it through the <a href="http://ccdb.hemiola.com/">CCDB API</a> as well as the <a href="https://glosbe.com/a-api">Glosbe Translation API</a>. This content is then added to the local database for future use.
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
        <h2>API Generated Content</h2>
        <i class="fas fa-cloud"></i>
        <p>
            If a character is not within the websites database, it will attempt to find information about it through the <a href="http://ccdb.hemiola.com/">CCDB API</a> as well as the <a href="https://glosbe.com/a-api">Glosbe Translation API</a>. This content is then added to the local database for future use.
        </p>
    </div>
</div>

{{-- <hr>
<div class="main-section">
    <h1>Title</h1>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque velit ea quo blanditiis tenetur, incidunt rem magni expedita laudantium, libero aliquid nemo aspernatur, deserunt quod laborum perspiciatis sapiente officia fugit recusandae eum porro ut illum. Veritatis doloremque vel reiciendis a enim itaque aut nisi aspernatur earum rerum. Neque exercitationem voluptates sed esse fuga modi cum non dicta assumenda saepe, delectus, praesentium, nemo expedita impedit obcaecati dignissimos reiciendis dolor eos iusto rerum soluta nihil sunt ex?</p>
</div>
 --}} 





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

    /*landing*/
    .landing-area {
        
        min-height: 70vh;
        padding-bottom: 10vh;
        text-align: center;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .search-container {
        width: 100%;
    }

    .search-container form {
        display: flex;
        justify-content: center;
    }

    .search-container input {
        padding: 1em;
        margin: 0;
        border: 0;
        border-radius: 1em 0 0 0;
        font-size: 1.25em;
        width: 100%;
        outline: none;
        opacity: 0.9;
    }

    .search-container input:focus{
        background-color: #fff;
        opacity: 1;
    }

    .search-container button {
        padding: 1em;
        margin: 0;
        border: 0;
        float: right;
        border-radius: 0 1em 1em 0;
        color: #fff;
        background-color: #B5183A;
        font-size: 1.25em;

    }

    .search-msg {
        background-color: #373737db;
        width: 40%;
        padding: 0.5em 1em;
        border-radius: 0 0 1em 1em;
        text-align: initial;
    }

   

    .landing-area-content {
        max-width: 650px;
        width: 100%;
        margin-bottom: 4em;
    }

    .landing-area-content h1 {
        margin-bottom: 1rem;
        font-size: 7em;
        font-weight: 100;
        font-family: 'Open Sans';
    }

    .landing-area-content p {
        margin-bottom: 1em;
        font-size: 1.5em;
        font-weight: 300;
    }

    /*Character Stat Section*/
    .main-section{
        display: flex;
        flex-direction: row;
        max-width: 1200px;
    }
    .stat-title{
        font-weight: 100;
        font-size: 2em;
    }
    .how-it-works-title{
        font-weight: 100;
        font-size: 2em;
    }
    .red{
        color: #b5183a;
        font-size: 4rem;
    }

    .nihao{
        display: flex;
        flex-direction: column;
        align-items: center;
        
    }
    .nihao h2{
        display: none;
    }
    .nihao div{  
        height: 300px;
        width: 300px;
        padding: 5px;  
    }

    .title-section {
        align-self: flex-start;
        padding: 0 75px 75px 75px;
        max-width: 60%;
    }

    /* Sub-feature */

    .sub-features-small {
        display: none;
    }

    .sub-features{
        padding-top: 3em;
        display: grid;
        grid-template-columns: 25% 25% 25%;
        width: 100%;
        text-align: center;
        align-items: baseline;
        justify-content: space-evenly;
    }
    .sub-features a, .sub-features-small a{
        color: #20897f;
    text-decoration: none;
    }
    .sub-features i, .sub-features-small i{
        color: #333;
    }

    .feature-icon{
        padding: 0.5em 0;
        font-size: 5em;
    }
    .feature-title{
        padding: 0 0.25em;
    }
    .feature-text{
        padding: 0 0.25em;
        text-align: justify
    }

    .feature{
        padding: 2em;
        text-align: center
    }
    .feature i{
        font-size: 5em;
        padding: 0.5em;
    }
    .feature h2{
        font-size: 2em;
    }

    @media screen and (max-width: 970px) {
        .character_link_example{
            font-size: 1rem;
            min-width: 215px;
            min-height: 215px;
        }
        .title-section{
            max-width: 70%;
            padding: 0 50px 50px 50px; 
        }
        .red{
            font-size: 3rem;
        }
    }

    @media screen and (max-width: 700px) {
        .title-section{
            max-width: 100%;
            padding: 0; 
            padding-bottom: 1em; 
        }
        .landing-area-content h1 {
            font-size: 20vw;
        }

        .main-section{
            flex-direction: column;
            text-align: center;
        }
        .nihao .stat-title{
            display: initial;
            font-size: 2rem;
        }
        .red{
            font-size: 2rem;
        }

        .title-section {
            padding-left: 0px;
        }
        
        .stat-title{
            display: none;
        }

        .sub-features{
            display: none;
        }
        .sub-features-small{
            display: flex;
            flex-direction: column;
        }
        .search-container input{
            width: 80%;
            border-bottom-left-radius: 10px;
        }
        .search-msg{
            display: none;
        }
    }

    @media screen and (max-width: 360px) {
        .character_link_example{
            width: 125px;
            height: 125px;
            font-size: 0.5rem;
            min-width: 0;
            min-height: 0;
        }
        .character_link_example .bottom-details{
            font-size: inherit;
        }
    }

</style>

<!--Script-->



<script defer async>
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

