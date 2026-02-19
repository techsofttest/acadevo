@extends('layouts.app')


@section('content')


<div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>Certificate Download</h3>
   <ul class="ibm-breadcrumb ">

      <li><a href="{{url('/')}}">Home</a></li>

      <li class="active">Certificate Download</li>

    </ul>
  </div>
</div>
</div>
	
 
 <div class="Certificate-sec">
 <div class="container">
<div class="ccerti-inner">
			
			<form > 
		 
			<div class="row justify-content-center">

			<div class="col-lg-6 col-md-12 col-sm-6">
				 
			<input type="text" name="code" required class="form-control" placeholder="Enter Code"> 
				 
			</div>

			</div>
			

			<div class="row justify-content-center">
				  
			<div class="col-lg-6 col-md-6 col-sm-6 my-3">
				 
			<button type="button" class="th-btn style3 w-100" onclick="openDiv()">Verify And Download</button>
				 
			</div>

			</div>
			
			</form>
			</div>
			
			
			<form>
   
</form>
			
			<div  id="myDiv" style="display:none;">
			
		 
 <img src="assets/img/certificate.webp" alt="" width="100%">
</div>
 </div>
 
 </div>
 



 
@endsection