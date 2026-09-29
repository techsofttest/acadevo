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

    <link rel="stylesheet" href="{{asset('css/style.css?v1.4')}}">

	  <link rel="stylesheet" href="{{asset('css/responsive.css')}}">

    @yield('header_extras')

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
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title" id="staticBackdropLabel">Sign in</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body pt-2">

        <div id="loginViewContainer">
          <!-- View 1: Mobile OTP Login (Shown first by default) -->
          <div id="otpLoginSection">
            <!-- Step 1: Send Mobile OTP -->
            <form id="sendOtpForm" action="{{ route('customer.sendOtp') }}" method="POST" class="slider-contactform form-style3 cnt">
              @csrf
              <div class="row gx-20">
                <div class="form-group col-md-12 mb-2">
                  <label class="form-label text-start d-block font-weight-bold" style="font-size:13px; color:#475569;">Enter Mobile Number</label>
                  <div class="mobile-input-container d-flex align-items-stretch" style="width: 100%; border: 1px solid #eaeff2; border-radius: 6px; background: #fff; overflow: hidden; box-shadow: rgb(170 170 170 / 10%) 0px 5px 14px 9px;">
                    <span class="d-flex align-items-center justify-content-center px-3 bg-light text-secondary fw-bold" style="font-size: 14px; border-right: 1px solid #eaeff2; white-space: nowrap; user-select: none;">
                      🇮🇳 +91
                    </span>
                    <input type="tel" name="phone" id="otpPhoneInput" placeholder="10-digit mobile number" maxlength="10" inputmode="numeric" pattern="[0-9]*" required style="flex: 1 1 auto; width: auto !important; height: 52px; border: none !important; box-shadow: none !important; border-radius: 0 !important; padding-left: 12px; letter-spacing: 1px; font-size: 15px;">
                  </div>
                  <div class="phone-error-msg text-danger text-start mt-1" style="font-size:12px;"></div>
                </div>

                <div class="form-btn col-12">
                  <div class="text-center">
                    <button type="submit" class="th-btn btn-white style3 w-100" id="sendOtpBtn">
                      <span class="btnText">Send OTP</span>
                      <span class="btnLoader d-none"><i class="fas fa-spinner fa-spin"></i> Sending OTP...</span>
                    </button>
                  </div>
                  <div class="text-center mt-3">
                    <a href="javascript:void(0);" class="switchToEmailBtn" style="font-size:13px; color:#3b82f6; font-weight:600; text-decoration:none;">
                      <i class="fas fa-envelope me-1"></i> Login with Email instead
                    </a>
                  </div>
                </div>
              </div>
            </form>

            <!-- Step 2: Verify Mobile OTP (Hidden initially) -->
            <form id="verifyOtpForm" action="{{ route('customer.verifyOtp') }}" method="POST" class="slider-contactform form-style3 cnt d-none">
              @csrf
              <input type="hidden" name="phone" id="verifyPhoneHidden">
              <div class="row gx-20">
                <div class="col-12 mb-2">
                  <div class="alert alert-success py-2 px-3 text-center mb-2" style="font-size:13px; border-radius:10px;">
                    OTP sent to <strong id="displayOtpPhone"></strong> <br>
                    <small class="text-muted">(Use test dummy OTP: <strong>0000</strong>)</small>
                  </div>
                </div>

                <div class="form-group col-md-12 mb-2">
                  <label class="form-label text-start d-block font-weight-bold" style="font-size:13px; color:#475569;">Enter OTP</label>
                  <input type="text" name="otp" id="otpCodeInput" placeholder="Enter 4-digit OTP" maxlength="6" required style="letter-spacing:4px; text-align:center; font-weight:bold; font-size:18px;">
                  <div class="otp-error-msg text-danger text-start mt-1" style="font-size:12px;"></div>
                </div>

                <div class="form-btn col-12">
                  <div class="text-center d-flex gap-2">
                    <button type="button" class="btn btn-light py-2 px-3" id="changePhoneBtn" style="border-radius:25px; font-size:13px;">
                      Change No.
                    </button>
                    <button type="submit" class="th-btn btn-white style3 flex-grow-1" id="verifyOtpBtn">
                      <span class="btnText">Verify & Login</span>
                      <span class="btnLoader d-none"><i class="fas fa-spinner fa-spin"></i> Verifying...</span>
                    </button>
                  </div>
                  <div class="text-center mt-2">
                    <a href="javascript:void(0);" id="resendOtpBtn" style="font-size:12px; color:#64748b; text-decoration:underline;">Resend OTP</a>
                  </div>
                  <div class="text-center mt-3">
                    <a href="javascript:void(0);" class="switchToEmailBtn" style="font-size:13px; color:#3b82f6; font-weight:600; text-decoration:none;">
                      <i class="fas fa-envelope me-1"></i> Login with Email instead
                    </a>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <!-- View 2: Email & Password Login (Hidden initially) -->
          <div id="emailLoginSection" class="d-none">
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
                    <button type="submit" class="th-btn btn-white style3 w-100" id="loginBtn"> 
                      <span class="btnText">Login</span>
                      <span class="btnLoader d-none">
                        <i class="fas fa-spinner fa-spin"></i> Checking...
                      </span>
                    </button>

                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal" aria-label="Close" class="ff d-block mt-2">Forgot your password?</a>
                  </div>

                  <div class="text-center mt-3">
                    <a href="javascript:void(0);" class="switchToOtpBtn" style="font-size:13px; color:#3b82f6; font-weight:600; text-decoration:none;">
                      <i class="fas fa-mobile-alt me-1"></i> Login with Mobile OTP instead
                    </a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Social login and register links -->
        <div class="text-center mt-3">
          <p class="text-center ssll my-2">Or Login Using</p>
          <div class="row justify-content-center align-items-center facebb-google">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <a href="{{ route('customer.social.login', 'google') }}" class="login1"><img src="{{asset('img/goo-gle.png')}}" alt="">Sign in with Google</a> 
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <a href="{{ route('customer.social.login', 'facebook') }}" class="login1"><img src="{{asset('img/face-book.png')}}" alt="">Sign in with Facebook</a> 
            </div>
            <div class="col-lg-12">
              <div class="llllri mt-3">Not signed up? <a href="{{route('customer.register.form')}}">Create an account.</a></div>
            </div>
          </div>
        </div>

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

<!-- MODERN QUICK ADD VARIANT SELECTION MODAL -->
<style>
  #variantSelectModal .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    background: #ffffff;
    overflow: hidden;
  }
  #variantSelectModal .modal-header {
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    padding: 18px 24px;
  }
  #variantSelectModal .quick-add-badge {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    background: rgba(253, 91, 68, 0.1);
    color: var(--theme-color, #FD5B44);
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-block;
  }
  #variantSelectModal .product-preview-card {
    background: #f8fafc;
    border-radius: 16px;
    padding: 16px;
    border: 1px solid #f1f5f9;
  }
  #variantSelectModal .product-img-box {
    width: 76px;
    height: 76px;
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    background: #fff;
  }
  #variantSelectModal .variant-btn-group {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 10px;
    padding-top: 8px;
  }
  #variantSelectModal .modal-variant-chip {
    position: relative;
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 110px;
    padding: 12px 18px;
    border-radius: 14px;
    border: 2px solid #e2e8f0;
    background: #ffffff;
    color: var(--title-color, #101018);
    font-family: var(--title-font, "Outfit", sans-serif);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    user-select: none;
    outline: none;
    text-align: center;
  }
  #variantSelectModal .modal-variant-chip .chip-label {
    font-size: 15px;
    font-weight: 700;
    color: #101018;
    line-height: 1.2;
  }
  #variantSelectModal .modal-variant-chip .chip-stock {
    font-size: 12px;
    font-weight: 600;
    margin-top: 4px;
    color: #27ae60;
  }
  #variantSelectModal .modal-variant-chip .chip-stock.out-of-stock {
    color: #eb5757;
  }
  #variantSelectModal .modal-variant-chip .variant-badge-tick {
    display: none;
    position: absolute;
    top: -10px;
    right: -10px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--theme-color, #FD5B44);
    color: #ffffff;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    box-shadow: 0 3px 8px rgba(253, 91, 68, 0.4);
    z-index: 2;
  }
  #variantSelectModal .modal-variant-chip:hover {
    border-color: var(--theme-color, #FD5B44);
    background: #ffffff;
  }
  #variantSelectModal .modal-variant-chip.active {
    border-color: var(--theme-color, #FD5B44);
    background: #ffffff;
    box-shadow: 0 4px 12px rgba(253, 91, 68, 0.12);
  }
  #variantSelectModal .modal-variant-chip.active .variant-badge-tick {
    display: flex;
  }
  #variantSelectModal .modal-variant-chip.disabled,
  #variantSelectModal .modal-variant-chip:disabled,
  #variantSelectModal .modal-variant-chip.out-of-stock-chip {
    opacity: 0.5;
    background: #f1f5f9 !important;
    border-color: #cbd5e1 !important;
    color: #94a3b8 !important;
    cursor: not-allowed !important;
    pointer-events: none !important;
    box-shadow: none !important;
  }
  #variantSelectModal .modal-variant-chip.disabled .chip-label,
  #variantSelectModal .modal-variant-chip:disabled .chip-label,
  #variantSelectModal .modal-variant-chip.out-of-stock-chip .chip-label {
    color: #94a3b8 !important;
  }
  #variantSelectModal .btn-confirm-add {
    background: var(--theme-color, #FD5B44);
    color: #ffffff;
    border: none;
    border-radius: 14px;
    padding: 14px 20px;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    box-shadow: 0 8px 20px rgba(253, 91, 68, 0.3);
    transition: all 0.25s ease;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }
  #variantSelectModal .btn-confirm-add:hover {
    background: #e04a35;
    box-shadow: 0 12px 24px rgba(253, 91, 68, 0.4);
    transform: translateY(-2px);
    color: #ffffff;
  }
  #variantSelectModal .btn-confirm-add:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
  }
</style>

<div class="modal fade" id="variantSelectModal" tabindex="-1" aria-labelledby="variantSelectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center justify-content-between">
        <div>
          <span class="quick-add-badge mb-1">Quick Add</span>
          <h5 class="modal-title font-weight-bold text-dark mb-0" id="variantSelectModalLabel" style="font-size: 18px;">Select Variant</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        
        <div class="product-preview-card d-flex align-items-center mb-4">
            <img id="variantModalProductImg" class="product-img-box me-3" src="" alt="">
            <div>
                <h6 id="variantModalTitle" class="mb-1 text-dark fw-bold" style="font-size: 16px; line-height: 1.3;"></h6>
                <div id="variantModalPrice" class="fs-5 fw-bold text-primary"></div>
            </div>
        </div>

        <div class="mb-4">
            <label class="fw-bold text-dark mb-2 d-block" style="font-size: 13px; letter-spacing: 0.3px; text-transform: uppercase; color: #64748b !important;">Select Option:</label>
            <div id="variantModalList" class="variant-btn-group">
                <!-- Dynamically rendered modern chips -->
            </div>
        </div>

        <input type="hidden" id="modal_product_id" value="">
        <input type="hidden" id="modal_variant_id" value="">

        <div class="mt-4">
            <button type="button" id="confirmModalAddToCartBtn" class="btn-confirm-add">
                <i class="fal fa-shopping-bag"></i>
                <span>Add To Cart</span>
            </button>
        </div>
      </div>
    </div>
  </div>
</div>


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





    <!-- Login Functions -->
    <script>
    $(document).ready(function(){

        // Restrict phone input and OTP input to numbers only
        $('#otpPhoneInput').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        $('#otpCodeInput').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
        });

        // 1. Mobile Send OTP Handler
        $('#sendOtpForm').submit(function(e){
            e.preventDefault();
            let form = $(this);
            let button = $('#sendOtpBtn');
            let phoneInput = $('#otpPhoneInput');
            let phone = phoneInput.val().trim();
            $('.phone-error-msg').text('');

            button.prop('disabled', true);
            button.find('.btnText').addClass('d-none');
            button.find('.btnLoader').removeClass('d-none');

            $.ajax({
                url: form.attr('action'),
                method: "POST",
                data: form.serialize(),
                success: function(response){
                    button.prop('disabled', false);
                    button.find('.btnText').removeClass('d-none');
                    button.find('.btnLoader').addClass('d-none');

                    if(response.success){
                        $('#verifyPhoneHidden').val(response.phone);
                        $('#displayOtpPhone').text(response.phone);
                        $('#sendOtpForm').addClass('d-none');
                        $('#verifyOtpForm').removeClass('d-none');
                        $('#otpCodeInput').focus();
                    }
                },
                error: function(xhr){
                    button.prop('disabled', false);
                    button.find('.btnText').removeClass('d-none');
                    button.find('.btnLoader').addClass('d-none');

                    if(xhr.status === 422 && xhr.responseJSON.errors){
                        let errors = xhr.responseJSON.errors;
                        let msg = errors.phone ? errors.phone[0] : (xhr.responseJSON.message || 'Invalid mobile number.');
                        $('.phone-error-msg').text(msg);
                    } else {
                        $('.phone-error-msg').text(xhr.responseJSON?.message || 'Failed to send OTP. Please try again.');
                    }
                }
            });
        });

        // 2. Change Phone button
        $('#changePhoneBtn').click(function(){
            $('#verifyOtpForm').addClass('d-none');
            $('#sendOtpForm').removeClass('d-none');
            $('#otpCodeInput').val('');
            $('.otp-error-msg').text('');
            $('#otpPhoneInput').focus();
        });

        // 3. Resend OTP button
        $('#resendOtpBtn').click(function(){
            let phone = $('#verifyPhoneHidden').val();
            if(!phone) return;
            $(this).text('Resending...');
            let btn = $(this);

            $.ajax({
                url: "{{ route('customer.sendOtp') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    phone: phone
                },
                success: function(response){
                    btn.text('Resend OTP');
                    alertify.success('OTP sent successfully!').dismissOthers();
                },
                error: function(){
                    btn.text('Resend OTP');
                    alertify.error('Failed to resend OTP.').dismissOthers();
                }
            });
        });

        // 4. Mobile Verify OTP Handler
        $('#verifyOtpForm').submit(function(e){
            e.preventDefault();
            let form = $(this);
            let button = $('#verifyOtpBtn');
            $('.otp-error-msg').text('');

            button.prop('disabled', true);
            button.find('.btnText').addClass('d-none');
            button.find('.btnLoader').removeClass('d-none');

            $.ajax({
                url: form.attr('action'),
                method: "POST",
                data: form.serialize(),
                success: function(response){
                    button.find('.btnLoader').html('<i class="fas fa-check"></i> Success');
                    setTimeout(function(){
                        window.location.href = response.redirect;
                    }, 600);
                },
                error: function(xhr){
                    button.prop('disabled', false);
                    button.find('.btnText').removeClass('d-none');
                    button.find('.btnLoader').addClass('d-none');

                    if(xhr.status === 422){
                        let msg = xhr.responseJSON.message || 'Invalid OTP.';
                        if(xhr.responseJSON.errors && xhr.responseJSON.errors.otp){
                            msg = xhr.responseJSON.errors.otp[0];
                        }
                        $('.otp-error-msg').text(msg);
                    } else {
                        $('.otp-error-msg').text(xhr.responseJSON?.message || 'Verification failed. Please try again.');
                    }
                }
            });
        });

        // 5. Password Login Handler
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

        // 6. Switch between OTP and Email Login Views
        $(document).on('click', '.switchToEmailBtn', function(e){
            e.preventDefault();
            $('#otpLoginSection').addClass('d-none');
            $('#emailLoginSection').removeClass('d-none');
            $('#emailLoginSection input[name="email"]').focus();
        });

        $(document).on('click', '.switchToOtpBtn', function(e){
            e.preventDefault();
            $('#emailLoginSection').addClass('d-none');
            $('#otpLoginSection').removeClass('d-none');
            $('#otpPhoneInput').focus();
        });

        // Reset to OTP view when modal is opened
        $('#youmyModal').on('show.bs.modal', function () {
            $('#emailLoginSection').addClass('d-none');
            $('#otpLoginSection').removeClass('d-none');
            $('#verifyOtpForm').addClass('d-none');
            $('#sendOtpForm').removeClass('d-none');
            $('.phone-error-msg, .otp-error-msg, .text-danger').text('');
            setTimeout(function(){
                $('#otpPhoneInput').focus();
            }, 300);
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
        let variants = button.data('variants');

        // Check if product card has variant options array
        if (variants && Array.isArray(variants) && variants.length > 0) {
            // Sort lowest price first, highest price last
            variants.sort(function(a, b) {
                return parseFloat(a.selling_price) - parseFloat(b.selling_price);
            });

            let productName = button.data('name') || 'Product';
            let productImage = button.data('image') || '';

            $('#modal_product_id').val(productId);
            $('#variantModalTitle').text(productName);
            if (productImage) {
                $('#variantModalProductImg').attr('src', productImage).show();
            } else {
                $('#variantModalProductImg').hide();
            }

            let listHtml = '';
            let inStockVariants = variants.filter(function(v) {
                return (v.stock === undefined || v.stock === null || parseInt(v.stock) > 0);
            });

            let defaultVariant = variants.find(function(v) {
                let isStk = (v.stock === undefined || v.stock === null || parseInt(v.stock) > 0);
                return v.is_default && isStk;
            }) || inStockVariants[0] || null;

            let defaultVariantId = defaultVariant ? defaultVariant.id : null;
            let defaultPriceHtml = '';

            if (defaultVariant) {
                defaultPriceHtml = '₹' + parseFloat(defaultVariant.selling_price).toFixed(2);
                if (parseFloat(defaultVariant.strike_price) > parseFloat(defaultVariant.selling_price)) {
                    defaultPriceHtml += ' <del class="text-muted small ms-2 fs-6">₹' + parseFloat(defaultVariant.strike_price).toFixed(2) + '</del>';
                }
            } else if (variants.length > 0) {
                let firstV = variants[0];
                defaultPriceHtml = '₹' + parseFloat(firstV.selling_price).toFixed(2);
                if (parseFloat(firstV.strike_price) > parseFloat(firstV.selling_price)) {
                    defaultPriceHtml += ' <del class="text-muted small ms-2 fs-6">₹' + parseFloat(firstV.strike_price).toFixed(2) + '</del>';
                }
            }

            variants.forEach(function(v) {
                let inStock = (v.stock === undefined || v.stock === null || parseInt(v.stock) > 0);
                let isSelected = defaultVariantId && (v.id == defaultVariantId);

                listHtml += `<button type="button" 
                                    class="modal-variant-chip ${isSelected ? 'active' : ''} ${!inStock ? 'disabled out-of-stock-chip' : ''}"
                                    data-variant-id="${v.id}"
                                    data-selling-price="${v.selling_price}"
                                    data-strike-price="${v.strike_price}"
                                    data-stock="${v.stock}"
                                    ${!inStock ? 'disabled="disabled"' : ''}>
                                <span class="variant-badge-tick"><i class="fas fa-check"></i></span>
                                <span class="chip-label">${v.label}</span>
                                <span class="chip-stock ${inStock ? '' : 'out-of-stock'}">${inStock ? 'In Stock' : 'Out of stock'}</span>
                            </button>`;
            }); 

            $('#variantModalList').html(listHtml);
            $('#modal_variant_id').val(defaultVariantId || '');
            $('#variantModalPrice').html(defaultPriceHtml);

            if (!defaultVariantId) {
                $('#confirmModalAddToCartBtn').prop('disabled', true).html('<span>Out of Stock</span>');
            } else {
                $('#confirmModalAddToCartBtn').prop('disabled', false).html('<i class="fal fa-shopping-bag"></i> <span>Add To Cart</span>');
            }

            $('#variantSelectModal').modal('show');
            return;
        }

        let qty = $('#quantity_input').val() || $('.quantity_input').val() || 1;
        let variantId = $('#selected_variant_id').val() || button.data('variant-id') || null;

        button.prop('disabled', true);

        $.post("{{ route('cart.add') }}", {
            product_id: productId,
            variant_id: variantId,
            qty: qty
        }, function(response) {

            $('#miniCartWrapper').html(response.html);
            $('.cart-count').text(response.count);
            
            setTimeout(() => {
                button.prop('disabled', false);
            }, 500);

            alertify.success('Added to cart').delay('3').dismissOthers();

        }).fail(function(xhr) {
            button.prop('disabled', false);
            if (xhr.responseJSON && xhr.responseJSON.message) {
                alertify.error(xhr.responseJSON.message).delay('4').dismissOthers();
            } else {
                alertify.error('Something went wrong').delay('3').dismissOthers();
            }
        });
    });

    $(document).on('click', '.modal-variant-chip', function(e) {
        e.preventDefault();
        if ($(this).is(':disabled') || $(this).hasClass('disabled') || $(this).hasClass('out-of-stock-chip')) {
            return false;
        }
        $('.modal-variant-chip').removeClass('active');
        $(this).addClass('active');

        let variantId = $(this).data('variant-id');
        let sellingPrice = parseFloat($(this).data('selling-price'));
        let strikePrice = parseFloat($(this).data('strike-price'));

        $('#modal_variant_id').val(variantId);
        $('#confirmModalAddToCartBtn').prop('disabled', false).html('<i class="fal fa-shopping-bag"></i> <span>Add To Cart</span>');

        let priceHtml = '₹' + sellingPrice.toFixed(2);
        if (strikePrice > sellingPrice) {
            priceHtml += ' <del class="text-muted small ms-2 fs-6">₹' + strikePrice.toFixed(2) + '</del>';
        }
        $('#variantModalPrice').html(priceHtml);
    });

    $(document).on('click', '#confirmModalAddToCartBtn', function(e) {
        e.preventDefault();

        let productId = $('#modal_product_id').val();
        let variantId = $('#modal_variant_id').val();
        let button = $(this);

        button.prop('disabled', true);

        $.post("{{ route('cart.add') }}", {
            product_id: productId,
            variant_id: variantId,
            qty: 1
        }, function(response) {
            $('#miniCartWrapper').html(response.html);
            $('.cart-count').text(response.count);
            $('#variantSelectModal').modal('hide');

            setTimeout(() => {
                button.prop('disabled', false);
            }, 500);

            alertify.success('Added to cart').delay('3').dismissOthers();
        }).fail(function(xhr) {
            button.prop('disabled', false);
            if (xhr.responseJSON && xhr.responseJSON.message) {
                alertify.error(xhr.responseJSON.message).delay('4').dismissOthers();
            } else {
                alertify.error('Something went wrong').delay('3').dismissOthers();
            }
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

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof alertify !== 'undefined') {
            alertify.success("{{ addslashes(session('success')) }}").dismissOthers();
        }
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof alertify !== 'undefined') {
            alertify.error("{{ addslashes(session('error')) }}").dismissOthers();
        }
    });
</script>
@endif

@if(session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof alertify !== 'undefined') {
            alertify.warning("{{ addslashes(session('warning')) }}").dismissOthers();
        }
    });
</script>
@endif

</body>
</html>



