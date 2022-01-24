<html>
<head>
	<script src="https://js.stripe.com/v3/"></script>
    <script>
		var stripe = Stripe("<?php echo $stripe_publishiable; ?>");
        function submitPayuForm() {
			stripe.redirectToCheckout({ sessionId: "<?php echo $sessionId; ?>" });
        }
    </script>
</head>
<body onload="submitPayuForm()">
</body>
</html>
