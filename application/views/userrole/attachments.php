<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-cloud-upload-alt"></i> <?=translate('attachments')?></h4>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-hover table-condensed mb-none table-export">
			<thead>
				<tr>
					<th><?=translate('sl')?></th>
					<th><?=translate('title')?></th>
					<th><?=translate('type')?></th>
					<th><?=translate('class')?></th>
					<th><?=translate('subject')?></th>
					<th><?=translate('remarks')?></th>
					<th><?=translate('publisher')?></th>
					<th><?=translate('date')?></th>
					<th><?=translate('action')?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$count = 1;
					$stu = $this->userrole_model->getStudentDetails();
					$this->db->where('class_id', $stu['class_id'])->or_where('class_id', 'unfiltered');
					$this->db->where('session_id', get_session_id());
					$this->db->order_by('id', 'desc');
					$query = $this->db->get('attachments');
					$attachmentss = $query->result();
					foreach($attachmentss as $row):
				?>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo $row->title; ?></td>
					<td><?php echo get_type_name_by_id('attachments_type', $row->type_id);?></td>
					<td><?php echo $row->class_id == 'unfiltered' ? '<span class="text-dark">All</span>' : get_type_name_by_id('class', $row->class_id);?></td>
					<td><?php echo $row->subject_id == 'unfiltered' ? '<span class="text-dark">Unfiltered</span>' : get_type_name_by_id('subject', $row->subject_id);?></td>
					<td><?php echo $row->remarks; ?></td>
					<td><?php echo get_type_name_by_id('staff', $row->uploader_id);?></td>
					<td><?php echo _d($row->date);?></td>
					<td>
					<?php 
						$extension = strtolower(pathinfo($row->enc_name, PATHINFO_EXTENSION));
						if ($extension == 'mp4') {
					?>
						<a href="javascript:void(0);" onclick="playVideo('<?=$row->id?>');" class="btn btn-default btn-circle icon" data-toggle="tooltip"
							data-original-title="<?=translate('play')?>"> <i class="far fa-play-circle"></i>
						</a>
					<?php  } ?>
						<!--download link-->
						<a href="<?=base_url('userrole/download?file=' . $row->enc_name)?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?=translate('download')?>">
							<i class="fas fa-cloud-download-alt"></i>
						</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</section>

<div class="zoom-anim-dialog modal-block mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="far fa-play-circle"></i> <?php echo translate('play') . " " . translate('video'); ?></h4>
		</header>
		<div class="panel-body">
			<div id='quick_view'></div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-video-dismiss"><?php echo translate('close'); ?></button>
				</div>
			</div>
		</footer>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		// modal dismiss
		$(document).on("click", ".modal-video-dismiss", function(e) {
			e.preventDefault();
			$.magnificPopup.close();
			$('#attachment_video').trigger('pause');
		});
	});


	function playVideo(id) {
	    $.ajax({
	        url: base_url + 'userrole/playVideo',
	        type: 'POST',
	        data: {'id': id},
	        dataType: "html",
	        success: function (data) {
	            $('#quick_view').html(data);
	            mfp_modal('#modal');
	        }
	    });
	}
</script>