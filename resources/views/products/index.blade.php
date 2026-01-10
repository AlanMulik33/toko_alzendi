<div>
    @extends('layouts.app')

    @section('content')
    <a href="{{ route('products.create') }}">Tambah</a>
    <table>
    @foreach($products as $p)
    <tr>
    <td>{{ $p->name }}</td>
    <td>{{ $p->category->name }}</td>
    </tr>
    @endforeach
    </table>
    @endsection

</div>

