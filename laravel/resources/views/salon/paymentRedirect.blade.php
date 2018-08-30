<form action="<?php echo $url; ?>" method="post" name="pay" id="betaware-wspay-redirect-form">
    <?php foreach ($data as $key => $value): ?>
    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
    <?php endforeach; ?>
</form>

<script type="text/javascript">
    document.getElementById("betaware-wspay-redirect-form").submit();
</script>