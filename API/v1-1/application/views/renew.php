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
  <h2>Account Status</h2>
  
  <?php if($status == 1){ ?>
  <div class="alert alert-success">
    <strong>Success!</strong> <?= $msg ?>
  </div>
  <?php } else{ ?>
  <div class="alert alert-danger">
    <strong>Fail!</strong> Link is expired or not valid.
  </div>
  <?php } ?>
</div>

</body>
</html>
