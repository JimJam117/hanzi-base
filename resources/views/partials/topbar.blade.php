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

    <title>
        @yield('title')
    </title>
    <link rel="icon" href="/icon.ico">
    <link rel="stylesheet" href="/css/styles.css">

    <style>
    
    
    </style>


    {{-- Used to determine if the topbar should be transparent or not --}}
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
        
        <a class="logo-container-top" href="/"><img src="/icon.png" alt="hanzibase"> <span>HanziBase</span></a>

        <div class="search-container-top">
            <form action="/search" method="POST">
                @csrf
                <input type="text" placeholder="Search.." name="query" autocomplete="off">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    



        <div class="links-container-top">
            <a href="/">Home</a>
            <a href="/browse">Browse</a>
            <a href="/about">About</a>
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


