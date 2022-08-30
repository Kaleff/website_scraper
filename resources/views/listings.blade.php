@extends('layout')
@section('content')
    @foreach($listingsArray as $listing)
        <div class="row">
            <listing-table :listing='{{  json_encode($listing)  }}'/>
        </div>
    @endforeach
@endsection