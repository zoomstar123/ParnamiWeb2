<?php 
include_once 'classes/Style.php';

if (isset($_GET['save_font']))
            $style->upload_font();
?>
<div class="content">
<form action="themes.php?page=theme_options&sub_page=theme_style_custom_font&save_font" method="post" enctype="multipart/form-data">
    <div class="content-top">
        <input type="submit" value="<?php _e("Save all changes", "transport"); ?>">
        <div class="clear"></div>
    </div>
    <div class="content-inner">
        <h3 style="margin-bottom: 30px"><?php _e("Upload custom fonts", "transport"); ?></h3>
        <p>To maximize your customization you can upload your own typography. Simply upload your font from your computer.</p>
        <div class="input"><input type="file" class="custom" name="font"/></div>    

    </div>

    <div class="content-top" style="border-style: solid none; margin-top: 230px">
        <input type="submit" value="<?php _e("Save all changes", "transport"); ?>">
        <div class="clear"></div>
    </div>
    <div class="submit-right">
        <button type="submit" class="fixsave fixed fontawesome"><i class="fa fa-floppy-o"></i></button>
    <div class="clear"></div>    
</form>
</div>