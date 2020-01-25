<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+TC|Noto+Sans+SC|Open+Sans|ZCOOL+XiaoWei&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" media="all" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/hanzi-writer@2.2/dist/hanzi-writer.min.js"></script>

    <title>HanziBase</title>
    <link rel="icon" href="/icon.ico">
</head>

<!--Body-->

<body>
    <div class="topbar">
        <div class="logo-search-container">
            <img src="/logo.png" alt="hanzibase">

            <div class="search-container search-container-top">
                <form action="/action_page.php">
                    <input type="text" placeholder="Search.." name="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>

        <div class="lang">
            <a href="#" style="font-family: Noto Sans SC;">中文</a>
        </div>
    </div>

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

        <h1>Test Animation</h1>
        <div id="character-target-div"></div>
        <button id="animate-button">Animate</button>
        
        @php
         $faker = Faker\Factory::create('zh_CN');
         $name = $faker->lastName;
        echo ("<script> 
            var writer = HanziWriter.create('character-target-div', '$name', {
            width: 100,
            height: 100,
            padding: 5,
            showOutline: true
          });

          document.getElementById('animate-button').addEventListener('click', function() {
  writer.animateCharacter();
});
              
            </script>");
  
        @endphp
        
        
      
        <hr>
        <footer>
            <a href="https://jsparrow.uk">HanziBase by James Sparrow</a>
            <a href=""></a>
            <a href="#">Back to top</a>
        </footer>

    </div>
</body>

<!--Style-->
<style>
    /*font-family: 'Open Sans', sans-serif;
    font-family: 'ZCOOL XiaoWei', serif;
    font-family: 'Noto Sans TC', sans-serif;*/

    * {
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Calibri, 'Trebuchet MS', sans-serif;
        margin: 0;
    }

    .topbar {
        width: 100%;
        background: linear-gradient(#E82048, #952828);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #2e131394;
        position: fixed;
    }

    .topbar img {
        padding: 1em;
        height: 4em;
    }

    .logo-search-container{
        display: flex;
    }

    .lang {
        padding: 1em;
        float: right;
    }

    .lang a {
        color: white;
        height: auto;
        font-size: 1.5em;
        padding: 0 2em;

        text-decoration: none;
    }

    .lang a:hover {
        color: black;
    }

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

    .search-container-top {
        width: 100%;
    margin: auto 1em auto 2em;
    }

    .search-container-top button {
        background-color: #ffffff00;
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

    /* Main Section */
    .main{
        padding: 3em 10em;
        background-image: -webkit-linear-gradient(60deg, #f8f8f8 50%, #ffffff 50%);
        min-height: 500px;
    }
    .main hr{
        border: 0;
        border-top: 1px solid #bebebe;
        margin: 4em;
    }
    .main-section{
        padding: 2em;
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

    /* Footer */

    footer{
        display: flex;
        justify-content: space-between;
    }

    .main a, .main a:visited{
        color: #20897f;
        text-decoration: none;
    }

</style>

<!--Script-->
<script>


</script>

</html>
