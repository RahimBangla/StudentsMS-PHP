<table class="table table-bordered table-condensed table-hover" id="participation_table">
	<thead>
		<tr>
			<th><?=translate('sl')?></th>
			<th><?=translate('name')?></th>
			<th><?=translate('register_no')?></th>
			<th><?=translate('mobile_no')?></th>
			<th><?=translate('joining_time')?></th>
		</tr>
	</thead>
	<tbody id="tableList">
		
			<?php 
			if (!empty($list)) {
			$count = 1; 
			foreach ($list as $key => $value) {
			$stuDetails = $this->application_model->getStudentDetails($value['student_id']);

			?>
			<tr>
			<td><?php echo $count++; ?></td>
			<td><?php echo $stuDetails['first_name'] . " " . $stuDetails['last_name'] ?></td>
			<td><?php echo $stuDetails['register_no'] ?></td>
			<td><?php echo $stuDetails['mobileno'] ?></td>
			<td><?php echo _d($value['created_at']) . " " . date("g:i A", strtotime($value['created_at']))  ?></td>
			</tr>
			<?php } }?>
		
	</tbody>
</table>


<script type="text/javascript">
	$('#participation_table').DataTable({
		"dom": '<"row"<"col-sm-6"l><"col-sm-6"f>><"table-responsive"t>p',
		"pageLength": 25,
		"ordering": false
	});
</script>
