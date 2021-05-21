<!DOCTYPE html>
<html lang="en">
<head>
  <title>My Partz</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <?php if($status == 1){ ?>
  <div class="col-md-4 col-md-offset-4">
  <?php if($error == 1){ ?>
      <div class="alert alert-success">
        <strong>Success</strong> Password Changed Successfully.
      </div>
  <?php } if($error == 2){ ?>
      <div class="alert alert-danger">
        <strong>Sorry!</strong> The mobile number you have entered does not exist.
      </div>
  <?php }?>	
  <?php }else{ ?>
  <h2>Not Found</h2>
  <div class="alert alert-danger">
    <strong>Sorry!</strong> Link is expired or not valid.
  </div>
  <?php } ?>
  <h2>Change Password</h2>
  <form method="post" action="http://mypartz.com.au/api/user/change_password">
    <input type="hidden" name="ResetToken" value="<?= $ResetToken ?>">
    <div class="form-group">
      <label for="usr">Mobile:</label>
      <input type="text" name="Mobile" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" name="Password" class="form-control" id="pwd" required>
    </div>
    <div class="form-group">
      <label for="pwd">Confirm Password:</label>
      <input type="password" class="form-control" id="cnfpwd" required>
    </div>
    
    <input type="submit" class="btn btn-primary" value="Submit">
    
  </form>
  </div>
</div>

<script>

var password = document.getElementById("pwd")
  , confirm_password = document.getElementById("cnfpwd");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}
password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;

</script>
</body>
</html>
