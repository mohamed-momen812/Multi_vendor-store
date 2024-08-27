@extends('layouts.dashboard')

@section('title', 'Categories')

@section('bredcrumb')
        <!-- show section in parent file -->
        @parent
        <li class="breadcrumb-item active">Categories</li>
@endsection

@section('content')



    <div class="mb-5">
        <a href="{{ route('dashboard.categories.create') }} " class="btn btn-sm btn-outline-primary mr-2">Create</a>
        <a href="{{ route('dashboard.categories.trash') }} " class="btn btn-sm btn-outline-dark">Trash</a>
    </div>

    <!-- alert component -->
    <x-alert />

    <form action=" {{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name"  class="mx-2" :value="request('name')"/>
        <select name="status" class="form-control mx-2">
            <option value="">All</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="archived" @selected(request('status') == 'archived')>Archived</option>
        </select>
        <button class="btn btn-dark mx-2">Filter</button>
    </form>

        <table class="table">
            <thed>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Parent</th>
                    <th>Product #</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th colspan="2"></th>
                </tr>
            </thed>
            <tbody>
                @forelse ($categories as $category)

                    <tr>
                        <td><img src="{{ asset('storage/' . $category->image) }}" alt="" height="50"></td>
                        <td>{{ $category->id }}</td>
                        <td>
                            <a href="{{ route('dashboard.categories.show', $category->id) }}">{{ $category->name  }}</a>
                        </td>
                        <td>{{ $category->parent->name  }}</td> {{-- from relations --}}
                        <td>{{ $category->product_number }}</td>  {{-- product_number from relations --}}
                        <td>{{ $category->status }}</td>
                        <td>{{ $category->created_at }}</td>
                        <td>
                            <a href="{{route('dashboard.categories.edit', $category->id )}}" class="btn btn-sm btn-outline-success">Edit</a>
                        </td>
                        <td>
                            <form action="{{ route('dashboard.categories.destroy', $category->id ) }}" method="post">

                                @method('delete') <!-- Form method spoofing -->
                                @csrf

                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>

                            </form>
                        </td>
                    </tr>

                    @empty {{-- if no data between foreach --}}
                    <tr>
                        <td colspan="9">No categories defined.</td>
                    </tr>

                @endforelse
            </tbody>
        </table>

        {{ $categories->withQueryString()->appends(['search' => 1])->links()}} {{-- withQueryString()-> save the previos query filter links()-> making required pagination appends(['search' => 1])-> append param to the request query --}}

@endsection
