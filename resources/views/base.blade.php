<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+TC|Noto+Sans+SC|Open+Sans|ZCOOL+XiaoWei&display=swap" rel="stylesheet">
    <title>HanziBase</title>
</head>

<!--Body-->

<body>
    <div class="topbar">
        <img src="/logo.png" alt="hanzibase">
        <div class="lang">
            <a href="#" style="font-family: Noto Sans SC;">中文</a>
        </div>
    </div>

    <div class="landing-area">
        <div class="landing-area-text">
            <h1>HanziBase</h1>
            <p><span style="font-family: Open Sans;">HanziBase</span> 是免费网站。这网站用于学习汉字。使用搜索框查找字符下面</p>
        </div>
        
    </div>

    <div class="main-filler">
        main section
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
    background: linear-gradient(#E82048,#952828);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #2e131394;
    }

    .topbar img {
        padding: 1em;
        height: 4em;
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
        min-height: 80vh;
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

    .landing-area-text{
        margin: 3em;
    }
    .landing-area-text h1{
        margin-bottom: 1rem; 
        font-size: 7em;
        font-weight: 100;
        font-family: 'Open Sans';
    }
    .landing-area-text p{
        margin: 0;
        font-size: 1.5em;
        font-family: 'Noto Sans SC';
    }

    .main-filler{
        min-height: 100vh;
    }
    

</style>

<!--Script-->
<script>


</script>

</html>
