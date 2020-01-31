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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <title>HanziBase</title>
    <link rel="icon" href="/icon.ico">
    <link rel="stylesheet" href="/css/styles.css">

    <style>
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

    .topbar-clear{
        height: 100px;
        background-color: blue;
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

    .search-container-top {
        width: 100%;
        margin: auto 1em auto 2em;
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

</head>

<!--Body-->

<body>
    <div class="topbar">
        <div class="logo-search-container">
            <a href="/"><img src="/logo.png" alt="hanzibase"></a>

            <div class="search-container-top">
                <form action="/action_page.php">
                    <input type="text" placeholder="Search.." name="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>

        <div class="lang">
            <a href="#" style="font-family: Noto Sans SC;">中文</a>
            <a href="/all">All</a>
            <a href="/">Home</a>
        </div>
    </div>
    <div class="topbar-clear"></div>


