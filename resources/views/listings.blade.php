@extends('layout')
@section('content')
    <listing-table :listings='{{ json_encode($listingsArray) }}'>
    </listing-table>
    @php
        $nextpage = $page + 1;
        $previouspage = $page - 1;
    @endphp
    @if ($previouspage >= 2)
        <button><a href='{{ url("listings/{$previouspage}") }}'>Previous page</a></button>
    @endif
    @if ($previouspage == 1)
        <button><a href='{{ url("listings") }}'>Previous page</a></button>
    @endif
    <button><a href='{{ url("listings/{$nextpage}") }}'>Next page</a></button>
@endsection