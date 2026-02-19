<!doctype html>
<html class="no-js" lang="zxx">

<head>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Acadevo</title>
    <meta name="author" content=" ">
    <meta name="description" content=" ">
    <meta name="keywords" content=" ">
    
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">

    
    <link rel="icon" type="image/png"   href="{{asset('img/favicon.png')}}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com/">

    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

    <link rel="stylesheet" href="{{asset('css/fontawesome.min.css')}}">

    <link rel="stylesheet" href="{{asset('css/magnific-popup.min.css')}}">

    <link rel="stylesheet" href="{{asset('css/swiper-bundle.min.css')}}">

    <link rel="stylesheet" href="{{asset('css/style.css')}}">

	  <link rel="stylesheet" href="{{asset('css/responsive.css')}}">


</head>

<body class="electronics-shop">

<div class="magic-cursor relative z-10">
        <div class="cursor"></div>
        <div class="cursor-follower"></div>
</div>

<div class="slider-drag-cursor"><img src="assets/img/icon/arrow.svg" alt=""></div>



@include('layouts.partials.header')




@yield('content')




@include('layouts.partials.footer')


<div class="modal fade youmyModal" id="youmyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Sign in</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">

      <form id="customerLoginForm" action="{{ route('customer.login') }}" method="POST" class="slider-contactform form-style3 cnt">
      @csrf

<div class="row gx-20">

            <div class="form-group col-md-12">
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group col-md-12">
            <input type="password" name="password" placeholder="Password" required>
            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

<div class="form-btn col-12">
							<div class="text-center">


							<button type="submit" class="th-btn btn-white style3 " id="loginBtn"> 
              <span class="btnText">Login</span>

              <span class="btnLoader d-none">
                  <i class="fas fa-spinner fa-spin"></i> Checking...
              </span>
              </button>


<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal" aria-label="Close" class="ff">Forgot your password?</a>
<p class="text-center ssll">Or Login Using</p>

<div class="row justify-content-center align-items-center facebb-google">

		  <div class="col-lg-6 col-md-6 col-sm-12">
		  <a href="#" class="login1"><img src="{{asset('img/goo-gle.png')}}" alt="">Sign in with Google</a> 
		  </div>
		  <div class="col-lg-6 col-md-6 col-sm-12">
		  <a href="#" class="login1"><img src="{{asset('img/face-book.png')}}" alt="">Sign in with Facebook</a> 
		  </div>

 <div class="col-lg-12"><div class="llllri">Not signed up? <a href="{{route('customer.register.form')}}">Create an account.</a></div></div>
 
</div>
</div>
</div>
</div>
</form>

      </div>
     
    </div>
  </div>
</div>


 <div class="modal fade youmyModal" id="forgotModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Forgot Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
       <form action="" method="POST" class="slider-contactform form-style3 cnt ">
<div class="row gx-20 mt-30">
<div class="form-group col-md-12">
<input type="email" name="email"  placeholder="Email  " required>  
</div>
 <div class="form-btn col-12">
<div class="text-center mt-20">
<button type="submit" class="th-btn btn-white style3   ">Submit Now </button>
 <div class="row mt-30 justify-content-center align-items-center facebb-google">
 <div class="col-lg-12"><div class="llllri">Already have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#youmyModal" data-bs-dismiss="modal" aria-label="Close" > Sign In</a></div>
		    </div>
   </div>
 </div>
 </div>

</div>

</form>
      </div>
     
    </div>
  </div>
</div>


<form id="customer-logout-form" action="{{ route('customer.logout') }}" method="POST" style="display:none;">
              @csrf
</form>


  <div class="fixedRit">

  <ul>

     <li> <a class="call" href="tel:098951 20996">

    <img src="{{asset('img/so1.png')}}">

      </a> </li>
 <li> <a  class="whatsapp" href="https://api.whatsapp.com/send/?phone=+098951 20996&text=%2AHey Acadevo+&app_absent=0" target="_blank">

      <img src="{{asset('img/so3.png')}}">

      </a> </li>
 
  </ul>
</div>
    <div class="scroll-top"><svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102"><path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;"></path></svg></div>
    
        <script src="{{asset('js/vendor/jquery-3.7.1.min.js')}}"></script>
        <script src="{{asset('js/swiper-bundle.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>

        <script src="{{asset('js/jquery.magnific-popup.min.js')}}"></script>
        <script src="{{asset('js/jquery.counterup.min.js')}}"></script>

        <script src="{{asset('js/tilt.jquery.min.js')}}"></script>

        <script src="{{asset('js/imagesloaded.pkgd.min.js')}}"></script>

        <script src="{{asset('js/isotope.pkgd.min.js')}}"></script>

        <script src="{{asset('js/jquery-ui.min.js')}}"></script>

        <script src="{{asset('js/nice-select.min.js')}}"></script>

        <script src="{{asset('js/gsap.min.js')}}"></script>

        <script src="{{asset('js/owl.carousel.min.js')}}"></script> 

        <script src="{{asset('js/main.js')}}"></script>





    <!-- Login FUnctions -->


    <script>


    $(document).ready(function(){

    $('#customerLoginForm').submit(function(e){
        e.preventDefault();

        let form = $(this);
        let button = $('#loginBtn');

        // Clear old errors
        $('.text-danger').remove();

        // Show loading state
        button.prop('disabled', true);
        button.find('.btnText').addClass('d-none');
        button.find('.btnLoader').removeClass('d-none');

        $.ajax({
            url: form.attr('action'),
            method: "POST",
            data: form.serialize(),

            success: function(response){

                // Success
                button.find('.btnLoader').html('<i class="fas fa-spinner fa-spin"></i> Success');
                
                setTimeout(function(){
                    window.location.href = response.redirect;
                }, 800);

            },

            error: function(xhr){

                button.prop('disabled', false);
                button.find('.btnText').removeClass('d-none');
                button.find('.btnLoader').addClass('d-none');

                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(key, value){
                        $('input[name="'+key+'"]')
                            .after('<span class="text-danger">'+value[0]+'</span>');
                    });
                }
                else if(xhr.status === 401){
                    form.prepend('<div class="text-danger mb-2 text-center">'
                        + xhr.responseJSON.message +
                        '</div>');
                }
                else{
                    alert('Something went wrong.');
                }
            }
        });

    });

});
</script>






		
		
		<script>

    var header = $('#header-sticky');
    var win = $(window);
    
    win.on('scroll', function() {
        if ($(this).scrollTop() > 400) {
           
			 $(".fixedRit").addClass("fixedRit-sticky");
		
			 
			 
        } else {
           
			$(".fixedRit").removeClass("fixedRit-sticky");
			
      }
    });
  </script> 


  <!-- AlertifyJS CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>

  <!-- Default theme -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>

  <!-- AlertifyJS JS -->
  <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
  <script>
  alertify.set('notifier','position', 'bottom-center');
  </script>



  <!-- Cart Ajax Functions Start -->

  <script>

  $(document).ready(function() {

    // Setup CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // =============================
    // ADD TO CART
    // =============================
    $(document).on('click', '.addToCartBtn', function(e) {
        e.preventDefault();

        let button = $(this);
        let productId = button.data('id');
        let qty = $('.quantity_input').val() ?? 1;

        button.prop('disabled', true);

        $.post("{{ route('cart.add') }}", {
            product_id: productId,
            qty: qty
        }, function(response) {

            $('#miniCartWrapper').html(response.html);
            $('.cart-count').text(response.count);
            
            setTimeout(() => {
                button.prop('disabled', false);
            }, 500);

            alertify.success('Added to cart').delay('3').dismissOthers();

        }).fail(function() {
            alert('Something went wrong');
        });
    });

});


// REMOVE ITEM
  $(document).on('click', '.removeFromCartBtn', function() {

      let key = $(this).data('key');

      $.post("{{ route('cart.remove') }}", {
          key: key
      }, function(response) {
          $('#miniCartWrapper').html(response.html);
          $('.cart-count').text(response.count);
      });

      alertify.error('Removed from cart').delay('3').dismissOthers();

  });


</script>



<!-- Cart Ajax FUnctions End -->

<script>

$(document).on('click', '.addToWishlistBtn', function () {

    let productId = $(this).data('id');
    let button = $(this);

    $.ajax({
        url: "{{ route('wishlist.toggle') }}",
        type: "POST",
        data: {
            product_id: productId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {

            if (response.status === 'added') {
                button.find('i').addClass('text-danger');
                alertify.success('Added to wishlist!').dismissOthers();
            }

            if (response.status === 'removed') {
                button.find('i').removeClass('text-danger');
                alertify.error('Removed from wishlist!').dismissOthers();
            }

            if (response.status === false) {
                alertify.error('Login to wishlist!').dismissOthers();
            }
        }
    });

});


</script>





@yield('footer_extras')






</body>
</html>



