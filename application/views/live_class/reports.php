<?php  $widget = (is_superadmin_loggedin() ? 2 : 3); ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<?php echo form_open($this->uri->uri_string(), array('class' => 'validate'));?>
			<div class="panel-body">
				<div class="row mb-sm">
				<?php if (is_superadmin_loggedin() ): ?>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' onchange='getClassByBranch(this.value)'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
				<?php endif; ?>
					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)'
								required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('section')?></label>
							<?php
								$arraySection = $this->app_lib->getSections(set_value('class_id'));
								echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('live_class_method')?></label>
							<?php
								$arrayMethod = array(
									'' => translate('select'),
									1 => "Zoom",
									2 => "BigBlueButton"
								);
								echo form_dropdown("live_class_method", $arrayMethod, set_value('live_class_method'), "class='form-control' id='live_class_method'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-3">		
						<div class="form-group">
							<label class="control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fas fa-calendar-check"></i></span>
								<input type="text" class="form-control daterange" name="daterange" value="<?php echo set_value('daterange', date("Y/m/d") . ' - ' . date("Y/m/d")); ?>" required />
							</div>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="search" value="1" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
					</div>
				</div>
			</footer>
			<?php echo form_close();?>
		</section>

		<?php if (isset($livelist)):?>
		<section class="panel appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-video"></i> <?php echo translate('live_class') . " " . translate('list');?></h4>
			</header>
			<div class="panel-body mb-md">
				<table class="table table-bordered table-hover mb-none table-condensed table-export">
					<thead>
						<tr>
							<th><?=translate('sl')?></th>
							<th><?=translate('live_class_method')?></th>
							<th><?=translate('api_credential')?></th>
							<th><?=translate('title')?></th>
							<th><?=translate('meeting_id')?></th>
							<th><?=translate('class')?></th>
							<th><?=translate('section')?></th>
							<th><?=translate('date')?></th>
							<th><?=translate('start_time')?></th>
							<th><?=translate('duration')?></th>
							<th><?=translate('created_by')?></th>
							<th><?=translate('status')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$count = 1;
							foreach ($livelist as $row):
								?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo $row['live_class_method'] == 1 ? 'Zoom' : 'BBB'; ?></td>
							<td><?php echo $row['own_api_key'] == 0 ? 'Global' : 'Own'; ?></td>
							<td><?php echo $row['title']; ?></td>
							<td><?php echo $row['meeting_id']; ?></td>
							<td><?php echo $row['class_name'];?></td>
							<td><?php echo $row['section_details'];?></td>
							<td><?php echo _d($row['date']);?></td>
                            <td><?php echo date("h:i A", strtotime($row['start_time'])); ?></td>
                            <td><?php echo $row['duration'] . " Min"; ?></td>
							<td><?php echo $row['staffname']; ?></td>
							<td>
								<?php  
								$status = '<i class="far fa-clock"></i> ' . translate('waiting');
								$labelmode = 'label-info-custom';
								if (strtotime($row['date']) == strtotime(date("Y-m-d")) && strtotime($row['start_time']) <= time()) {
									$status = '<i class="fas fa-video"></i> ' . translate('live');
									$labelmode = 'label-success-custom';
								}
								if (strtotime($row['date']) < strtotime(date("Y-m-d")) || strtotime($row['end_time']) <= time()) {
									$status = '<i class="far fa-check-square"></i> ' . translate('expired');
									$labelmode = 'label-danger-custom';
								}
								echo "<span class='label " . $labelmode . " '>" . $status . "</span>";
								?>
							</td>
							<td class="min-w-c">
								<!-- host link -->
								<a href="javascript:void(0);" class="btn btn-circle btn-default" onclick="studentView('<?=$row['id']?>');" data-toggle="tooltip" data-original-title="<?=translate('student_participation_report')?>" 
								onclick="getHostModal('<?=$row['meeting_id'] . "|" . $row['id'] ?>');">
									<i class="far fa-user-circle"></i> Atten List
								</a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</section>
		<?php endif;?>
	</div>
</div>

<div class="zoom-anim-dialog modal-block modal-block-lg mfp-hide" id="quickView">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title">
				<i class="far fa-user-circle"></i> <?=translate('student_participation_report')?>
			</h4>
		</header>
		<div class="panel-body">
			<div id="tableList"></div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss"><?=translate('close')?></button>
				</div>
			</div>
		</footer>
	</section>
</div>

<script type="text/javascript">
	function studentView(live_id) {
	    $.ajax({
	        url: base_url + 'live_class/participation_list',
	        type: 'POST',
	        data: {live_id: live_id},
	        dataType: 'html',
	        success: function (res) {
	            $('#tableList').html(res);
	            mfp_modal('#quickView');
	        }
	    });
	}
</script>