<footer class="footer-wrapper footer-layout5 footer-layout6">
        <div class="footer-area th-screen">
            <div class="widget-area">
                <div class="container th-container6">
                    <div class="row justify-content-between">
                        <div class="col-md-6 col-lg-3 col-sm-8">
                            <div class="widget footer-widget">
                                <div class="th-widget-about">
                                    <div class="about-logo2 mb-25"><a href="{{url('/')}}"><h3>Acadevo</h3></a>
									</div>
                                
                                 <div class="th-widget-contact">
								     <div class="info-box">
                                    <div class="box-icon"><i class="fa-solid fa-location-dot"></i></div>
                                    <p class="box-text">House No:2508/ Door No:179 Ground floor, Jk hearing clinic building, near Upasana hospital, Chinnakada, Kollam, Kerala 691010</p>
                                </div>
                                <div class="info-box">
                                    <div class="box-icon"><img src="{{asset('img/icon/phone2.svg')}}" alt=""></div>
                                    <p class="box-text"><a href="tel:098951 20996" class="box-link">098951 20996</a></p>
                                </div>
                                <div class="info-box">
                                    <div class="box-icon"><i class="fa-sharp fa-solid fa-envelope"></i></div>
                                    <p class="box-text"><a href="mailto:info@acadevo.com" class="box-link">info@acadevo.com</a></p>
                                </div>
                            
                            </div>
                                 
                                    
                                    <div class="download-app">
                                 
                                        <div class="social-wrapp">
<div class="th-social style2">
<a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
 <a href="#" target="_blank"><i class=""><img src="{{asset('img/twitter.png')}}"></i></a> 
 <a href="#" target="_blank"><i class="fab fa-instagram"></i></a> 
 <a href="#" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
 <a href="#" target="_blank"><i class="fa-brands fa-youtube"></i></a> 
  <a   href="https://api.whatsapp.com/send/?phone=+098951 20996&text=%2AHey     Acadevo+&app_absent=0" target="_blank"><i class="fa-brands fa-whatsapp"></i></a> 
 </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-4 col-xl-auto col-lg-auto">
                            <div class="widget widget_nav_menu footer-line footer-widget">
                                <h3 class="widget_title">Quick Links</h3>
                                <div class="menu-all-pages-container">
                                    <ul class="menu">
                                        <li><a href="{{url('/')}}">Home</a></li>
                                    
                                        <li><a href="{{url('products')}}">Products</a></li>
                                        <li><a href="{{url('about')}}">About Us</a></li>
                                        <li><a href="{{url('services')}}">Services</a></li>
                                        <li><a href="{{url('testimonials')}}">Testimonials</a></li>
                                        <li><a href="{{url('contact')}}">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xl-auto col-lg-auto">
                            <div class="widget widget_nav_menu footer-line footer-widget">
                                <h3 class="widget_title">Our products</h3>
                                <div class="menu-all-pages-container">
                                    <ul class="menu">

                                    @foreach($header_categories as $footercategory)
                                    <li><a href="{{url('category')}}/{{$footercategory->slug}}">{{$footercategory->name}}</a></li>
                                    @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4 col-xl-auto col-lg-auto">
                            <div class="widget widget_nav_menu footer-line footer-widget">
                                <h3 class="widget_title">My Account</h3>
                                <div class="menu-all-pages-container">

                                    <ul class="menu">

    @guest('customer')

        <li>
            <a href="#" data-bs-toggle="modal" data-bs-target="#youmyModal">
                Login/Register
            </a>
        </li>

        <li>
            <a href="#" data-bs-toggle="modal" data-bs-target="#youmyModal">
                My Account
            </a>
        </li>

        <li>
           <a href="{{ route('cart.view') }}">
                Cart
            </a>
        </li>

        <li>
            <a href="#" data-bs-toggle="modal" data-bs-target="#youmyModal">
                My Wishlist
            </a>
        </li>

    @else

        <li>
            <a href="{{ route('customer.profile') }}">
                My Account
            </a>
        </li>

        <li>
            <a href="{{ route('cart.view') }}">
                Cart
            </a>
        </li>

        <li>
            <a href="{{ route('customer.wishlist') }}">
                My Wishlist
            </a>
        </li>

    @endguest

</ul>

                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-4 col-xl-auto col-lg-auto">
                            <div class="widget widget_nav_menu footer-widget">
                                <h3 class="widget_title">Policy</h3>
                                <div class="menu-all-pages-container">
                                    <ul class="menu">

                                <li><a href="{{url('shipping-policy')}}">Shipping Policy</a></li>
                                <li><a href="{{url('return-policy')}}">Return and Refund</a></li>
                                <li><a href="{{url('terms-and-conditions')}}">Terms & Conditions</a></li>
                                <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-wrap">
            <div class="container th-container6">
                <div class="row gy-2 align-items-center justify-content-between">
                    <div class="col-lg-auto col-md-12">
                        <p class="copyright-text">Copyright <i class="fal fa-copyright"></i> @php echo date('Y') @endphp <a href="{{url('/')}}">Acadevo</a>. All Rights Reserved. Web Designed By <a href="https://www.techsoftweb.com/" target="_blank"> Techsoft</a></p>
                    </div>
                    <div class="col-lg-auto col-md-12 text-center text-lg-end">
                        <div class="footer-card">  <img src="{{asset('img/shape/cards3.png')}}" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>