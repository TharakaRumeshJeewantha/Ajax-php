<html>
<head>
<title>
PHP AJAX CRUD
</title>
<link rel="stylesheet" href="css/jquery-ui.css"/>
<link rel="stylesheet" href="css/bootstrap.min.css"/>
<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.js"></script>
</head>

<body>
	<div class="container">
		<br />
		<h3 align="center"> PHP AJAX crud operations </h3>
		<p align="center"> -- Tharaka Rumesh Jeewantha -- </p>
		<br /><br />
		<div align="right" style="margin-bottom : 5px;">
		<button type="button" name="add" id="add" class="btn btn-success btn-xs"> Add </button>
		</div>
		<div id="user_data" class="table-responsive">
		</div>
		<br />
	</div>
	
	<div id="user_dialog" title="Add Data">
		<form method="POST" id="user_form">
			<div class="form-group">
				<label> First Name </label>
				<input type="text" name="first_name" id="first_name" class="form-control" />
				<span id="error_first_name" class="text-danger"> </span>
			</div>
			<div class="form-group">
				<label> Last Name </label>
				<input type="text" name="last_name" id="last_name" class="form-control" />
				<span id="error_last_name" class="text-danger"> </span>
			</div>
			<div class="form-group">
				<input type="hidden" name="action" id="action" value="insert" />		
				<input type="hidden" name="hidden_id" id="hidden_id" />		
				<input type="submit" name="form_action" id="form_action" class="btn btn-info" value="Insert" />		
			</div>
		</form>
	</div>
	
	<div id="action_alert" title="Action">
	</div>
	
	<div id="delete_confirmation" title="Confirmation">
		<p> Are you sure you want to Delete this data? </p>
	</div>	
	
</body>
</html>

<script>
$(document).ready(function() {
load_data();
	function load_data() 
	{
	$.ajax({
		url 	: "fetch.php",
		method 	: "POST",
		success	: function (data) { // received data from server
					$('#user_data').html(data);
					}	
		})
	}
	$('#user_dialog').dialog({
		autoOpen	: false,
		width	: 400
		});
		
	$('#add').click(function() {
		$('#user_dialog').attr('title', 'Add Data');
		$('#action').val('insert');
		$('#form_action').val("Insert");
		$('#user_dialog').dialog('open');
	});
	
	$('#user_form').on('submit', function(event) {
		event.preventDefault();
		var error_first_name = '';
		var error_last_name = '';
		if($('#first_name').val() == '')
		{
			error_first_name = 'First Name is required';
			$('#error_first_name').text(error_first_name);
			$('#first_name').css('border-color', '#cc0000');
		}
		else
		{
			error_first_name = '';
			$('#error_first_name').text(error_first_name);
			$('#first_name').css('border-color', '');
		}
		if($('#last_name').val() == '')
		{
			error_last_name = 'Last Name is required';
			$('#error_last_name').text(error_last_name);
			$('#last_name').css('border-color', '#cc0000');
		}
		else
		{
			error_last_name = '';
			$('#error_last_name').text(error_last_name);
			$('#last_name').css('border-color', '');
		}
		if ($('#first_name').val() == '' || $('#last_name').val() == '')
		{
			return false;
		}
		else
		{
			$('#form_action').attr('disabled', 'disabled');
			var form_data = $(this).serialize();
			$.ajax ({
				url	: "action.php",
				method	: "POST",
				data	: form_data,
				success	: function(data)
				{
					$('#user_dialog').dialog('close');
					$('#action_alert').html(data);
					$('#action_alert').dialog('open');
					load_data();
				}
			});
		}
	});
	
	$('#action_alert').dialog({
		autoOpen	: false
	});
	
	$(document).on('click', '.edit', function() {
		var id = $(this).attr("id");
		var action = 'fetch_single';
		$.ajax ({
			url	: "action.php",
			method	: "POST",
			data	: {
						id	: id,
						action	: action
					},
			dataType	: "json",
			success	: function (data) {
					$('#first_name').val(data.first_name);
					$('#last_name').val(data.last_name);
					$('#user_dialog').attr('title', 'Edit Data');
					$('#action').val('update');
					$('#hidden_id').val(id);
					$('#form_action').val('Update');
					$('#user_dialog').dialog('open');
					}
				})
		});
		
	$('#delete_confirmation').dialog ({
		autoOpen	: false,
		modal	: true,
		buttons : {
			Ok	: function() {
					var id = $(this).attr("id");
					var action = 'delete';
					$.ajax ({
						url	: "action.php",
						method	: "POST",
						data	: {
								id	: id,
								action	: action
								},
						success	: function(data)
						{
							$('#delete_confirmation').dialog('close');
							$('#action_alert').html(data);
							$('#action_alert').dialog('open');
							load_data();
						}
					})
				},
			Cancel	: function() {
					$(this).dialog('close');
				}
			}
		});
		
	$(document).on('click', '.delete', function() {
		var id = $(this).attr("id");
		$('#delete_confirmation').data('id', id).dialog('open');
	});
	
});
</script>
