@extends('layouts.dashboard')

@section('title', 'Edit Categories')

@section('bredcrumb')
        <!-- show section in parent file -->
        @parent
        <li class="breadcrumb-item active">Categories</li>
        <li class="breadcrumb-item active">Edit Categories</li>
@endsection

@section('content')
    <!--  enctype="multipart/form-data to till form that data will transfer as binary for image  -->
    <form action="{{ route('dashboard.categories.update', $category->id) }}" method="post" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        @include('dashboard.categories._form', ['button_label' => 'Update' ])

    </form>

@endsection
