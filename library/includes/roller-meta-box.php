<label>Show Title</label>
<select name="roller_post_options[roller_show_title]">
	<option value="1">Yes</option>
	<option value="0" <?php if($show_title=="0")echo "selected='selected'"; ?>>No</option>
</select>
<br/>
<label>Inner Shadow</label>
<select name="roller_post_options[roller_inner_shadow]">
	<option value="1">Yes</option>
	<option value="0" <?php if($inner_shadow=="0")echo "selected='selected'"; ?>>No</option>
</select>
<br/>
<label>Background Color</label>
<input class="roller_color_selector" type="text" id="roller_color_picker_input" name="roller_post_options[roller_bg_color]" value="<?php echo $bg_color; ?>"/>
<div id="roller_color_picker" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div> 