<?php if (!defined('THINK_PATH')) exit();?><?php
$author = str_replace('"','',$obj['author']);
$source = str_replace('"','',$obj['source']);
$tag = str_replace('"','',$obj['tag']);
$seo_title = substr(str_replace('"','',$obj['seo_title']),0,2);
$seo_keywords = substr(str_replace('"','',$obj['seo_keywords']),0,2);
$seo_description = substr(str_replace('"','',$obj['seo_description']),0,2);
?>
<script>
$(function(){
	
	$('#more_options').change(function(){
        if ($(this).is(':checked')) {
            $('#more_options_box').show();
        }
        else {
            $('#more_options_box').hide();
        }
    });
	
	 $('#more_seo').change(function(){
        if ($(this).is(':checked')) {
            $('#more_seo_box').show();
        }
        else {
            $('#more_seo_box').hide();
        }
    });
	
	var author="<?php echo ($author); ?>";
	var source="<?php echo ($source); ?>";
	var tag="<?php echo ($tag); ?>";
    if (author != '' || source != '' || tag != '') {
        $('#more_options').attr('checked', true);
        $('#more_options').trigger('change');
    }
	
	var seo_title = "<?php echo ($seo_title); ?>";
	var seo_keywords = "<?php echo ($seo_keywords); ?>";
	var seo_description = "<?php echo ($seo_description); ?>";
    if (seo_title != '' || seo_keywords != '' || seo_description != '') {
        $('#more_seo').attr('checked', true);
        $('#more_seo').trigger('change');
    }
});
</script>