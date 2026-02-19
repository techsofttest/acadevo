@foreach($products as $product)
    @include('components.product-grid', ['product' => $product])
@endforeach