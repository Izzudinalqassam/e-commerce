@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Products Section -->
        <section class="mb-5">
            <h2 class="mb-4">Featured Products</h2>
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card hover-effect">
                            <a href="{{ route('product.show', $product->id) }}">
                                <img src="{{ asset('storage/uploads/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">Rp @if ($product->sale_price)
                                        <s>${{ $product->regular_price }}</s> ${{ $product->sale_price }}
                                    @else
                                        ${{ $product->regular_price }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Categories Section -->
        <section class="mb-5">
            <h2 class="mb-4">Product Categories</h2>
            <div class="row">
                @foreach ($categories as $category)
                    <div class="col-md-2 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="{{ asset('uploads/categories/' . $category->image) }}" class="img-fluid mb-2" alt="{{ $category->name }}">
                                <h5 class="card-title">{{ $category->name }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Brands Section -->
        <section class="mb-5">
            <h2 class="mb-4">Our Brands</h2>
            <div class="row">
                @foreach ($brands as $brand)
                    <div class="col-md-2 mb-4">
                        <div class="card">
                            <img src="{{ asset('uploads/brands/' . $brand->image) }}" class="img-fluid" alt="{{ $brand->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $brand->name }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
