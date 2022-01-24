Please wait, connecting with Jazzcash....
<html>
<head>
    <script>
        function submitJazzcashForm() {
            var jazzcash = document.forms.jazzcash;
            jazzcash.submit();
        }
    </script>
</head>
<body onload="submitJazzcashForm()">
    <form action="<?php echo $api_url; ?>" method="post" name="jazzcash">
        <?php foreach ($post_data as $key => $value) { ?>
            <input type="hidden" name="<?php echo $key;?>" value="<?php echo $value; ?>">
        <?php } ?>
    </form>
</body>
</html>
