@extends('layouts.app')


@section('content')



<div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>Contact Us</h3>
   <ul class="ibm-breadcrumb ">

      <li><a href="index.html">Home</a></li>

	 

      <li class="active">Contact Us</li>

    </ul>
  </div>
</div>
</div>
	
	
 
	
	<section class="contact-innersec">
  <div class="container ">
    <div class="row justify-content-between ">
   <div class="col-xl-7 col-lg-7 col-md-12 d-flex ">
   <div class="coleft-main">
    <div class="coleft-1  ">
	
 
	
	   <div class="title-area   mb-20"> 
                        <h2 class="sec-title style1">Get In Touch</h2>
                    </div>
	
	<p>Have any questions about our products    please don’t hesitate to reach out. Our dedicated team is on hand to assist and guide you.</p>
     </div>
   <div class="coleft-2 ">
   
   
     <div class="row  ">
      <div class="col-md-6  col-lg-6 col-sm-12 d-flex">
        <div class="ccfo-box">
          <div class="media-icon"><i class="theme-color fas fa-map-marker-alt"></i>   <h3 class="ccfo-title h4">Address</h3></div>
          <div class="media-body">
         
            <p class="ccfo-text">
House No:2508/ Door No:179 Ground floor, Jk hearing clinic building, near Upasana hospital, Chinnakada, Kollam, Kerala 691010</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-6 col-sm-12 d-flex">
        <div class="ccfo-box">
          <div class="media-icon"><i class="theme-color fas fa-phone-alt"></i> <h3 class="ccfo-title h4">Phone</h3></div>
          <div class="media-body">
           
            <p class="ccfo-text"> <a href="tel:098951 20996">098951 20996</a></p>
           
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-12 col-lg-6 d-flex">
        <div class="ccfo-box">
          <div class="media-icon"><i class="theme-color far fa-envelope"></i><h3 class="ccfo-title h4">Email </h3></div>
          <div class="media-body">
            
  <p class="ccfo-text"><a href="mailto:info@acadevo.com">info@acadevo.com</a></p>
          </div>
        </div>
      </div>
	       <div class="col-md-6 col-sm-12 col-lg-6  d-flex">
        <div class="ccfo-box">
          <div class="media-icon"><i class="theme-color far fa-link"></i><h3 class="ccfo-title h4">Social Media</h3></div>
          <div class="media-body">
            
          
       <div class="th-social style2">
<a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
 <a href="#" target="_blank"><i class=""><img src="{{asset('img/twitter.png')}}"></i></a> 
 <a href="#" target="_blank"><i class="fab fa-instagram"></i></a> 
 <a href="#" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
 <a href="#" target="_blank"><i class="fa-brands fa-youtube"></i></a> 
  <a href="https://api.whatsapp.com/send/?phone=+098951 20996&amp;text=%2AHey     Acadevo+&amp;app_absent=0" target="_blank"><i class="fa-brands fa-whatsapp"></i></a> 
 </div>
          </div>
        </div>
      </div>
    </div>
   </div>
   </div>
   
   
   </div>
     <div class="col-lg-5 col-md-12 col-sm-12 d-flex ">
	 
	 <div class="co-forrm-sec">
 
	
	   <div class="title-area   mb-20"> 
                        <h2 class="sec-title style1">Send Us a Message</h2>
                    </div>
	 <form action="#" method="POST" class="ajax-contact">
      <div class="row">
        <div class="form-group col-md-6">
          <input type="text" class="form-control style2" name="name" placeholder="First Name" required="">
          <i class="fal fa-user"></i></div>
		       <div class="form-group col-md-6">
          <input type="text" class="form-control style2" name="name" placeholder="Last Name" required="">
          <i class="fal fa-user"></i></div>
        <div class="form-group col-md-6">
          <input type="email" class="form-control style2" name="email" placeholder="Email Address" required="">
          <i class="fal fa-envelope"></i></div>
		   <div class="form-group col-md-6">
          <input type="text" class="form-control style2" name="phone" placeholder="Phone No" required="">
          <i class="fal fa-phone"></i></div>
    
        <div class="form-group col-12">
          <textarea name="message" id="message" cols="30" rows="3" class="form-control style2" placeholder=" Your Message" required=""></textarea>
          <i class="fal fa-pencil"></i></div>
        <div class="form-btn col-12">
          <button class="th-btn btn-white style3">Send Message</button>
        </div>
      </div>
 
    </form>
	 
	 </div>
   </div>
  </div>
  
  
  </div>
</section>

<div class="mapsec">

<div class="container ">
<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7883.055702653192!2d76.53572482140423!3d8.923408909411618!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b05fd6cbb4bc4a3%3A0x80c60f943c56dea6!2sAcadevo%20Research%20Labs%20Pvt.Ltd!5e0!3m2!1sen!2sin!4v1767269700092!5m2!1sen!2sin"   width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>

</div>





@endsection