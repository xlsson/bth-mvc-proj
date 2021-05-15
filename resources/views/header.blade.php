<!doctype html>
<html>
<meta charset="utf-8">
<title>{{ $title }}</title>
<link rel="stylesheet" href="{{ url('/css/style.css') }}">
<link rel="icon" href="{{ url('/img/favicon.ico') }}">
<body>
<div class="header-wrapper">
    <header>
        <nav>
            <a href="{{ url('/') }}">Home</a>
            @if( auth()->check() )
            <a href="{{ url('/yatzy') }}">Play!</a>
            @endif
            <a href="{{ url('/highscores') }}">Highscores</a>
        </nav>
        <div class="header-right">
            @if( auth()->check() )
                <a href="{{ url('/myaccount') }}">Playing as: {{ auth()->user()->name }}</a> |
                <a href="{{ url('/logout') }}">Logout</a>
            @else
                <a href="{{ url('/register') }}">Register</a> |
                <a href="{{ url('/login') }}">Login</a>
            @endif
        </div>
    </header>
</div>
<div class="line"></div>
<div class="main-wrapper">
<main>
