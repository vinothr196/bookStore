<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>BookStore | Home</title>
	<link rel="stylesheet" href="index.css">
</head>

<body>
	<nav>
		<a href="/">Home</a>
		<a href="/salesUpload.php">Sales-Upload</a>
	</nav>
	<hr>
	<section>
		<form id="salesFilter" action="#" method="post">
			<div class='wrap center'>
				<div class="fieldDiv">
					<label for="customer">Customer :</label>
					<input type="text" name="customer" id="customer" placeholder="Customer Name">
				</div>
				<div class="fieldDiv">
					<label for="product">Product :</label>
					<input type="text" name="product" id="product" placeholder="Product Name">
				</div>
				<div class="fieldDiv">
					<label for="priceTo">Price :</label>
					<input type="text" name="priceFrom" id="priceFrom" placeholder="From" style="width: 35px">
					<input type="text" name="priceTo" id="priceTo" placeholder="To" style="width: 35px">
				</div>
				<div class='buttonDiv'>
					<input type='submit' name='filter' value='Filter' id='filter' />
					<input type="button" value = "Reset" id = "formReset" />
				</div>
			</div>
		</form>
		<hr>
		<table id="salesTable">
			<thead>
				<tr>
					<th>Customer</th>
					<th>Email-ID</th>
					<th>Product</th>
					<th>Price</th>
					<th>Sale Date (UTC)</th>
				</tr>
			</thead>
			<tbody data-bind="foreach: sales">
				<tr>
					<td data-bind="text: cname"></td>
					<td data-bind="text: mail"></td>
					<td data-bind="text: product"></td>
					<td data-bind="text: price"></td>
					<td data-bind="text: sale_date"></td>
				</tr>
			</tbody>
			<tfoot data-bind="if: sales() != null && sales().length > 0">
				<th colspan="3" id="totalField">Total</th>
				<th data-bind="text: total"></th>
				<th></th>
			</tfoot>
		</table>
	</section>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
	integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://ajax.aspnetcdn.com/ajax/knockout/knockout-3.5.0.js"></script>
<script>
	var viewModel = {
		sales: ko.observableArray([]),
		total: ko.observable()
	};

	ko.applyBindings(viewModel);
	
	$(document).ready(function () {
	  $("form").submit(function (event) {
	    event.preventDefault();
		loadData();
	  });
	  $("form #formReset").on('click',function (event) {
	    event.preventDefault();
		document.getElementById('salesFilter').reset();
		loadData();
	  });

	  function loadData()
	  {
	    var formData = new FormData($("form")[0]);
		$.ajax({
		    url: '/Action/Load.php',
		    data: formData,
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    success: function (data) {
				data = JSON.parse(data);
				viewModel.sales(data.sales);
				viewModel.total(data.total);
			}
		});
	  }
	  loadData();
	});

</script>

</html>