@extends('layouts.dashboard')

@section('title', 'Trashed Categories')

@section('bredcrumb')
        <!-- show section in parent file -->
        @parent
        <li class="breadcrumb-item ">Categories</li>
        <li class="breadcrumb-item active">Trashed Categories</li>
@endsection

@section('content')



    <div class="mb-5">
        <a href="{{ route('dashboard.categories.index') }} " class="btn btn-sm btn-outline-primary">Back</a>
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
                    <th>Status</th>
                    <th>Deleted At</th>
                    <th colspan="2"></th>
                </tr>
            </thed>
            <tbody>
                @forelse ($categories as $category)

                    <tr>
                        <td><img src="{{ asset('storage/' . $category->image) }}" alt="" height="50"></td>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->status }}</td>
                        <td>{{ $category->deleted_at }}</td>
                        <td>
                            <form action="{{ route('dashboard.categories.restore', $category->id ) }}" method="post">

                                @method('put') <!-- Form method spoofing -->
                                @csrf

                                <button type="submit" class="btn btn-sm btn-outline-info">Restore</button>

                            </form>
                        </td>
                        <td>
                            <form action="{{ route('dashboard.categories.force-delete', $category->id ) }}" method="post">

                                @method('delete') <!-- Form method spoofing -->
                                @csrf

                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>

                            </form>
                        </td>
                    </tr>

                    @empty {{-- if no data between foreach --}}
                    <tr>
                        <td colspan="7">No categories defined.</td>
                    </tr>

                @endforelse
            </tbody>
        </table>

        {{ $categories->withQueryString()->appends(['search' => 1])->links()}} {{-- withQueryString()-> save the previos query filter links()-> making required pagination appends(['search' => 1])-> append param to the request query --}}

@endsection
