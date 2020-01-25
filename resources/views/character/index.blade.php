@foreach ($chars as $item)
    <h1>{{$item->simp_char}}</h1>
    <h2>{{$item->trad_char}}</h2>
    <p>{{$item->heisig_keyword}}</p>
    <hr>
@endforeach