<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
                <a href="#list" data-toggle="tab">
                    <i class="fas fa-list-ul"></i> <?=translate('lessons') ." ". translate('list')?>
                </a>
			</li>
<?php if (get_permission('live_class', 'is_add')): ?>
			<li>
                <a href="#add" data-toggle="tab">
                   <i class="far fa-edit"></i> <?=translate('add') ." ". translate('live_class')?>
                </a>
			</li>
<?php
endif;
if (!is_superadmin_loggedin()):
	$config = $this->live_class_model->get('live_class_config', "", true, true);
	if ($config['staff_api_credential'] == 1):
	?>
			<li>
                <a href="#api_credential" data-toggle="tab"> <i class="far fa-plus-square"></i> Zoom Own API</a>
			</li>
<?php
endif;
	endif;
		?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane box active mb-md" id="list">
				<table class="table table-bordered table-hover mb-none table-condensed table-export">
					<thead>
						<tr>
							<th><?=translate('sl')?></th>
<?php if (is_superadmin_loggedin()): ?>
							<th><?=translate('branch')?></th>
<?php endif; ?>
							<th><?=translate('live_class_method')?></th>
							<th><?=translate('title')?></th>
							<th><?=translate('meeting_id')?></th>
							<th><?=translate('class')?></th>
							<th><?=translate('section')?></th>
							<th><?=translate('date')?></th>
							<th><?=translate('start_time')?></th>
							<th><?=translate('end_time')?></th>
							<th><?=translate('created_by')?></th>
							<th><?=translate('status')?></th>
							<th><?=translate('created_at')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$count = 1;
							foreach ($liveClass as $row):
								?>
						<tr>
							<td><?php echo $count++; ?></td>
<?php if (is_superadmin_loggedin()): ?>
							<td><?php echo $row['branchname'];?></td>
<?php endif; ?>
							<td><?php echo $row['live_class_method'] == 1 ? 'Zoom' : 'BigBlueButton'; ?></td>
							<td><?php echo $row['title']; ?></td>
							<td><?php echo $row['meeting_id']; ?></td>
							<td><?php echo $row['class_name'];?></td>
							<td><?php echo $row['section_details'];?></td>
							<td><?php echo _d($row['date']);?></td>
                            <td><?php echo date("h:i A", strtotime($row['start_time'])); ?></td>
                            <td><?php echo date("h:i A", strtotime($row['end_time'])); ?></td>
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
							<td><?php echo _d($row['created_at']);?></td>
							<td class="min-w-c">
								<!-- host link -->
								<a href="javascript:void(0);" class="btn btn-circle btn-default icon" data-toggle="tooltip" data-original-title="<?=translate('host')?>" 
								onclick="getHostModal('<?=$row['meeting_id'] . "|" . $row['id'] ?>');">
									<i class="fas fa-network-wired"></i>
								</a>
							<?php  if (get_permission('live_class', 'is_delete')) { ?>
								<!-- deletion link -->
								<?php echo btn_delete('live_class/delete/'.$row['id']);?>
							<?php } ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
<?php if (get_permission('live_class', 'is_add')): ?>
			<div class="tab-pane" id="add">
					<?php echo form_open($this->uri->uri_string(), array('class' => 'form-bordered form-horizontal frm-submit'));?>
                    <!-- hidden input -->
					<input type="hidden" name="zoom_password" value="<?=substr(md5(mt_rand()), 0, 6)?>">
					<input type="hidden" name="attendee_password" value="<?=substr(md5(mt_rand()), 0, 6)?>">
					<input type="hidden" name="moderator_password" value="<?=substr(md5(mt_rand()), 0, 6)?>">

					<?php if (is_superadmin_loggedin()): ?>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate('branch')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
									$arrayBranch = $this->app_lib->getSelectList('branch');
									echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-width='100%' onchange='getClassByBranch(this.value)'
									data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"></span>
							</div>
						</div>
					<?php endif; ?>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('title')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title" value="" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('class')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0,1)'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('section')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<select class="form-control" name="section[]" id="section_id" data-plugin-selectTwo multiple >
							</select>
							<span class="error"></span>
							<div class="checkbox-replace mt-sm pr-xs pull-right">
								<label class="i-checks"><input type="checkbox" class="chk-sendsmsmail" name="chk_section"><i></i> Select All</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('live_class_method')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayMethod = array(
									'' => translate('select'),
									1 => "Zoom",
									2 => "BigBlueButton"
								);
								echo form_dropdown("live_class_method", $arrayMethod, set_value('live_class_method'), "class='form-control' id='live_class_method'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group hidden-div" id="zoom_config">
                        <div class="col-md-offset-3 col-md-6">
                            <div class="checkbox-replace">
                                <label class="i-checks">
                                    <input type="checkbox" name="join_before_host" id="join_before_host" checked >
                                    <i></i> Join meeting before host start the meeting.
                                </label>
                            </div>
                            <div class="checkbox-replace mt-md">
                                <label class="i-checks">
                                    <input type="checkbox" name="host_video" id="host_video" checked >
                                    <i></i> Host Video (Start video when host join meeting).
                                </label>
                            </div>
                            <div class="checkbox-replace mt-md">
                                <label class="i-checks">
                                    <input type="checkbox" name="participant_video" id="participant_video" checked >
                                    <i></i> Participants Video (Start video when participants join meeting).
                                </label>
                            </div>
                            <div class="checkbox-replace mt-md">
                                <label class="i-checks">
                                    <input type="checkbox" name="option_mute_participants" id="option_mute_participants" >
                                    <i></i> Mute Participants upon entry.
                                </label>
                            </div>
                        </div>
					</div>

					<div class="form-group hidden-div" id="bbb_config">
						<div class="form-group">
							<label class="col-md-3 control-label">Meeting ID <span class="required">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="meeting_id" value="" />
								<span class="error"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Set Max Participants</label>
							<div class="col-md-6">
								<input type="text" class="form-control" placeholder="Enter maximum participant no, leave blank if want unlimited participant." name="max_participants" value="" />
								<span class="error"></span>
							</div>

							<div class="col-md-offset-3 col-md-6 mt-md">
								<div class="checkbox-replace">
									<label class="i-checks">
										<input type="checkbox" name="set_record" checked> <i></i> <?=translate('set_record')?>
									</label>
								</div>
								<div class="checkbox-replace mt-md">
									<label class="i-checks">
										<input type="checkbox" name="set_mute_on_start" checked> <i></i> <?=translate('set_mute_on_start')?>
									</label>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('date')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
								<input type="text" class="form-control" name="date" value="<?=set_value('date', date('Y-m-d'))?>" data-plugin-datepicker
								data-plugin-options='{ "todayHighlight" : true }' />
							</div>
						</div>
						<span class="error"></span>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('time_slot'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="row">
								<div class="col-xs-6">
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-clock"></i></span>
										<input type="text" data-plugin-timepicker class="form-control" name="time_start" id="time_start" onchange='calculate_duration()' value="<?php echo set_value('time_start'); ?>" />
									</div>
									<span class="error"></span>
								</div>
								<div class="col-xs-6">
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-clock"></i></span>
										<input type="text" data-plugin-timepicker class="form-control" name="time_end" id="time_end" onchange='calculate_duration()' value="<?php echo set_value('time_end'); ?>" />
									</div>
								</div>
							</div>
							
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('duration'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="duration" id="duration"  readonly value="0" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('remarks')?></label>
						<div class="col-md-6 mb-md">
							<textarea name="remarks" rows="2" class="form-control" aria-required="true"></textarea>
							<div class="checkbox-replace mt-lg">
								<label class="i-checks">
									<input type="checkbox" name="send_notification_sms" checked> <i></i> <?=translate('send_notification_sms')?>
								</label>
							</div>
						</div>
					</div>
				
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('save')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php
endif;
if (!is_superadmin_loggedin()):
	if ($config['staff_api_credential'] == 1):
		$getData = $this->live_class_model->get('zoom_own_api', array('user_type' => 1, 'user_id' => get_loggedin_user_id()), true);
	?>
			<div class="tab-pane" id="api_credential">
				<?php echo form_open('live_class/zoom_own_api', array('class' => 'form-bordered form-horizontal frm-submit'));?>
				<input type="hidden" name="api_id" value="<?=$getData['id']?>" >
					<div class="form-group">
						<label class="col-md-3 control-label">Zoom API Key <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="zoom_api_key" value="<?=$getData['zoom_api_key']?>" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Zoom API Secret <span class="required">*</span></label>
						<div class="col-md-6 mb-md">
							<input type="text" class="form-control" name="zoom_api_secret" value="<?=$getData['zoom_api_secret']?>" />
							<span class="error"></span>
						</div>
					</div>
				
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('save')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php
	endif;
endif;
?>
		</div>
	</div>
</section>

<div class="zoom-anim-dialog modal-block modal-block-lg mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-bars"></i> <?php echo translate('live_class') . " " . translate('host'); ?></h4>
		</header>
		<div class="panel-body">
			<div id='quick_view'></div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss"><?php echo translate('close'); ?></button>
				</div>
			</div>
		</footer>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('.chk-sendsmsmail').on('change', function() {
			if($(this).is(':checked') ){
				$(this).parents('.form-group').find('select > option').prop("selected","selected");
				$(this).parents('.form-group').find('select').trigger("change");
			} else {
				$(this).parents('.form-group').find('select').val(null).trigger('change');
			}
		});
	});

	// get details
	function getHostModal(id) {
	    $.ajax({
	        url: base_url + 'live_class/hostModal',
	        type: 'POST',
	        data: {'meeting_id': id},
	        dataType: "html",
	        success: function (data) {
	            $('#quick_view').html(data);
	            mfp_modal('#modal');
	        }
	    });
	}

	function calculate_duration() {
		var today = '<?php echo date("Y/m/m ")?>';
		var startTime = new Date(today + $("#time_start").val()); 
		var endTime = new Date(today + $("#time_end").val());
		var difference = endTime.getTime() - startTime.getTime(); // This will give difference in milliseconds
		var resultInMinutes = Math.abs(difference / 60000);
		$("#duration").val(parseInt(resultInMinutes));
	}
</script>