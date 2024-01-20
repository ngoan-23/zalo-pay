<!doctype html>
<html lang="en"><head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/blue.css">
<title>ZaloPay</title>
    
    
<style>
    body {
        padding:20px;
    }
    
    .icheckbox_flat-blue, .iradio_flat-blue {
        top:-2px;
        margin-right:5px;
    }
    .txtGray {color:#798594;}
</style>
</head>
<body>

<form action="{{ route('zalo.payment') }}" method="POST">
    @csrf
    <label>Amount:</label><input type="text" name="amount"><br>
    <label>Desc:</label><textarea name="description"></textarea>
    <p>Vui lòng chọn hình thức thanh toán:</p>
    <button type="submit">Submit</button>
</form>

<!-- Optional JavaScript --> 
<!-- jQuery first, then Popper.js, then Bootstrap JS --> 
<script src="js/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script>
<script src="js/icheck.min.js"></script>  
    
<script>
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_flat-blue',
    radioClass: 'iradio_flat-blue'
  });
});
</script>

</body>
</html>