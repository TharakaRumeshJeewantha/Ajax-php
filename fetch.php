<?php
include('database_connection.php');
$query = "SELECT * FROM tbl_users";
$statement = $connect -> prepare ($query);
$statement -> execute();
$result = $statement -> fetchAll();
$total_row = $statement -> rowCount();
$output = '
	<table class = "table table-striped table-bordered">
		<tr>
			<th> First Name </th>
			<th> Last Name </th>
			<th> Edit </th>
			<th> Delete </th>
		</tr>
	';	
	
	if ($total_row > 0)
	{
		foreach ($result as $row)
			{
				$output .= '
							<tr>
								<td>' .$row["first_name"]. '</td>
								<td>' .$row["last_name"]. '</td>
								<td> <button type="button" name="edit" class="btn btn-primary btn-xs edit" id="' .$row["id"]. '"> Edit </button> </td>
								<td> <button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' .$row["id"]. '"> Delete </button> </td>
							</tr>
						';
			}
	}
	else
	{
		$output .= '
			<tr>
				<td colspan="4" align="center"> Data not Found </td>
			</tr>
			';
	}
	
	$output .= '</table>';
	echo $output;
?>