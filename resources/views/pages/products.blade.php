@extends('layouts.app')


@section('content')

<style>

#price-slider {
    margin-top: 8px;
}

.noUi-target {
    border-radius: 6px;
}

.noUi-connect {
    background: #0d6efd; /* bootstrap primary */
}

.noUi-handle {
    border-radius: 50%;
    width: 18px;
    height: 18px;
    top: -6px;
}

.price-labels {
    font-size: 14px;
    color: #333;
}


/* HTML: <div class="loader"></div> */
.loader{
  width: 40px;
  aspect-ratio: 1;
  --c:no-repeat linear-gradient(#000 0 0);
  background: 
    var(--c) 0    0,
    var(--c) 0    100%, 
    var(--c) 50%  0,  
    var(--c) 50%  100%, 
    var(--c) 100% 0, 
    var(--c) 100% 100%;
  background-size: 8px 50%;
  animation: l7-0 1s infinite;
  position: relative;
  overflow: hidden;
}
.loader:before {
  content: "";
  position: absolute;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #000;
  top: calc(50% - 4px);
  left: -8px;
  animation: inherit;
  animation-name: l7-1;
}

@keyframes l7-0 {
 16.67% {background-size:8px 30%, 8px 30%, 8px 50%, 8px 50%, 8px 50%, 8px 50%}
 33.33% {background-size:8px 30%, 8px 30%, 8px 30%, 8px 30%, 8px 50%, 8px 50%}
 50%    {background-size:8px 30%, 8px 30%, 8px 30%, 8px 30%, 8px 30%, 8px 30%}
 66.67% {background-size:8px 50%, 8px 50%, 8px 30%, 8px 30%, 8px 30%, 8px 30%}
 83.33% {background-size:8px 50%, 8px 50%, 8px 50%, 8px 50%, 8px 30%, 8px 30%}
}

@keyframes l7-1 {
 20%  {left:0px}
 40%  {left:calc(50%  - 4px)}
 60%  {left:calc(100% - 8px)}
 80%,
 100% {left:100%}
}

</style>


<!-- noUiSlider CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.8.0/nouislider.min.css" rel="stylesheet">



<div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>Products</h3>
   <ul class="ibm-breadcrumb ">

      <li><a href="{{url('/')}}">Home</a></li>

      <li class="active">Products</li>

    </ul>
  </div>
</div>
</div>
	
 
 <div class="product-inner-ss">
  <div class="container">
  
  <div class="row">
  <div class="col-lg-3 col-md-4">
  
    <div class="ff-side-mainsec">
	<div class="ff-filter">
	
	<h3 class="ff-filter-heading">Filter:</h3>
	
	</div>
	

 <aside class="sidebar-aa">
    <ul class="filter ul-reset">

        @foreach($categories as $category)
            <li class="filter-item">
                <section class="filter-item-inner">

                    <h3 class="filter-item-inner-heading minus">
                        <a href="#">
                            {{ $category->name }}
                        </a>
                    </h3>

                    @if($category->children->count())
                        <ul class="filter-attribute-list ul-reset">
                            <div class="filter-attribute-list-inner">

                                @foreach($category->children as $child)
                                    <li class="filter-attribute-item">
                                        <input
                                            type="checkbox"
                                            class="filter-input filter-attribute-checkbox ib-m category-filter"
                                            value="{{ $child->id }}"
                                            id="cat-{{ $child->id }}"
                                        >

                                        <label for="cat-{{ $child->id }}"
                                               class="filter-attribute-label ib-m">
                                            {{ $child->name }}
                                        </label>
                                    </li>
                                @endforeach

                            </div>
                        </ul>
                    @endif

                </section>
            </li>
        @endforeach

    </ul>
</aside>


	<div class="ff-price">
	
	<h3 class="ff-price-heading">Price</h3>
	
	<p>Move the sliders to adjust price</p>
	
	<div class="row">


   <div class="col-12">

        <!-- Price display -->
        <div class="d-flex justify-content-between mb-2 price-labels">
            <span>
                From ₹<strong id="price-from-label">0</strong>
            </span>
            <span>
                To ₹<strong id="price-to-label">50000</strong>
            </span>
        </div>

        <!-- Slider -->
        <div id="price-slider"></div>

        <!-- Hidden inputs (used by Laravel request) -->
        <input class="filter-input" type="hidden" name="price_from" id="price_from" value="{{ request('price_from') ?? 0 }}">
        <input class="filter-input" type="hidden" name="price_to" id="price_to" value="{{ request('price_to') ?? 50000 }}">

    </div>

	
	</div>
	</div>
	
	</div>
  </div>
    <div class="col-lg-9 col-md-8">
  
  <div class="ff-right-mainsec">
  
  <div class="ff-right-result">
  
  <div class="row align-items-center justify-content-between">
  <div class="col-auto">
  <p class="ff-right-result-p">31 products</p>
  </div>
    <div class="col-auto">

  <select id="sort" class="selectpicker filter-input">
    <option value="" selected disabled>Sort by</option>
    <option value="best_seller">Best Sellers</option>
    <option value="new">New Arrivals</option>
    <option value="featured">Featured Products</option>
    <option value="price_desc">Price, high to low</option>
    <option value="price_asc">Price, low to high</option>
</select>

  </div>
  </div>
  </div>
  <!--  start   -->
  
  <div class="row" id="product-list">
  

                      
  </div>
	  
     
     
    </div>
  <!-------- end --->
  </div>
  </div>
  
  </div>
</div>
</div>






@endsection


@section('footer_extras')


<script>
function loadProducts(filters = {}) {
    $.ajax({
        url: "{{ route('products.ajax') }}",
        data: filters,
        beforeSend: function () {
            $('#product-list').html('<div class="loader"></div>');
        },
        success: function (res) {
            $('#product-list').html(res.html);
        }
    });
}

// Initial load
$(document).ready(function () {
    loadProducts();
});

// Search
$('#search-input').on('keyup', function () {
    loadProducts({
        search: $(this).val()
    });
});

// Filters
$('.filter-input').on('change', function () {
    loadProducts({
        categories: $('.category-filter:checked').map(function () {
            return this.value;
        }).get(),
        min_price: $('#price_from').val(),
        max_price: $('#price_to').val(),
        search: $('#search-input').val(),
        sort: $('#sort').val()
    });
});
</script>



<!-- noUiSlider JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.8.0/nouislider.min.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    var slider = document.getElementById('price-slider');

    var priceFromInput = document.getElementById('price_from');
    var priceToInput   = document.getElementById('price_to');

    var priceFromLabel = document.getElementById('price-from-label');
    var priceToLabel   = document.getElementById('price-to-label');

    var startFrom = parseInt(priceFromInput.value);
    var startTo   = parseInt(priceToInput.value);

    noUiSlider.create(slider, {
        start: [startFrom, startTo],
        connect: true,
        step: 100,
        range: {
            min: 0,
            max: 50000
        },
        format: {
            to: value => Math.round(value),
            from: value => Number(value)
        }
    });

    slider.noUiSlider.on('update', function (values) {
        priceFromInput.value = values[0];
        priceToInput.value   = values[1];

        priceFromLabel.textContent = values[0];
        priceToLabel.textContent   = values[1];
        $('#price_from, #price_to').trigger('change');
    });

});


</script>



@endsection