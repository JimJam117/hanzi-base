@include('partials.topbar')
<h1 class="character">{{$char->simp_char ? $char->simp_char : $char->trad_char}}</h1>
<h2 class="translations">{{$char->translations}}</h2>
<p class="pinyin">{{$char->pinyin}}</p>
