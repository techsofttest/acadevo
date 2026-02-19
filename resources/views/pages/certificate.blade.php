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
		 
			<div class="row">
			
			
			
		 
			
			
			
			 <div class="col-lg-3 col-md-6 col-sm-6">
													  <div class="form-group">
									 
							<select name="school" class="form-control" required="">
<option selected disabled >Select School</option>
<option value="">Rani Matha Public School</option>
<option value="">Kendriya Vidyalaya Ernakulam</option>
<option value="">Darul Uloom H.S.S</option>
<option value="">Sacred Heart CMI Public School</option>
<option value="">Greets Public School</option>
</select>							
				  </div>
				 </div>
				 
				 				 				 <div class="col-lg-3 col-md-6 col-sm-6">
													  <div class="form-group">
								 
								<select name="class" class="form-control" required="">
<option selected disabled >Select Class</option>
<option value="1">Class 1</option>
  <option value="2">Class 2</option>
  <option value="3">Class 3</option>
  <option value="4">Class 4</option>
  <option value="5">Class 5</option>
  <option value="6">Class 6</option>
  <option value="7">Class 7</option>
  <option value="8">Class 8</option>
  <option value="9">Class 9</option>
  <option value="10">Class 10</option>
  <option value="11">Class 11</option>
  <option value="12">Class 12</option>
</select>				 	 
				  </div>
				 </div>
				 
				 	  			 <div class="col-lg-3 col-md-6 col-sm-6">
													  <div class="form-group">
									 
							<select name="class" class="form-control" required="">
<option selected disabled >Select Student</option>
<option value="">Amal Thomas</option>
<option value="">Ajil Sunny</option>
<option value="">Venu Nair</option>
<option value="">Sulfikkar Shan</option>
<option value="">Don Saju</option>
</select>				 	 
				  </div>
				 </div>
				  
			 <div class="col-lg-3 col-md-6 col-sm-6">
				 
				  <button type="button" class="th-btn style3 w-100" onclick="openDiv()">Generate Certificate</button>
				 
				  
				 
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