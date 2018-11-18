@extends('layout')

@section('title', $product->name)

@section('extra-css')

@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <a href="/">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <a href="{{ route('shop.index') }}">Shop</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <span>Macbook Pro</span>
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="product-section container">
    <div>
        <div class="product-section-image">
            {{-- <img src="{{ asset('img/products/' . $product->slug . '.jpg') }}" alt="product"> --}}
            {{-- <img src="{{ asset('storage/' . $product->image) }}" alt="product"> --}}
            {{-- using helper method --}}
            <img src="{{ productImage($product->image) }}" class="active" alt="product" id="currentImage">
        </div>
        <div>
            <div  class="product-section-images">
                <div class="product-section-thumbnail selected">
                    <img src="{{ productImage($product->image) }}" alt="product">
                </div>
            @if ($product->images)
                {{-- since the image is stored as a string, we need to decode it  --}}
                @foreach (json_decode($product->images, true) as $image)
                <div class="product-section-thumbnail">
                    <img src="{{ productImage($image) }}" alt="product">
                </div> 
                @endforeach
            @endif
            </div>
        </div>
    </div>
        <div class="product-section-information">
            <h1 class="product-section-title">{{ $product->name }}</h1>
            <div class="product-section-subtitle">{{ $product->details }}</div>
            <div class="product-section-price">{{ $product->presentPrice() }}</div>

            <p>
                {!! $product->description !!}
            </p>

            <p>&nbsp;</p>

            {{-- sice we are making a post request, we cant just use this link, we have to use a form --}}
            {{-- <a href="#" class="button">Add to Cart</a> --}}
            <form action="{{ route('cart.store') }}" method="POST">
            {{ csrf_field() }}
            {{-- @csrf --}}
            <input type="hidden" name="id" value="{{ $product->id }}">
            <input type="hidden" name="name" value="{{ $product->name }}">
            <input type="hidden" name="price" value="{{ $product->price }}">
            <button type="submit" class="button button-plain">Add to cart</button>
            </form>
        </div>
    </div> <!-- end product-section -->

    @include('partials.might-like')


@endsection

@section('extra-js')

<script>
    (function(){
        const currentImage = document.querySelector('#currentImage');

    const images = document.querySelectorAll('.product-section-thumbnail');

    images.forEach((element) => element.addEventListener('click', thumbnailClick));

    function thumbnailClick(e){
        // alert('hello');
        //"this" is the div, so we get the 'img' tag:
        // console.log(currentImage.src);

        // currentImage.src = this.querySelector('img').src;
        currentImage.classList.remove('active');
        currentImage.addEventListener('transitionend', () => {
            currentImage.src = this.querySelector('img').src;
            currentImage.classList.add('active');
        })

        images.forEach((element) => element.classList.remove('selected'))
        this.classList.add('selected');
    }
    })();
</script>

@endsection
