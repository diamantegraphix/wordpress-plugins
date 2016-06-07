<?php 
/* 
Calculates an estimate for a website based on user sumbitted input.

*/
?>

<!DOCTYPE html>
<html lang="en-US">
	<script   src="https://code.jquery.com/jquery-1.12.4.min.js"
			  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
			  crossorigin="anonymous"></script>
	<head><script type="text/javascript" src="price-calculator.js"></script>
	</head>


<body>
<fieldset>

<!-- Form Name -->
<legend>Form Name</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="numPages">How many pages are in your site</label>  
  <div class="col-md-2">
  <input id="numPages" name="numPages" value="1" class="form-control input-md" required="" type="text" onchange="updateNumPages (this.value);">

  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="design">Do you want a custom design or a template?</label>
  <div class="col-md-4">
    <select id="design" name="design" class="form-control" onchange="updateDesignType(this.value)">
      <option value="c" selected>c</option>
      <option value="t">t</option>
    </select>
  </div>
</div>

<!-- Multiple Radios -->
<div class="form-group">
  <label class="col-md-4 control-label" for="responsive">Do you want a responsive design?</label>
  <div class="col-md-4">
  <div class="radio">
    <label for="responsive-0">
      <input name="responsive" id="responsive-0" value="r" type="radio" onclick="updateResponsiveType(this.value)">
      r
    </label>
	</div>
  <div class="radio">
    <label for="responsive-1">
      <input name="responsive" id="responsive-1" value="s" checked="checked" type="radio" onclick="updateResponsiveType(this.value)">
      s
    </label>
	</div>
  </div>
</div>

</fieldset>


<div id="price">$<span id="total"></span></div>



</body>





