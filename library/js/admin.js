jQuery(function(){

	if(jQuery("#roller_color_picker").length >0){
		jQuery("#roller_color_picker").farbtastic("#roller_color_picker_input");
		jQuery("#roller_color_picker_input").on('click',function(e){
			jQuery("#roller_color_picker").show();
			e.stopPropagation()
		})
		jQuery("body").on('click',function(){
			if(jQuery("#roller_color_picker").is(":visible"))
				jQuery("#roller_color_picker").hide();
		})
	}
	
	jQuery("#roller_set_section_background").on('click',function(){
		tb_show('', 'media-upload.php?post_id=102&amp;type=image&amp;TB_iframe=1&amp;width=640&amp;height=122');
	});
	
	jQuery( "#roller_config_layout_inactive_pages, #roller_config_layout_active_pages" ).sortable({
		connectWith: ".connectedSortable",
		placeholder: "roller_config_layout_page_highlight"
	}).disableSelection();

	jQuery("#roller_config_form").on('submit',function(e){
		serialize_options();
	})
	
	jQuery("#roller_config_styles_add").on('click',function(e){
		
		if(jQuery("#roller_config_style_class_description").val()=="" || jQuery("#roller_config_style_class_name").val()==""){
			e.preventDefault();
			return
		}
		
		var text = jQuery("#roller_config_style_class_description").val() + "=" + jQuery("#roller_config_style_class_name").val()
		
		jQuery("#roller_config_styles_list").append("<option>"+text+"</option>");
		
		jQuery("#roller_config_style_class_description").val("");
		jQuery("#roller_config_style_class_name").val("");
		jQuery("#roller_config_style_class_description").focus();
		
		e.preventDefault();
	})
	
	jQuery("#roller_config_styles_remove").on('click',function(e){
		var selected = jQuery("#roller_config_styles_list")[0].selectedIndex
		jQuery("#roller_config_styles_list")[0].remove(selected)
	});
	
});

function serialize_options(){
	var buffer = []
	jQuery("#roller_config_layout_active_pages li").each(function(i,e){
		//Cut the first part: roller_config_layout_page = 25 chars
		buffer.push(jQuery(e).attr('id').substr(25));
	});
	jQuery("#roller_theme_options_layout").attr("value",buffer.join(","));
	
	buffer = []
	jQuery.each(jQuery("#roller_config_styles_list")[0].options,function(i,e){
		//Cut the first part: roller_config_layout_page = 25 chars
		buffer.push(jQuery(e).val());
	});
	jQuery("#roller_theme_options_styles").attr("value",buffer.join(","));
	
} 
