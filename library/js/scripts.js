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
			
			if(document.width<700)
				return;
			
			defaults = {
				win: null,
				offset: 200,
				header_selector: '#fixed_header',
				spacer_selector: '#fixed_header_spacer',
				section_selector:'.section',
				nav_selector: 'nav'
			};
			if (options) {
				$.extend(defaults, options);
			}
			
			active_section = "";
			sections 	= jQuery(defaults.section_selector);
			nav_height 	= jQuery(defaults.nav_selector).outerHeight(true)
			jQuery.each(sections,function(i,e){
				scrollTop = jQuery(document.body).scrollTop() + nav_height;
				sectionTop = jQuery(e).offset().top;
				sectionHeight = jQuery(e).height();
				if(scrollTop >= sectionTop - nav_height && sectionTop < sectionTop+sectionHeight){
					active_section = jQuery(e).attr("id");
				}
			});
			
			if(jQuery("li#menu_"+active_section).length==0){
				active_section = "Inicio";
			}
			
			click_originated = false;
			jQuery("li.page_item").each(function(i,e){
				if(jQuery(e).data("click")==null){
					jQuery(e).removeClass("current_page_item");
				}else{
					click_originated = true;
				}
			});
			if(!click_originated){
				jQuery("li#menu_"+active_section).addClass("current_page_item");
			}
			
			header = jQuery(defaults.header_selector);
			spacer = jQuery(defaults.spacer_selector);
			
			if ( defaults.win.scrollTop() > 0) {
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
					header.css("position","relative");
					header.hide();
					header.find("header").removeClass("fixed")
					header.css("position","relative");
					header.fadeIn()
				}
			}
			
			/*
			scroll_buffer = jQuery(document).data("scroll_buffer")
			jQuery(document).data("scroll_buffer",++scroll_buffer);
			if(scroll_buffer%10 == 0){
				jQuery(this).highlightMenuItem()
			}*/
			
		},
		highlightMenuItem:function(options){
			defaults = {
				win: null,
				offset: 200,
				header_selector: '#fixed_header',
				section_selector:'.section',
				nav_selector: 'nav'
			};
			if (options) {
				$.extend(defaults, options);
			}
			
			
		},
		goToSection: function( options ) {
			defaults = {
				hash: this.attr('href'),
				offset: 110,
				header_selector: '#fixed_header',
				nav_selector: 'nav',
			};
			if (options) {
				$.extend(defaults, options);
			}
			
			self = this
			
			defaults.hash = defaults.hash.replace('#','');
			
			header = $(defaults.header_selector);
			
			/*
			if(header.hasClass('fixed')){
				offset = 120;
			}else{
				offset = 70;
			}*/
			if(jQuery(document.body).scrollTop()==0){
				defaults.offset = 125;
			}else{
				defaults.offset = 55;
			}
			
			jQuery("li.page_item").removeClass("current_page_item");
			jQuery(self).parent("li").addClass("current_page_item");
			jQuery(self).parent("li").data("click",true);
			
			scrollDistance = 0;
			if ( defaults.hash != '' ) {
				scrollDistance = jQuery('#'+defaults.hash).offset().top - ( jQuery(defaults.nav_selector).outerHeight(true) + defaults.offset );
			}
			
			$('html,body').animate({
				'scrollTop': scrollDistance
			}, 700,function(){
				jQuery("li.page_item").each(function(i,e){
					jQuery(e).data("click",null)
				});
			});
		}
	
})

// as the page loads, call these scripts
$(document).ready(function() {

	
	$(window).bind('scroll', function() {
		$('#header').fixedNav({ win: $(this) });
	});
	
	$('body').delegate('nav a[href*="#"]:not(.button)', 'click', function(e) {
		e.preventDefault();
		$(this).goToSection();
	});
	
	$('body').delegate("a.big-btn", 'click', function(e){
		if(this.href.indexOf("#")>=0){
			e.preventDefault();
			$(this).goToSection();
		}
	})
	
	jQuery(".product_showcase").productshowcase();
	
	//Scroll to location instead of just jumping there
	if(location.href.indexOf("#")>0){
		hash = location.href.substr(location.href.indexOf("#"));
		$(this).goToSection({hash:hash});
	}
 
}); /* end of as page load scripts */