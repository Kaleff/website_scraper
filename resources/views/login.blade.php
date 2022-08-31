@extends('layout')
@section('content')
<h1>Login to your account:</h1>
<form method="POST" action="{{ url('users/auth') }}">
    @csrf
    <p>Login:</p>
    <input type="email" name="email">
    @error('email')
        <p style='color:red'>{{ $message }}</p>
    @enderror
    <p>Password:</p>
    <input type="password" name="password">
    <button type="submit">Login</button>
</form>
@endsection
