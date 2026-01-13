@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container mt-5">
    <h1>Customer Dashboard</h1>
    <p>Welcome, {{ auth('customer')->user()->name }}!</p>
    <p>Email: {{ auth('customer')->user()->email }}</p>
    <p>Use the navigation menu above to view your transactions or create a new one.</p>
</div>
@endsection