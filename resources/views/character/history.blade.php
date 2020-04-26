@extends('layouts.app')

{{-- Title --}}
@section('title')
{{ $chars->currentPage() == 1 ? "Browse All Characters" : "Browse All (Page " . $chars->currentPage() .")" }}
@endsection

@section('main')
    
    <table class="history">
        <tr>
            <th>Character</th>
            <th>Pinyin</th>
            <th>Added</th>
        </tr>
        
        @foreach ($chars as $item) 
            <tr>
                <td class="history-character"><a href="/character/{{ $item->char }}">{{ $item->char }}</a></td>
                <td>{{ $item->pinyin }}</td>
                <td>{{ $item->updated_at }}</td>
            </tr>
        @endforeach
    </table>


    {{ $chars->links() }}
@endsection
