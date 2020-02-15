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
        padding: 0 2em;

        text-decoration: none;
    }

    .lang a:hover {
        color: black;
    }

    .search-container-top {
        width: 100%;
        margin: auto 1em auto 2em;
        display: none;
    }
    

    .search-container-top form {
        display: flex;
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
        background-color: #B5183A;
        font-size: 1.25em;
        background-color: #ffffff00;
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
    </div>
    <div class="topbar-clear"></div>


