/*
roller Scripts File
Author: Eddie Machado

This file should contain any js scripts you want to add to the site.
Instead of calling it in the header or throwing it inside wp-head()
this file will be called automatically in the footer so as not to 
slow the page load.

*/

// Modernizr.load loading the right scripts only if you need them
Modernizr.load([
	{
    // Let's see if we need to load selectivizr
    test : Modernizr.borderradius,
    // Modernizr.load loads selectivizr and Respond.js for IE6-8
    nope : ['libs/selectivizr-min.js']
	}
]);

/* imgsizer (flexible images for fluid sites) */
var imgSizer={Config:{imgCache:[],spacer:"/path/to/your/spacer.gif"},collate:function(aScope){var isOldIE=(document.all&&!window.opera&&!window.XDomainRequest)?1:0;if(isOldIE&&document.getElementsByTagName){var c=imgSizer;var imgCache=c.Config.imgCache;var images=(aScope&&aScope.length)?aScope:document.getElementsByTagName("img");for(var i=0;i<images.length;i++){images[i].origWidth=images[i].offsetWidth;images[i].origHeight=images[i].offsetHeight;imgCache.push(images[i]);c.ieAlpha(images[i]);images[i].style.width="100%";}
if(imgCache.length){c.resize(function(){for(var i=0;i<imgCache.length;i++){var ratio=(imgCache[i].offsetWidth/imgCache[i].origWidth);imgCache[i].style.height=(imgCache[i].origHeight*ratio)+"px";}});}}},ieAlpha:function(img){var c=imgSizer;if(img.oldSrc){img.src=img.oldSrc;}
var src=img.src;img.style.width=img.offsetWidth+"px";img.style.height=img.offsetHeight+"px";img.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+src+"', sizingMethod='scale')"
img.oldSrc=src;img.src=c.Config.spacer;},resize:function(func){var oldonresize=window.onresize;if(typeof window.onresize!='function'){window.onresize=func;}else{window.onresize=function(){if(oldonresize){oldonresize();}
func();}}}}

$.fn.extend({
	fixedNav: function( options ) {
			var $self 			= $(this),
				self  			= this,
				$window 		= options.$window
				
			if(document.width<700)
				return;
				
			var defaults = {
				offset: 200,
				header_selector: '#fixed_header',
			};
			if (options) {
				$.extend(defaults, options);
			}
			
			header = $(defaults.header_selector);
			
			if ( $window.scrollTop() >= defaults.offset) {
				if(!header.find("header").hasClass('fixed')){
					header.hide()
					header.find("header").addClass("fixed")
					header.css("position","fixed");
					header.css("top",-80)
					header.show()
					
					header.animate({'top':0})
				}
			}else{
				if(header.find("header").hasClass('fixed')){
					header.hide()
					header.find("header").removeClass("fixed")
					header.css("position","relative");
					header.fadeIn()
				}
			}
		},
		goToSection: function( options ) {
			var $self 			= $(this),
				self			= this,
				hash  			= $self.attr('href'),
				scrollDistance 	= 0,
				offset			= 120;
			
			console.log("asdf"+hash)
			if(hash == ""){
				hash = window.location.url
				console.log(hash)
			}
			
			hash = hash.replace('#','');
			
			if ( hash.toLowerCase() == 'products' ) {
				offset = 110;
			}
			
			if ( hash.toLowerCase() != 'home' ) {
				scrollDistance = $('#'+hash).offset().top - ( $('#nav li a').outerHeight(true) + offset );
			}
			
			$('html,body').animate({
				'scrollTop': scrollDistance
			}, 700);
		}
	
})

// as the page loads, call these scripts
$(document).ready(function() {

	
	$(window).bind('scroll', function() {
		$('#header').fixedNav({ $window: $(this) });
	});
	
	
	
	jQuery(".product_showcase").productshowcase();
	
	

 
}); /* end of as page load scripts */