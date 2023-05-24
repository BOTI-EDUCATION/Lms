"use strict";

var LMSScriptHeader = function() {

	// Initialize
	var _init = function() {
        
        (function(){
            
            $(".full-screen-btn").click(function(e){
                console.log('full-screen-btn');
                e.preventDefault();
                $(".courses-content").toggleClass('full-screen');
                $('.menu-vertical').toggleClass('full-screen-content');
                $('.header').toggleClass('hidden');
                
            });
            
            $('.menu-courses').click(function(){
            $(".menu-vertical").toggleClass('slide-menu');
        //        $("body").addClass('menu-open');
            });
            $('.menu-close').click(function(){
                $(".menu-vertical").removeClass('slide-menu');
        //        $("body").removeClass('menu-open');
            });
            $(".slide-element").click(function(){
                $(".current-matiere").toggleClass("hidden");
                $(".current-class").toggleClass("hidden");
                $(".menu-item").toggleClass('menu-open');
            
            });
            $(".current-class").click(function(e){
                e.preventDefault();
                $(".class-menu").addClass("class-menu-animate");
                $('.current-class i ').hide();
            });
            $(".close-menu").click(function(e){
                e.preventDefault();
                $(".class-menu").removeClass("class-menu-animate");
                $('.current-class i ').show();
            });
            
        })();
	}

    // Public methods
	return {
		init: function() {

            // Initialize
            _init();
		},
	};
}();

var LMSScript = function() {

	// Initialize
	var _init = function() {
        
        (function(){
            
            $('.collapse.in').prev('.panel-heading').addClass('active');
            $('#accordion, #bs-collapse')
                .on('show.bs.collapse', function(a) {
                $(a.target).prev('.panel-heading').addClass('active');
                })
                .on('hide.bs.collapse', function(a) {
                $(a.target).prev('.panel-heading').removeClass('active');
            });
            
        //      Include SVG IMG
            jQuery('img.svg').each(function(){
                var $img = jQuery(this);
                var imgID = $img.attr('id');
                var imgClass = $img.attr('class');
                var imgURL = $img.attr('src');
            
                jQuery.get(imgURL, function(data) {
                    // Get the SVG tag, ignore the rest
                    var $svg = jQuery(data).find('svg');
            
                    // Add replaced image's ID to the new SVG
                    if(typeof imgID !== 'undefined') {
                        $svg = $svg.attr('id', imgID);
                    }
                    // Add replaced image's classes to the new SVG
                    if(typeof imgClass !== 'undefined') {
                        $svg = $svg.attr('class', imgClass+' replaced-svg');
                    }
            
                    // Remove any invalid XML tags as per http://validator.w3.org
                    $svg = $svg.removeAttr('xmlns:a');
                    
                    // Check if the viewport is set, else we gonna set it if we can.
                    if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                        $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
                    }
            
                    // Replace image with new SVG
                    $img.replaceWith($svg);
            
                }, 'xml');
            
            });
            /*Preload*/
            var $preloader = $('#page-preloader'),
            $spinner   = $preloader.find('.spinner-loader');
            $spinner.fadeOut();
            $preloader.delay(50).fadeOut('slow');
            
        })();

        $('.chart').easyPieChart({
                    //your options goes here
                barColor:"#b86d40",
                trackColor:"#2a2933",
                lineWidth:2,
                lineCap:'circle',
                scaleColor:false,
                size:35
        });

        $(".ressource-begin").click(function(e){
            e.preventDefault();
            $(".ressource-intro").fadeOut(100, function() {
                $('.ressource-content').show();
            });
        });
	}

    // Public methods
	return {
		init: function() {

            // Initialize
            _init();
		},
	};
}();

// Webpack support
if (typeof module !== 'undefined') {
	module.exports = LMSScript;
}