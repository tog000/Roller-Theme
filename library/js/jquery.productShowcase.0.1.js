(function($) {

	// replace 'pluginName' with the name of your plugin
    $.fn.pluginName = function(options) {
		// plugin default options
        var defaults = {
        };

		// extends defaults with options provided
        if (options) {
			$.extend(defaults, options);
		}

		// iterate over matched elements
        return this.each(function() {
            // implementations
        });

    };

	// public functions definition
	$.fn.pluginName.functionName = function(foo) {
		return this;
	};

	// private functions definition
	function foobar() {}

})(jQuery);