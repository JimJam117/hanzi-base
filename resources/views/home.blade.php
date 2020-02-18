@extends('layouts.app')

@section('landing')
<div class="landing-area">
    <div class="landing-area-content">
        <h1>HanziBase</h1>
        <p>Free resource for learning Chinese characters</p>
        <div class="search-container">
            <form action="/search" method="POST">
                @csrf
                <input type="text" placeholder="Search.." name="query" autocomplete="off">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>       
    </div>
</div>
@endsection
    
@section('main')
<div class="main-section stat-section">
    <div class="nihao">
        <h2 class="stat-title"><span class='red'>{{$charCount}}</span> Characters on HanziBase</h2>
        <div id="writer"></div>
    </div>
    <div class="stat-title-section">
        <h2 class="stat-title"><span class='red'>{{$charCount}}</span> Characters on HanziBase</h2>
        <p>HanziBase is a free resource for learning Chinese characters, both simplifed and traditional. You can search by character, pinyin, translation or Heisig keyword (if you are using <em>Remembering the Hanzi</em>) to find a character's details.</p>
        <br>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis esse temporibus voluptates est debitis accusantium ea veniam! Est, consequuntur debitis.</p>
    </div>


</div>
<hr>

<div class="sub-features">
    
        <h2 class="feature-title">Translations</h2>
        <h2 class="feature-title">Stroke Order</h2>
        <h2 class="feature-title">API Generated Content</h2>

        <i class="fas fa-language feature-icon"></i>
    <i class="fas fa-pen-fancy feature-icon"></i>
    <i class="fas fa-cloud feature-icon"></i>

        <p class="feature-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde nisi ad deserunt quibusdam ipsum ducimus blanditiis ab, illo rem repellat?</p>
    
    
        
        
        <p class="feature-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde nisi ad deserunt quibusdam ipsum ducimus blanditiis ab, illo rem repellat?</p>
    
   
        
        
        <p class="feature-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde nisi ad deserunt quibusdam ipsum ducimus blanditiis ab, illo rem repellat?</p>

</div>

<div class="sub-features-small">
    <div class="feature">
        <h2>Translations</h2>
        <i class="fas fa-language"></i>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde nisi ad deserunt quibusdam ipsum ducimus blanditiis ab, illo rem repellat?</p>
    </div>
    <div class="feature">
        <h2>Stroke Order</h2>
        </i><i class="fas fa-pen-fancy"></i>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde nisi ad deserunt quibusdam ipsum ducimus blanditiis ab, illo rem repellat?</p>
    </div>
    <div class="feature">
        <h2>API Generated Content</h2>
        <i class="fas fa-cloud"></i>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde nisi ad deserunt quibusdam ipsum ducimus blanditiis ab, illo rem repellat?</p>
    </div>
</div>

<hr>
<div class="main-section">
    <h1>Title</h1>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque velit ea quo blanditiis tenetur, incidunt rem magni expedita laudantium, libero aliquid nemo aspernatur, deserunt quod laborum perspiciatis sapiente officia fugit recusandae eum porro ut illum. Veritatis doloremque vel reiciendis a enim itaque aut nisi aspernatur earum rerum. Neque exercitationem voluptates sed esse fuga modi cum non dicta assumenda saepe, delectus, praesentium, nemo expedita impedit obcaecati dignissimos reiciendis dolor eos iusto rerum soluta nihil sunt ex?</p>
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
        background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(12, 12, 12, 0.73)), url(bk.jpg);
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
        border-radius: 1em 0 0 1em;
        font-size: 1.25em;
        width: 80%;
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
    .stat-section{
        display: flex;
        flex-direction: row;
        max-width: 1200px;
    }
    .stat-title{
        font-weight: 100;
        font-size: 2em;
    }
    .red{
        color: #b5183a;
        font-size: 3em;
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

    .stat-title-section {
        padding-left: 75px;
    }

    /* Sub-feature */
    .sub-features-small {
        display: none;
    }

    .sub-features{
        display: grid;
        grid-template-columns: 33% 33% 33%;
        width: 100%;
        text-align: center;
    }
    .feature-icon{
        padding: 0.5em 0;
        font-size: 5em;
    }
    .feature-title, .feature-text{
        padding: 0 0.25em;
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

    @media screen and (max-width: 700px) {
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

        .stat-title-section {
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
    }

</style>

<!--Script-->



<script>
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

<script>
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

