
<div id="form_success" style="background:green; color:#fff;"></div>
<div id="form_error" style="background:red; color:#fff;"></div>

<form id="enquiry_form" enctype="multipart/form-data">

   <?php wp_nonce_field('wp_rest');?>

   <div class="name-container">
     <div class="name-field">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="First Name" pattern="[A-Za-z]{3,}" maxlength="50" title="Please enter minimum of 3 characters" required>
        <p class="error-message" id="first_name-error"></p>
   </div>

  <div class="name-field">
    <label for="middle_name">Middle Name</label>
    <input type="text" id="middle_name" name="middle_name" placeholder="Middle Name" pattern="[A-Za-z]{3,}" maxlength="50" title="Please enter at least 3 characters">
     <p class="error-message" id="middle_name-error"></p>
  </div>

  <div class="name-field">
    <label for="last_name">Last Name</label>
    <input type="text" id="last_name" name="last_name" placeholder="Last Name" pattern="[A-Za-z]{3,}" maxlength="50" title="Please enter at least 3 characters" required>
    <p class="error-message" id="last_name-error"></p>
  </div>
</div>

<div class="form-container">
  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="Eg.john@ymail.com" title="Please enter a valid email address" required>
    <div id="email-error-message"></div>
  </div>

  <div class="form-group">
    <label for="phone">Phone</label>
    <input type="text" id="phone" name="phone">
    <p class="error-message" id="phone-error"></p>
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
    <p class="error-message" id="tracker_id-error"></p>
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
    <input type="file" id="image" name="image" accept="image/*" required><br>
    <span id="file-name"></span>
  </div>

  <div class="message-container">
    <label>Message</label><br />
    <textarea name="message"></textarea><br /><br />
</div>

  <button type="submit">Submit form</button>

</form>

<script>

 jQuery(document).ready(function($){

   $('#email').on('input', function() {
    var email = $(this).val();
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    var errorMessageContainer = $('#email-error-message'); // Assuming you have an element with the id 'email-error-message' to display the error message

    if (!emailPattern.test(email)) {
      // Provide feedback to the user (e.g., display an error message)
      errorMessageContainer.text('Please enter a valid email address');
       errorMessageContainer.css({
        'color': 'red',
        'font-weight': 'thin',
        'margin-top': '5px' // Adjust spacing as needed
        // Add more styles as desired
      });
      return false;
    } else {
      errorMessageContainer.text(''); // Clear the error message if the email is valid
      // Reset styles when the email is valid
       errorMessageContainer.css({
        'color': '', // Reset to default color
        'font-weight': '',
        'margin-top': ''
        // Reset other styles as needed
      });
    }
  });


    function validateName(fieldName) {
     var name  = document.getElementById(fieldName);
     var value = name.value;
     var error = document.getElementById(fieldName + "-error");
    
    // Regular expression to check if the input contains only alphabets
    var namePattern = /^[a-zA-Z]+$/;

    if (!namePattern.test(value)) {
        error.textContent = "Name must contain only alphabets.";
        name.setCustomValidity("Invalid input");
        error.style.color = "red";

      } else if (value.length < 3) {
            error.textContent = "Name must be at least 3 characters long.";
            name.setCustomValidity("Invalid input");
            error.style.color = "red";
      } else {
            error.textContent = "";
            name.setCustomValidity("");
            error.style.color = "";
       }
    }

    $('#first_name, #middle_name, #last_name').on('input', function() {
        validateName(this.id);
    });

    
    function validatePhoneNumber() {
    var phoneInput = document.getElementById('phone');
    var phoneNumber = phoneInput.value;
    var error = document.getElementById('phone-error');
    
    // Regular expression to check for a valid phone number format
    var phonePattern = /^\d{11}$/;

    if (!phonePattern.test(phoneNumber)) {
        phoneInput.setCustomValidity("Invalid phone number format. Please enter 11 digits.");
        error.textContent = "Invalid phone number format. Please enter 11 digits.";
        error.style.color = "red"; // Apply red color to the error message
    } else {
        phoneInput.setCustomValidity("");
        error.textContent = "";
        error.style.color = ""; // Reset the color to default
    }
   }

  $('#phone').on('input', validatePhoneNumber);


  // Tracker ID validation
    $('#tracker_id').on('input', function() {
        validateTrackerId();
    });

    function validateTrackerId() {
        var trackerIdInput = document.getElementById('tracker_id');
        var trackerId = trackerIdInput.value;
        var trackerIdError = document.getElementById('tracker_id-error');

        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            data: { action: 'validate_tracker_id', tracker_id: trackerId },
            success: function(response) {
                if (response === 'invalid') {
                    trackerIdInput.setCustomValidity('Invalid Tracker ID. This Tracker ID is either not in the database or already registered.');
                    trackerIdError.textContent = 'Invalid Tracker ID. This Tracker ID is either not in the database or already registered.';
                    trackerIdError.style.color = 'red';
                } else if (response === 'not_logged_in') {
                // Handle not logged in case if needed
                }
                else {
                    trackerIdInput.setCustomValidity('');
                    trackerIdError.textContent = '';
                    trackerIdError.style.color = '';
                }
            },
            error: function() {
                // Handle error if needed
            }
        });
    }


   $('#image').on('change', function() {
        // Get the file name
        var fileName = $(this).val().split('\\').pop();
        
        // Display the file name in the span element
        $('#file-name').text(fileName);
    });
 

    //form submission
    $("#enquiry_form").submit( function(event){

      event.preventDefault();
       
      var form = $(this);
      var formData = new FormData(form[0]);

      // Append the file using the correct field name
      //var fileInput = document.getElementById('myFile');
      formData.append('image[]', $('input[name="image"]')[0].files[0]);


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
