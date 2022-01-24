<?php $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<div class="panel-body">
				<div class="row mb-sm">
				<?php if (is_superadmin_loggedin() ): ?>
					<div class="col-md-4 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
				<?php endif; ?>
                    <div class="col-md-<?=$widget?> mb-sm">
                        <div class="form-group">
                            <label class="control-label"><?php echo translate('role'); ?> <span class="required">*</span></label>
                            <?php
                                $role_list = $this->app_lib->getRoles();
                                echo form_dropdown("staff_role", $role_list, set_value('staff_role'), "class='form-control' required data-plugin-selectTwo
                                data-width='100%' data-minimum-results-for-search='Infinity' ");
                            ?>
                        </div>
                    </div>
					<div class="col-md-<?=$widget?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('templete')?> <span class="required">*</span></label>
							<?php
								$arrayClass = $this->app_lib->getSelectByBranch('card_templete', $branch_id, false, array('user_type' => 2, 'card_type' => 1));
								echo form_dropdown("templete_id", $arrayClass, set_value('templete_id'), "class='form-control' id='templete_id'
								required data-plugin-selectTwo data-width='100%' ");
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="submit" value="search" class="btn btn-default btn-block"><i class="fas fa-filter"></i> <?=translate('filter')?></button>
					</div>
				</div>
			</div>
			<?php echo form_close();?>
		</section>

		<?php if (isset($stafflist)): ?>
			<section class="panel appear-animation" data-appear-animation="<?php echo $global_config['animations']?>" data-appear-animation-delay="100">
				<?php echo form_open('card_manage/idCardprintFn/2', array('class' => 'printIn')); ?>
				<input type="hidden" name="templete_id" value="<?=set_value('templete_id')?>">
				<header class="panel-heading">
					<h4 class="panel-title">
						<i class="fas fa-users"></i> <?=translate('employee_list')?>
					</h4>
					<div class="panel-btn">
						<button type="submit" class="btn btn-default btn-circle" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-print"></i> <?=translate('generate')?>
						</button>
					</div>
				</header>
				<div class="panel-body">
					<div class="row mb-md">
						<div class="col-md-3">
							<div class="form-group mt-xs">
								<label class="control-label"><?=translate('print_date')?></label>
								<input type="text" name="print_date" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' class="form-control" autocomplete="off" value="<?=date('Y-m-d')?>">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mt-xs">
								<label class="control-label"><?=translate('expiry_date')?></label>
								<input type="text" name="expiry_date" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' class="form-control" autocomplete="off" value="<?=date('Y-m-d')?>">
							</div>
						</div>
					</div>

					<div class="table-responsive mt-sm mb-md">
						<table class="table table-bordered table-hover table-condensed mb-none">
							<thead class="text-weight-bold">
								<tr>
									<td><?=translate('sl')?></td>
									<th> 
										<div class="checkbox-replace">
											<label class="i-checks" data-toggle="tooltip" data-original-title="Print Show / Hidden">
												<input type="checkbox" name="select-all" id="selectAllchkbox"> <i></i>
											</label>
										</div>
									</th>
									<td><?=translate('name')?></td>
									<td><?=translate('staff_id')?></td>
									<td><?=translate('department')?></td>
									<td><?=translate('designation')?></td>
									<td><?=translate('mobile_no')?></td>
								</tr>
							</thead>
							<tbody>
								<?php
								$count = 1;
								if (count($stafflist)){
									foreach ($stafflist as $row):
									?>
								<tr>
									<td><?=$count++?></td>
									<td class="hidden-print checked-area hidden-print" width="30">
										<div class="checkbox-replace">
											<label class="i-checks"><input type="checkbox" name="user_id[]" value="<?=$row->id?>"><i></i></label>
										</div>
									</td>
									<td><?=$row->name?></td>
									<td><?=$row->staff_id?></td>
									<td><?=$row->department_name?></td>
									<td><?=$row->designation_name?></td>
									<td><?=$row->mobileno?></td>
								</tr>
							<?php 
								endforeach; 
							}else{
								echo '<tr><td colspan="8"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
				<?php echo form_close(); ?>
			</section>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#branch_id').on("change", function() {
			var branchID = $(this).val();
			getIDCardTempleteByBranch(branchID, 'staff', 'idcard');
		});

        $('form.printIn').on('submit', function(e){
            e.preventDefault();
            var btn = $(this).find('[type="submit"]');
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                dataType: 'html',
                beforeSend: function () {
                    btn.button('loading');
                },
                success: function (data) {
                	certificate_printElem(data, true);
                },
                error: function () {
	                btn.button('reset');
	                alert("An error occured, please try again");
                },
	            complete: function () {
	                btn.button('reset');
	            }
            });
        });
	});

</script>