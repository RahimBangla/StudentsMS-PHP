(function($) {
	'use strict';
	
	$(document).ready(function () {
		if ($("#certificateConten").length) {
			$('#certificateConten').summernote({
				fontNames: ['Arial', 'Arial Black', 'Consolas','Tahoma', 'Times New Roman', 'Great Vibes', 'Pinyon Script', 'Parisienne'],
				fontNamesIgnoreCheck: ['Great Vibes', 'Pinyon Script', 'Parisienne'],
				fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '28', '36', '48' , '64', '82'],
				height: 220,
				toolbar: [
					["style", ["style"]],
					["name", ["fontname","fontsize","height"]],
					["font", ["bold","italic","underline", "clear"]],
					["color", ["color"]],
					["para", ["ul", "ol", "paragraph"]],
					["insert", ["link","table"]],
					["misc", ["fullscreen", "undo", "codeview"]]
				]
			});
		}
		
		$("#userType").on("change", function() {
			var val = $(this).val();
			if (val == 1) {
				$('.stafftag').hide("slow");
				$('.studenttags').show("slow");
			} else if(val == 2) {
				$('.studenttags').hide("slow");
				$('.stafftag').show("slow");
			}
		});
		
		$('.btn_tag').on('click', function() {
			var txtToAdd = $(this).data("value");
			$('#certificateConten').summernote('editor.insertText', txtToAdd);
		});
	});
})(jQuery);

function getCertificate(id) {
	$.ajax({
		url: base_url + 'certificate/getCertificate',
		type: 'POST',
		data: {'id': id},
		dataType: "html",
		success: function (data) {
			$('#quick_view').html(data);
			mfp_modal('#modal');
		}
	});
}

function getIDCard(id) {
    $.ajax({
        url: base_url + 'card_manage/getIDCard',
        type: 'POST',
        data: {'id': id},
        dataType: "html",
        success: function (data) {
            $('#quick_view').html(data);
            mfp_modal('#modal');
        }
    });
}

function getTempleteByBranch(branch_id, user_type) {
    $.ajax({
        url: base_url + 'certificate/getTempleteByBranch',
        type: 'POST',
        data: {
            branch_id: branch_id,
            user_type: user_type
        },
        success: function (data) {
            $('#templete_id').html(data);
        }
    });
}

function getExamByclass(class_id, section_id, sel='')
{
    if(class_id !== "" && section_id !== ""){
        var branchID = $('#branch_id').length ? $("#branch_id").val() : "";
        $.ajax({
            url: base_url + 'card_manage/getExamByBranch',
            type: 'POST',
            data: {
                branch_id: branchID,
                class_id: class_id,
                section_id: section_id,
                selected: sel,
            },
            success: function (data) {
                $('#exam_id').html(data);
            }
        });
    }
}

function getIDCardTempleteByBranch(branch_id, user_type, card_type) {
    $.ajax({
        url: base_url + 'card_manage/getIDCardTempleteByBranch',
        type: 'POST',
        data: {
            branch_id: branch_id,
            user_type: user_type,
            card_type: card_type
        },
        success: function (data) {
            $('#templete_id').html(data);
        }
    });
}

// print function
function certificate_printElem(elem, html = false)
{
    if (html == false) {
        var oContent = document.getElementById(elem).innerHTML;
    } else {
       var oContent = elem; 
    }
    var frame1 = document.createElement('iframe');
    frame1.name = "frame1";
    frame1.style.position = "absolute";
    frame1.style.top = "-1000000px";
    document.body.appendChild(frame1);
    var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
    frameDoc.document.open();
    //Create a new HTML document.
    frameDoc.document.write('<html><head><title></title>');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'assets/vendor/bootstrap/css/bootstrap.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'assets/css/custom-style.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'assets/css/certificate.css">');
    frameDoc.document.write('</head><body>');
    frameDoc.document.write(oContent);
    frameDoc.document.write('</body></html>');
    frameDoc.document.close();
    setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        frame1.remove();
    }, 500);
    return true;
}


