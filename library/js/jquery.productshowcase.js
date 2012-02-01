(function($) {

    $.fn.productshowcase = function(options) {
        var defaults = {
			offset_x: 300
        };
        if (options) {
			$.extend(defaults, options);
		}

        return this.each(function(i,parent) {
			
			var listElement = $(this);
			
			jQuery(parent).find(".product").each(function(i,product){
				jQuery(product).bind('click',function(event){
					jQuery(parent).find(".product").removeClass('active');
					product = jQuery(product);
					product.addClass('active');
					product_image = jQuery(parent).find("#product_image")[0];
					jQuery(product_image).fadeOut(function(){
						jQuery(this).css("background-image","url(\""+product.find("img").attr("src")+"\")").fadeIn();
					})
				})
			});
			/*
			jQuery.find(".product").removeClass('active')
			li.addClass('active')
			$("#product_image").fadeOut(function(){
				$("#product_image").find("img").attr("src",li.find("img").attr("src"));
				$(this).fadeIn()
			})
			*/
        });

    };

	function product_click(event){
		
	}
	
	/*
	// public functions definition
	$.fn.pluginName.functionName = function(foo) {
		return this;
	};

	// private functions definition
	function foobar() {}
	*/
})(jQuery);