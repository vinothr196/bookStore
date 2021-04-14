<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>BookStore | Sales Upload</title>
	<link rel="stylesheet" href="index.css">
</head>
<body>
	<nav>
		<a href="/">Home</a>
		<a href="/salesUpload.php">Sales-Upload</a>
	</nav>
	<hr>
	<section>
		<form action='Action/Upload.php' method='post' enctype='multipart/form-data'>
			<div class="wrap center">
				<div class="fieldDiv">
					<label for="upload">Sales File : </label>
					<input type='file' name='upload' value='Upload' id='upload' accept="application/json" required/>
				</div>
				<div class='buttonDiv'>
					<input type='submit' name='import' value='Import' id='import' />
				</div>
			</div>
		</form>
	</section>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
	$(document).ready(function () {
	  $("form").submit(function (event) {
	    event.preventDefault();
	    var formData = new FormData($(this)[0]);
		 
		$.ajax({
		    url: 'Action/Upload.php',
		    data: formData,
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    success: function(result){
		      result = JSON.parse(result);
		      alert(result.message);
		      if(result.status == "success")
		      {
		      	$("form")[0].reset();
		      }
		    }
		});
	  });
	});
</script>
</html>