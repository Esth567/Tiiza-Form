
<div id="form_success" style="background:green; color:#fff;"></div>
<div id="form_error" style="background:red; color:#fff;"></div>

<form id="enquiry_form" method="post" enctype="multipart/form-data">

   <?php wp_nonce_field('wp_rest');?>

   <div class="name-container">
     <div class="name-field">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="First Name" pattern="[A-Za-z]{3,}" title="Please enter minimum of 3 characters" oninput="validateName('first_name')" required>
        <p class="error-message" id="first_name-error"></p>
   </div>

  <div class="name-field">
    <label for="middle_name">Middle Name</label>
    <input type="text" id="middle_name" name="middle_name" placeholder="Middle Name" pattern="[A-Za-z]{3,}" oninput="validateName('middle_name')" title="Please enter at least 3 characters">
     <p class="error-message" id="middle_name-error"></p>
  </div>

  <div class="name-field">
    <label for="last_name">Last Name</label>
    <input type="text" id="last_name" name="last_name" placeholder="Last Name" pattern="[A-Za-z]{3,}" oninput="validateName('last_name')" title="Please enter at least 3 characters" required>
    <p class="error-message" id="last_name-error"></p>
  </div>
</div>

<div class="form-container">
  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="Eg.john@ymail.com" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Please enter a valid email address" required>
  </div>

  <div class="form-group">
    <label for="phone">Phone</label>
    <input type="text" id="phone" name="phone">
  </div>
</div>

<div class="form-container1">
  <div class="form-group1">
    <label for="address">Address</label>
    <input type="text" id="address" name="address" placeholder="Enter your address" required>
  </div>

  <div class="form-group1">
    <label for="state">State</label>
    <input type="text" id="state" name="state" placeholder="Eg Jos" required>
  </div>

  <div class="form-group1">
    <label for="country">Country</label>
    <input type="text" id="country" name="country" placeholder="Eg Nigeria" required>
  </div>
</div>

 <div class="form-container2">
  <div class="form-row">
    <label for="tracker_id">Tracker ID</label>
    <input type="text" id="tracker_id" name="tracker_id" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,}$" title="Please enter a valid Tracker ID" required>
  </div>

  <div class="form-row">
    <label for="category">Item tag</label>
    <input type="text" id="category" name="category" placeholder="Eg. Bag">
  </div>

  <div class="form-row">
    <label for="color">Color</label>
    <input type="text" id="color" name="color" placeholder="Color of the item">
  </div>
  </div>

 <div class="gender-container">
    <label for="gender">Gender:</label>
    <input type="radio" name="gender" value="male"> Male
    <input type="radio" name="gender" value="female"> Female<br>
  </div>

  <div class="image-container">
    <label for="image">Upload Image:</label>
    <input type="file" id="myFile" name="image">
    <input type="hidden" name="action" value="handle_image_viewing">
  </div>

  <div class="message-container">
    <label>Message</label><br />
    <textarea name="message"></textarea><br /><br />
</div>

  <button type="submit">Submit form</button>

</form>

<script>

 jQuery(document).ready(function($){

    function validateName(fieldName) {
    var input = document.getElementById(fieldName);
    var error = document.getElementById(fieldName + "-error");
    
    // Regular expression to check if the input contains only alphabets
    var regex = /^[a-zA-Z]+$/;

    if (!regex.test(input.value)) {
      error.textContent = "Name must contain only alphabets.";
      input.setCustomValidity("Invalid input");
    } else {
      error.textContent = "";
      input.setCustomValidity("");
    }
  }
 

    //form submission
    $("#enquiry_form").submit( function(event){

      event.preventDefault();
       
      var form = $(this);
      var formData = new FormData(form[0]);

      // Append the file using the correct field name
      var fileInput = document.getElementById('myFile');
      formData.append('image', fileInput.files[0]);


      $.ajax({
        
      type: "POST",
      url: "<?php echo get_rest_url(null, 'tiiza-form/v1/submit-form');?>",
      data: formData,
      contentType: false,
      processData: false,
      success: function(response){

     // Successful submission
      $("#enquiry_form").hide();
      $("#form_success").html(response.message).fadeIn();
    },
      error: function(){
        
        $("#form_error").html("Error submitting your form").fadeIn();
        
      }

      })

    }); 
  });

</script>
