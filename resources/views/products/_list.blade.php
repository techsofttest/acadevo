@foreach($products as $product)
    @include('products._item', ['product' => $product])
@endforeach