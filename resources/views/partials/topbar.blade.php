<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+TC|Noto+Sans+SC|Open+Sans|ZCOOL+XiaoWei|Noto+Serif+SC:200,400|Noto+Serif+TC:200,400&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" media="all" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/hanzi-writer@2.2/dist/hanzi-writer.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script type="text/javascript">
        function burgerMenuShowHide() {
            // show/hide menu
            // var ico = document.getElementById("bMenuButton");
            // var text = document.getElementById("bMenuText");

            // var Main = document.getElementById("mainContent");
            // var Smenu = document.getElementById("sMenu");
            // var SmenuClear = document.getElementById("sMenuClear");

            // if (ico.className === "fas fa-angle-left") {
            // ico.className = "fas fa-angle-right";
            // text.className = "bMenuTextShowMessage";

            // Main.className += " main_content_full";


            // sMenu.className += " top_band_closed";
            // sMenuClear.className += " top_band_fix_closed";
            // }
            // // open
            // else {
            // ico.className = "fas fa-angle-left";
            // text.className = "bMenuTextHideMessage";

            // Main.className = "main_content";
            // sMenu.className = "top_band";
            // sMenuClear.className = "top_band_fix";

            var button = document.getElementById("bMenuButton");
            var bMenu = document.getElementById("bMenu");

            // open
            if(bMenu.className === "bMenu bMenuClosed") {
                bMenu.className = "bMenu";
                
                button.innerHTML = '<i class="fas fa-times"></i>';
                console.log("open");
            }

            // close
            else {
                bMenu.className = "bMenu bMenuClosed";

                button.innerHTML = '<i class="fas fa-bars"></i>';
                console.log("close");
            }

            }
        
  </script>

    <title>HanziBase</title>
    <link rel="icon" href="/icon.ico">
    <link rel="stylesheet" href="/css/styles.css">

    <style>
    .topbar {
        width: 100%;
        background: none;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: none;
        position: fixed;
        z-index: 5;
    }
    

    .topbar img {
        padding: 1em;
        height: 4em;
    }

    .topbar-clear{
        height: 100px;
    }

    .logo-search-container{
        display: flex;
    }
    .logo-search-container a{
        display: flex;
        color: white;
        align-items: center;
        text-decoration: none;
        font-family: inherit;
        font-weight: 700;
        padding-right: 1em;
    }
    .logo-search-container a span{
        font-size: 1.75em;
        font-weight: 500;
        font-family: 'open sans';
    }
    

    .lang {
        padding: 1em;
        float: right;
    }

    .lang a {
        color: white;
        height: auto;
        font-size: 1.5em;
        padding: 0 1em;

        text-decoration: none;
    }

    .lang a:hover {
        color: black;
    }

    .hamburger-button{
        display: none;
        color: white;
        background: none;
        border: none;
        padding: 1em;
        font-size: 2rem;
        
    }

    @media screen and (max-width: 925px) {
        .lang {
            display : none;
        }
        .logo-search-container{
            width: 100%;
        }
        .logo-search-container a span {
            display: none;
        }
        .hamburger-button{
            display: inherit;
        }
    }


    .bMenu{
        position: fixed;
        height: 100%;
        background-color:#555555e6;
        top: 0;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        padding-top: 2em;
    }

    .bMenu-container{
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .bMenu a{
        color: white;
        text-decoration: none;
         padding: 0.75em; 
        font-size: 1.5rem;
    }
    .bMenu-bottom-link a{
        color: cyan;
        font-size: 1rem;
        position: absolute;
        transform: translateX(-50%);
        bottom: 2em;
    }

    .bMenuClosed{
        display: none;
    }

    @media screen and (min-width: 925px) {
        .bMenu{
            display: none;
        }
    }

    .search-container-top {
        width: 100%;
        margin: auto 1em;
        display: none;
    }
    

    .search-container-top form {
        display: flex;
        justify-content: center;
    }

    .search-container-top input {
        padding: 0.5em 1em;
        margin: 0;
        border: 0;
        border-radius: 1em 0 0 1em;
        font-size: 1.25em;
        width: 100%;
        outline: none;
    }


    .search-container-top button {
        padding: 0.5em 1em;
        margin: 0;
        border: 0;
        float: right;
        border-radius: 0 1em 1em 0;
        color: #fff;
        font-size: 1.25em;
        background-color: #ffffff29;
    }


    @media screen and (max-width: 625px) {
        .search-container-top {
            margin: auto;
        }
        .logo-search-container a{
            padding-right: none;
        }
        .search-container-top input{
            width: 100px;
        }
        .topbar img {
            padding: 1em 0 1em 1em;
        }
        .search-container-top button{
            display: none;
        }
        .search-container-top input{
            border-radius: 1em;
            width: 50%;
        }
    }

    </style>

    @isset ($charCount)
        <style>
        .topbar-not-at-top .search-container-top{
            display: initial;
        }
        .topbar-not-at-top .topbar {
            background: #3f3f3f;
            border-bottom: 2px solid #2e131394;
        }
        </style>

    @else
        <style>
        .search-container-top{
            display: initial;
        }
        .topbar {
            background: #3f3f3f;
            border-bottom: 2px solid #2e131394;
        }
        </style>

    @endisset

</head>

<!--Body-->

<body>
    <div class="topbar">
        <div class="logo-search-container">
            <a href="/"><img src="/icon.png" alt="hanzibase"> <span>HanziBase</span></a>

            <div class="search-container-top">
                <form action="/search" method="POST">
                    @csrf
                    <input type="text" placeholder="Search.." name="query" autocomplete="off">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>



        <div class="lang">
            <a href="/all">All</a>
            <a href="/">Home</a>
        </div>
        <button onclick="burgerMenuShowHide()" id="bMenuButton" class="hamburger-button">
            <i class="fas fa-bars"></i>
        </button>

        
    </div>
    <div class="topbar-clear"></div>

    {{-- The burger menu for mobile --}}
    <div id="bMenu" class="bMenu bMenuClosed">
        <div class="bMenu-container">
        <a href="/">Home</a>
        <a href="/browse">Browse</a>
        <a href="/about">About</a>

        <div class="bMenu-bottom-link">
            <a href="https://jsparrow.uk">HanziBase by James Sparrow</a>
        </div>
    
    </div>
    </div>


