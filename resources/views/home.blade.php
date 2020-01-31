@include('partials.topbar')

    <div class="landing-area">
        <div class="landing-area-text">
            <h1>HanziBase</h1>
            <p><span style="font-family: Open Sans;">HanziBase</span> 是免费网站。这网站用于学习汉字。使用搜索框查找字符下面</p>
        </div>
        <div class="search-container">
            <form action="/action_page.php">
                <input type="text" placeholder="Search.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>

    </div>

    <div class="main">
        <div class="main-section stat-section">
            <div class="nihao">
                <div id="writer"></div>
            </div>
            <div class="stat-title-section">
                <h2 class="stat-title"><span class='red'>{{$charCount}}</span> Characters on HanziBase</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis esse temporibus voluptates est debitis accusantium ea veniam! Est, consequuntur debitis.</p>
            </div>


        </div>
        <hr>

        <div class="sub-features">
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
                <h2>Lessons</h2>
                <i class="fas fa-graduation-cap"></i>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde nisi ad deserunt quibusdam ipsum ducimus blanditiis ab, illo rem repellat?</p>
            </div>
        </div>
        <hr>
        <div class="main-section">
            <h1>Title</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque velit ea quo blanditiis tenetur, incidunt rem magni expedita laudantium, libero aliquid nemo aspernatur, deserunt quod laborum perspiciatis sapiente officia fugit recusandae eum porro ut illum. Veritatis doloremque vel reiciendis a enim itaque aut nisi aspernatur earum rerum. Neque exercitationem voluptates sed esse fuga modi cum non dicta assumenda saepe, delectus, praesentium, nemo expedita impedit obcaecati dignissimos reiciendis dolor eos iusto rerum soluta nihil sunt ex?</p>
        </div>


        

        
        
        
        
      
        @include('partials.footer')

    </div>
</body>

<!--Style-->
<style>

    /*landing*/
    .landing-area {
        background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(12, 12, 12, 0.73)), url(bk.jpg);
        min-height: 100vh;
        text-align: center;
        color: white;
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .search-container {
        width: 50%;
    }

    .search-container form {
        display: flex;
    }

    .search-container input {
        padding: 1em;
        margin: 0;
        border: 0;
        border-radius: 1em 0 0 1em;
        font-size: 1.25em;
        width: 100%;
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

   

    .landing-area-text {
        margin: 3em;
    }

    .landing-area-text h1 {
        margin-bottom: 1rem;
        font-size: 7em;
        font-weight: 100;
        font-family: 'Open Sans';
    }

    .landing-area-text p {
        margin: 0;
        font-size: 1.5em;
        font-family: 'Noto Sans SC';
    }

    /*Character Stat Section*/
    .stat-section{
        display: flex;
        
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
        width: 50%;
        margin: auto;
    }
    .nihao div{  
        height: 300px;
        width: 300px;
        padding: 5px;  
    }


    /* Sub-feature */
    .sub-features{
        display: flex;
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

    

</style>

<!--Script-->

@php
    echo("<script>
        var chars = $chars;
        </script>");
    
@endphp

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

</html>
