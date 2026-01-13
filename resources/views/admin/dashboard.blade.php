@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-5">
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ auth('web')->user()->name }}!</p>
    <p>Use the navigation menu above to manage products, categories, view transactions, and generate reports.</p>
</div>
@endsection