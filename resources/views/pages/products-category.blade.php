@extends('layouts.app')


@section('content')



<div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>{{$pp_title}}</h3>
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

    <div class="col-lg-12 col-md-8">
  
  <div class="ff-right-mainsec">
  
  <div class="ff-right-result">
  
  <div class="row align-items-center justify-content-between">
  <div class="col-auto">
  <p class="ff-right-result-p">{{ $products->count() }} products</p>
  </div>

  </div>
  </div>
  <!--  start   -->
  
  <div class="row">
  



  @include('components.product-grid',['products' => $products])

                      
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