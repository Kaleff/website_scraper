@extends('layout')
@section('content')
    <listing-table :listings='{{ json_encode($listingsArray) }}'>
    </listing-table>
@endsection