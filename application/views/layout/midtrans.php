<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo $midtrans_client_key ?>"></script>
	<script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
</head>
<body onload="submitPayuForm()">
</body>
    <script>
        function submitPayuForm() {
	        snap.pay('<?=$snapToken?>', {
	          // Optional
	          onSuccess: function(result){
	          	var post = JSON.stringify(result);
	          	
                $.ajax({
                    url: "<?php echo base_url($this->router->fetch_class() . '/midtrans_success'); ?>",
                    type: 'POST',
                    data: {'post_data': post},
                    dataType: "json",
                    success: function (res) {
                    	console.log(res);
                        window.location.href = res.url;
                    } 
                });
	          },
	          // Optional
	          onPending: function(result){
	          	console.log(result);
	          },
	          // Optional
	          onError: function(result){
	          	console.log(result);
	          }
	        });
        }
    </script>
</html>
