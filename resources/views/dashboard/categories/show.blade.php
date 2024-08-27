@extends('layouts.dashboard')

@section('title', $category->name)

@section('bredcrumb')
        <!-- show section in parent file -->
        @parent
        <li class="breadcrumb-item active">Categories</li>
        <li class="breadcrumb-item active">{{ $category->name }}</li>
@endsection

@section('content')

    <table class="table">
    <thed>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Store</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thed>
    <tbody>

        @php
           $products = $category->products()->with('store')->paginate(5); // products from relations, so we have query so we can ues relations,, ->products() with () mean return the relation, if products without () mean the object
        @endphp
        @forelse ($products as $product)
            <tr>
                <td><img src="{{ asset('storage/' . $product->image) }}" alt="" height="50"></td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->store->name  }}</td> {{-- from relations --}}
                <td>{{ $product->status }}</td>
                <td>{{ $product->created_at }}</td>

            </tr>

            @empty {{-- if no data between foreach --}}
            <tr>
                <td colspan="5">No Products defined.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $products->links() }}

@endsection
