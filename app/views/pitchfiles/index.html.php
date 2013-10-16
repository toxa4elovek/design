<html><body>
<head>
<script>
<?php if (isset($res)):?>
var iframeResponse = <?php echo $res;?>;
<?php endif;?>
</script>
</head>
<form action="/pitchfiles/add" method="post" id="fileuploadform" enctype="multipart/form-data">
    <input type="file" name="file" id="fileupload" /><br /><br />
    <input type="text" id="fileupload-description" name="description" style="width:370px;margin-right: 20px;padding:1em;color:#CCC;font-size:14px;" placeholder="Пояснение" value="Пояснение" data-placeholder="Пояснение" />
    <input type="hidden" id="file-description" name="file-description">
    <input type="submit" class="button" value="Загрузить" id="uploadButton">
</form>
<script src="/js/jquery-1.7.1.min.js"></script>
<script src="/js/pitchfiles/index.js"></script>
</body></html>
