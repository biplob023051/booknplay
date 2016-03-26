<html>

<body>
    <form action="<?php echo $requestUrl; ?>" method="post" id="submitForm">
        <?php foreach($data as $key=>$value) {?>
            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
        <?php } ?>
    </form>
    <script type="text/javascript">
        document.getElementById("submitForm").submit();
    </script>
</body>
</html>