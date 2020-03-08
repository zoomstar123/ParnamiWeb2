'use strict';

/*-----------------------------------------------------------------------------------*/
/*  On screen jQuery functions
/*-----------------------------------------------------------------------------------*/

jQuery.fn.isOnScreen = function() {
    var win = jQuery(window);

    var viewport = {
        top: win.scrollTop(),
        left: win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();

    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();

    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
};

jQuery(function($) {
    /*-----------------------------------------------------------------------------------*/
  /*  Tabs
  /*-----------------------------------------------------------------------------------*/

    $('.nav-tabs a').on('click', function(e) {
        e.preventDefault()
        $(this).tab('show')
    });

    /*-----------------------------------------------------------------------------------*/
  /*  Equal height
  /*-----------------------------------------------------------------------------------*/

    function equalHeight() {
        function setHeight(column) {
            var setHeight = $(column).outerHeight();
            $('> .wpb_column > .vc_column-inner', column).css('min-height', setHeight);
        }

        var child_height = 0;
        var column_height = 0;

        if ($('.site-wrapper').hasClass('legacy')) {
            $('.wpb_row.double-column').each(function() {
                $('.row .row > .wpb_column', this).each(function() {
                    if ($(this).outerHeight() > column_height) {
                        column_height = $(this).outerHeight();
                    }
                });
                $('.row', this).css("min-height", column_height);
            });
        } else {
            $('.wpb_row.double-column').each(function() {
                $('> .wpb_column > .vc_column-inner', this).css("min-height", column_height);
                setHeight(this);
            });
        }
    }

    $(window).load(equalHeight);
    $(window).resize(equalHeight);

    /*-----------------------------------------------------------------------------------*/
  /*  Portfolio
  /*-----------------------------------------------------------------------------------*/
    try {
        $('.isotope').each(function() {
            var $container = $(this);
            var $filter = $container.parents('.wpb_column').find('.filter');
            if ($container.length && !$container.hasClass('.random')) {
                var first_scroll = true;
                $(window).scroll(function () {
                    if (first_scroll) {
                        $container.isotope();
                        first_scroll = false;
                    }
                });
                $(window).focus(function () {
                    $container.isotope();
                });
                $container.isotope({
                    itemSelector: '.isotope li',
                    layoutMode: 'fitRows',
                    animationOptions: {
                        duration: 750,
                        queue: false,
                    }
                });
                $filter.find('button').on('click', function () {
                    $(this).parents('.filter').find('button').removeClass('selected');
                    $(this).addClass("selected");
                    var item = "";
                    if ($(this).attr('data-filter') != '*') {
                        item = ".";
                    }
                    item += $(this).attr('data-filter');
                    $container.isotope({
                        filter: item
                    });
                });
                $(window).resize(function () {
                    if ($container.length && $filter.find('button.selected').length) {
                        var item = "";
                        if ($filter.find('button.selected').attr('data-filter') != '*') {
                            item = ".";
                        }
                        item += $filter.find('button.selected').attr('data-filter');
                        $container.isotope({
                            filter: item
                        });
                        $container.isotope('layout');
                    }
                });
                $(document).ready(function () {
                    $(window).load(function () {
                        $container.isotope('layout');
                    });
                });
            }
        });
    } catch (e) {}


    /* Portfolio Random */
    try {
        var $containerRandom = $('.isotope.random');
        if ($containerRandom.length) {
            var first_scroll = true;
            $(window).scroll(function() {
                if (first_scroll) {
                    $containerRandom.isotope();
                    first_scroll = false;
                }
            });
            $(window).focus(function() {
                $containerRandom.isotope();
            });
            $containerRandom.isotope({
                itemSelector: '.isotope li',
                layoutMode: 'masonry',
                masonry: {
                    columnWidth: 292
                }
            });
            $('.filter button').on('click', function() {
                $('.filter button').removeClass('selected');
                $(this).addClass("selected");
                var item = "";
                if ($(this).attr('data-filter') != '*') {
                    item = ".";
                }
                item += $(this).attr('data-filter');
                $containerRandom.isotope({
                    filter: item
                });
            });
            $(window).resize(function() {
                var $containerRandom = $('.isotope.random');

                if ($(".isotope").length && $('.filter button.selected').length) {
                    var item = "";
                    if ($('.filter button.selected').attr('data-filter') != '*') {
                        item = ".";
                    }
                    item += $('.filter button.selected').attr('data-filter');
                    $containerRandom.isotope({
                        filter: item
                    });

                    $(".isotope").isotope('layout');

                    if ($('.isotope').width() > 1140) {
                        $containerRandom.isotope({
                            masonry: {
                                columnWidth: 292
                            },
                            layoutMode: 'masonry',
                        });
                    } else if ($('.isotope').width() > 940) {
                        $containerRandom.isotope({
                            masonry: {
                                columnWidth: 242
                            },
                            layoutMode: 'masonry',
                        });
                    } else {
                        $containerRandom.isotope({
                            layoutMode: 'fitRows',
                        });
                    }
                } else {
                    var $containerRandom = $('.isotope.random');
                    $containerRandom.isotope({
                        layoutMode: 'fitRows'
                    });
                }
            });

            if ($('.isotope').width() > 1140) {
                $containerRandom.isotope({
                    masonry: {
                        columnWidth: 292
                    },
                    layoutMode: 'masonry',
                });
            } else if ($('.isotope').width() > 940) {
                $containerRandom.isotope({
                    masonry: {
                        columnWidth: 242
                    },
                    layoutMode: 'masonry',
                });
            } else {
                $containerRandom.isotope({
                    layoutMode: 'fitRows',
                });
            }
            $(document).ready(function() {
                $(window).load(function() {
                    $(".isotope").isotope('layout');
                });
            });
        }
    } catch (e) {}

    /*-----------------------------------------------------------------------------------*/
  /*  Blog masonry
  /*-----------------------------------------------------------------------------------*/

    var $containerMasonry = $('.blog-masonry');
    
    if ($containerMasonry.length) {
        $(window).on('load', function() {
            $containerMasonry.isotope({
                itemSelector: '.blog-masonry .post',
                animationOptions: {
                    duration: 450,
                    queue: false,
                }
            });
    
            $containerMasonry.isotope('layout');
        });
    
        $(window).on('resize', function() {
            $containerMasonry.isotope('layout');

            setTimeout(function() {
                $containerMasonry.isotope('layout');
            }, 500);
        });
    }

    /*-----------------------------------------------------------------------------------*/
  /*  Twitter
  /*-----------------------------------------------------------------------------------*/

    try {
        $("[data-twitter]").each(function(index) {
            var el = $("[data-twitter]").eq(index);
            $.ajax({
                type: "POST",
                url: 'http://localhost:8004/assets/php/twitter.php',
                data: {
                    account: el.attr("data-twitter")
                },
                success: function(msg) {
                    el.find(".carousel-inner").html(msg);
                }
            });

        });
    } catch (e) {}

    /*-----------------------------------------------------------------------------------*/
  /*  On screen
  /*-----------------------------------------------------------------------------------*/

    function checkForOnScreen() {
        $('.counter-number').each(function(index) {
            if (!$(this).hasClass('animated') && $('.counter-number').eq(index).isOnScreen()) {
                $('.counter-number').eq(index).countTo({
                    speed: 5000
                });
                $('.counter-number').eq(index).addClass('animated');
            }
        });
    }

    checkForOnScreen();
    $(window).scroll(checkForOnScreen);

    /*-----------------------------------------------------------------------------------*/
  /*  Fullscreen
  /*-----------------------------------------------------------------------------------*/

    if ($(window).height > 700) {
        $('.fullscreen').css('height', window.innerHeight); //menu position on home page
    }

    /*-----------------------------------------------------------------------------------*/
  /*  Site search
  /*-----------------------------------------------------------------------------------*/

    $('.site-search-close').on('click', function() {
        $('.site-wrapper').removeClass('site-search-opened');
    });

    $('.site-search-toggle').on('click', function() {
        if( !$('.site-search-opened').length ) {
          $(window).scrollTop(0);
        }

        $('.site-wrapper').toggleClass('site-search-opened');
    });

    /*-----------------------------------------------------------------------------------*/
  /*  Mobile menu toggle
  /*-----------------------------------------------------------------------------------*/

    $('.navbar-toggle').on('click', function() {
      $('.site-navigation').toggleClass('site-navigation-opened');
        if ($('.site-navigation-opened').length) {
            mobileMenuOpened = true;
            $('.site-header-sticky-active').removeClass('site-header-sticky-active');
            $(window).scrollTop(0);
        } else {
            mobileMenuOpened = false;
        }
    });

    /*-----------------------------------------------------------------------------------*/
  /*  Animated submenu
  /*-----------------------------------------------------------------------------------*/

    if( !$('.menu-item-depth-0').length ) {
      $('#menu-main-menu > li').addClass('menu-item-depth-0');
    }

    if( $('.site-search-toggle') && !$('.site-search-toggle').hasClass('hidden-sm') ) {
      $('.cartwrap').addClass('cart-search-space');
    }

    function submenuHeight() {
      $('.menu-item-depth-0 > .sub-menu').each(function() {
        $(this).css({
          'display' : 'none',
          'height'  : 'auto'
        });
        $(this).attr('data-height', $(this).height());
        $(this).attr('style', '');
      });
    }

    if( window.innerWidth > 991 ) {
      submenuHeight();
    }

    $(window).on('resize', submenuHeight);

    $('.menu-item-depth-0 > a').on('mouseenter', function() {
      if( window.innerWidth > 991 ) {
        var $subMenu = $(this).siblings('.sub-menu');
        $subMenu.css('height', $subMenu.attr('data-height'));
      }
    });

    $('.menu-item-depth-0 > .sub-menu ').on('mouseenter', function() {
      if( window.innerWidth > 991 ) {
        $(this).css('height', $(this).attr('data-height'));
        $(this).css('overflow', 'visible');
      }
    });

    $('.menu-item-depth-0 > a').on('mouseleave', function() {
      var $subMenu = $(this).siblings('.sub-menu');
      $subMenu.attr('style', '');
    });

    $('.menu-item-depth-0 > .sub-menu').on('mouseleave', function() {
      $(this).attr('style', '');
    });

    /*-----------------------------------------------------------------------------------*/
  /*  Sticky
  /*-----------------------------------------------------------------------------------*/

    var topOffset;
    var $wpBar = $('#wpadminbar');
    var $siteHeader = $('.site-header');
    var mobileMenuOpened = false;

    function changeTopOffset() {
      topOffset = $siteHeader.offset().top;

      if( $('.site-header-style-transparent').length && $('.top-bar').length ) {
          topOffset = $('.top-bar').innerHeight() + $('.nav-wrap').css('top').replace('px', '')*1;

          if (window.innerWidth < 600) {
              topOffset = $wpBar.height() +  $('.top-bar').innerHeight();
          }
      }

      if (window.innerWidth > 600) {
          topOffset -= $wpBar.height();
      }

      /* Full screen menu type */
      if ($('.site-header-style-full-width, .site-header-style-boxed').length) {
          if (window.innerWidth > 991) {
              topOffset += $('.preheader-wrap').height();
          } else {
              topOffset = 0;

              if (window.innerWidth < 600) {
                  topOffset += $('#wpadminbar').height();
              }

              if ($('.site-search-opened').length) {
                  topOffset += $('.site-search').height();
              }
          }

          if( $('.site-header-style-boxed').length ) {
              topOffset -= $('.nav-bar-wrapper').height() / 2;
          }
      }
    }

    function stickyHeader() {
      if($('.site-header-sticky').length && $(window).scrollTop() > topOffset && (!mobileMenuOpened || window.innerWidth > 991)) {
        $siteHeader.addClass('site-header-sticky-active');
      } else {
        $siteHeader.removeClass('site-header-sticky-active');
      }
    }

    if($('.site-header-sticky').length) {
      $(window).on('resize', changeTopOffset);
      $(window).on('scroll', changeTopOffset);
      changeTopOffset();

      $(window).on('scroll', stickyHeader);
      stickyHeader();
    }

    /*-----------------------------------------------------------------------------------*/
  /*  Top bar
  /*-----------------------------------------------------------------------------------*/

    function topBarSize() {
      $('.top-bar .container').css('height', $('.top-bar-left').innerHeight() + $('.top-bar-right').innerHeight() + 15);
    }

    $('.top-bar-close').on('click', function() {
      if( !$('.top-bar .container').attr('style') ) {
        topBarSize();
        $('.top-bar').addClass('top-bar-show').removeClass('top-bar-hide');
      } else {
        $('.top-bar .container').attr('style', '');
        $('.top-bar').removeClass('top-bar-show').addClass('top-bar-hide');
      }

      $(this).trigger('blur');
    });

    $(window).on('resize', function() {
      changeTopOffset();

      if( window.innerWidth > 991 ) {
        $('.top-bar .container').attr('style', '');
        $('.top-bar').removeClass('top-bar-show').removeClass('top-bar-hide');
      } else {
        if( $('.top-bar-show').length ) {
          topBarSize();
        }
      }
    });

    /*-----------------------------------------------------------------------------------*/
  /*  Navigation links (smooth scroll)
  /*-----------------------------------------------------------------------------------*/

    $('.site-navigation a[href*="#"]:not([href="#"]):not([href*="="])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') ||
            location.hostname == this.hostname) {
            var hash = this.hash.replace(/&/g, '');
        
            var target = $(hash);
            var href = $.attr(this, 'href');
            target = target.length ? target : $('[name=' + hash.slice(1) + ']');
            if (target.length) {
                var $targetoffset = target.offset().top - $('.nav-wrap').outerHeight(true) + 20;
    
                $('html,body').animate({
                    scrollTop: $targetoffset
                }, 1000);
                return false;
            }
        }
    });

    /*-----------------------------------------------------------------------------------*/
  /*  Waypoints
  /*-----------------------------------------------------------------------------------*/

    if ($('body').hasClass('home')) {
        var navLinkIDs = '';

        $('.site-navigation a[href*="#"]:not([href="#"]):not([href*="="])').each(function(index) {
            if (navLinkIDs != "") {
                navLinkIDs += ", ";
            }
            var temp = $('.site-navigation a[href*="#"]:not([href="#"]):not([href*="="])').eq(index).attr('href').split('#');
            navLinkIDs += '#' + temp[1].replace(/&/g, '');
        });
        
        if (navLinkIDs) {
            $(navLinkIDs).waypoint(function(direction) {
                if (direction == 'down') {
                    $('.site-navigation a').parent().removeClass("current_page_item");
                    $('.site-navigation a[href="#' + $(this).attr('id') + '"]').parent().addClass('current_page_item');
                }
            }, {
                offset: 125
            });

            $(navLinkIDs).waypoint(function(direction) {
                if (direction == 'up') {
                    $('.site-navigation a').parent().removeClass("current_page_item");
                    $('.site-navigation a[href="#' + $(this).attr('id') + '"]').parent().addClass("current_page_item");
                }
            }, {
                offset: function() {
                    return -$(this).height() + 20;
                }
            });
        }
    }

    /*-----------------------------------------------------------------------------------*/
  /*  WordPress specific
  /*-----------------------------------------------------------------------------------*/
    // Comment buttons
    $('button[data-form="clear"]').on('click', function() {
        $('textarea, input[type="text"]').val('');
    });
    $('button[data-form="submit"]').on('click', function() {
        $('.form-submit #submit').click();
    });
    // Search widget
    $('.widget_product_search form').addClass('searchform');
    $('.searchform input[type="submit"]').remove();
    $('.searchform div').append('<button type="submit" class="fa fa-search" id="searchsubmit" value=""></button>');
    $('.searchform input[type="text"]').attr('placeholder', anps.search_placeholder);

    $('.blog-masonry').parent().removeClass('col-md-12');
    $('.post.style-3').parent().parent().removeClass('col-md-12').parent().removeClass('col-md-12');

    if (jQuery.fn.prettyPhoto) {
      $("a[rel^='prettyPhoto']").prettyPhoto();
    }


    $('.show-register').on('click', function() {
        $('#customer_login h3, #customer_login .show-register').addClass('hidden');
        $('#customer_login .register').removeClass('hidden');
    });

    $('[data-form="submit"]').on('click', function(e) {
        $(this).parents('form.contact-form').submit();
        e.preventDefault();
    });

    $('body').on('added_to_cart',function(e) {
    $('.added_to_cart').addClass('btn btn-md style-3');
  });

    /*-----------------------------------------------------------------------------------*/
  /*  Lightbox for VC
  /*-----------------------------------------------------------------------------------*/

    $('.wpb_single_image .wpb_wrapper a[href$=".jpg"], .wpb_single_image .wpb_wrapper a[href$=".png"], .wpb_single_image .wpb_wrapper a[href$=".gif"]').attr('rel', 'lightbox');

    $(document).ready(function() {
        $('.parallax-window[data-type="background"]').each(function() {
            var $bgobj = $(this); // assigning the object

            $(window).scroll(function() {
                var yPos = -($(window).scrollTop() / $bgobj.data('speed'));

                // Put together our final background position
                var coords = '50% ' + yPos + 'px';

                // Move the background
                $bgobj.css({
                    backgroundPosition: coords
                });
            });
        });
    });


    /*-----------------------------------------------------------------------------------*/
  /*  Revolution Slider Parallax
  /*-----------------------------------------------------------------------------------*/

    $(document).ready(function() {
        $('.paraslider .tp-bgimg.defaultimg').each(function() {
            var $bgobj = $(this); // assigning the object

            $(window).scroll(function() {
                var yPos = -($(window).scrollTop() / 5);

                // Put together our final background position
                var coords = '50% ' + yPos + 'px';

                // Move the background
                $bgobj.css({
                    backgroundPosition: coords
                });
            });
        });
    });
});


/*-----------------------------------------------------------------------------------*/
/*  Lightbox for VC
/*-----------------------------------------------------------------------------------*/

/* fix horizontal height of boxes */

jQuery(document).ready(function($) {
    $('#menu-main-menu').doubleTapToGo();

    $(function() {
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                $('#scrolltop').fadeIn();
            } else {
                $('#scrolltop').fadeOut();
            }
        });

        // scroll body to 0px on click
        $('#scrolltop button').on('click', function() {
            $('body, html').animate({
                scrollTop: 0
            }, 800);
        });
    });


    if ($('.owl-carousel').length) {
        $('.owl-carousel').each(function() {
            var owl = $(this);
            var number_items = $(this).attr("data-col");
            var loop = true;
            if (number_items >= owl.children().length) {
                loop = false;
                owl.parents('.wpb_column').find('.owlprev, .owlnext').hide();
            }
            owl.owlCarousel({
                loop: loop,
                margin: 30,
                responsiveClass: true,
                nav: owl.data('nav') !== undefined,
                navText: [
                    '<i class="fa fa-chevron-left"></i>',
                    '<i class="fa fa-chevron-right"></i>',
                ],
                responsive: {
                    0: {
                        items: 1,
                        slideBy: 1
                    },
                    600: {
                        items: 2,
                        slideBy: 2
                    },
                    992: {
                        items: number_items,
                        slideBy: number_items
                    }
                }
            })

            owl.siblings().find('.owlnext').on('click', function() {
                owl.trigger('next.owl.carousel');
            });

            owl.siblings().find('.owlprev').on('click', function() {
                owl.trigger('prev.owl.carousel');
            });
        });
    }
});

/*-----------------------------------------------------------------------------------*/
/*  Overwriting the vc row behaviour function for the vertical menu
/*-----------------------------------------------------------------------------------*/

if (typeof window['vc_rowBehaviour'] !== 'function') {
  window.vc_rowBehaviour = function() {
    function fullWidthRow() {
      var $elements = $('[data-vc-full-width="true"]');
      $.each($elements, function(key, item) {
        /* Anpthemes */
        var verticalOffset = 0;
        if( $('.site-header-vertical-menu').length && window.innerWidth > 992 ) {
          verticalOffset = $('.site-header-vertical-menu').innerWidth();
        }

        var boxedOffset = 0;
        if( $('body.boxed').length && window.innerWidth > 992 ) {
          boxedOffset = ($('body').innerWidth() - $('.site-wrapper').innerWidth()) / 2;
        }

        var $el = $(this);
        $el.addClass("vc_hidden");
        var $el_full = $el.next(".vc_row-full-width");
        $el_full.length || ($el_full = $el.parent().next(".vc_row-full-width"));
        var el_margin_left = parseInt($el.css("margin-left"), 10)
          , el_margin_right = parseInt($el.css("margin-right"), 10)
          , offset = 0 - $el_full.offset().left - el_margin_left
          , width = $(window).width() - verticalOffset - boxedOffset*2;
        if ($el.css({
          position: "relative",
          left: offset + verticalOffset + boxedOffset,
          "box-sizing": "border-box",
          width: width
        }),
        !$el.data("vcStretchContent")) {
          var padding = -1 * offset - verticalOffset - boxedOffset;
          0 > padding && (padding = 0);
          var paddingRight = width - padding - $el_full.width() + el_margin_left + el_margin_right;
          0 > paddingRight && (paddingRight = 0),
          $el.css({
            "padding-left": padding + "px",
            "padding-right": paddingRight + "px"
          })
        }
        $el.attr("data-vc-full-width-init", "true"),
        $el.removeClass("vc_hidden")
      }),
      $(document).trigger("vc-full-width-row", $elements)
    }
    function parallaxRow() {
      var vcSkrollrOptions, callSkrollInit = !1;
      return window.vcParallaxSkroll && window.vcParallaxSkroll.destroy(),
      $(".vc_parallax-inner").remove(),
      $("[data-5p-top-bottom]").removeAttr("data-5p-top-bottom data-30p-top-bottom"),
      $("[data-vc-parallax]").each(function() {
        var skrollrSpeed, skrollrSize, skrollrStart, skrollrEnd, $parallaxElement, parallaxImage, youtubeId;
        callSkrollInit = !0,
        "on" === $(this).data("vcParallaxOFade") && $(this).children().attr("data-5p-top-bottom", "opacity:0;").attr("data-30p-top-bottom", "opacity:1;"),
        skrollrSize = 100 * $(this).data("vcParallax"),
        $parallaxElement = $("<div />").addClass("vc_parallax-inner").appendTo($(this)),
        $parallaxElement.height(skrollrSize + "%"),
        parallaxImage = $(this).data("vcParallaxImage"),
        youtubeId = vcExtractYoutubeId(parallaxImage),
        youtubeId ? insertYoutubeVideoAsBackground($parallaxElement, youtubeId) : "undefined" != typeof parallaxImage && $parallaxElement.css("background-image", "url(" + parallaxImage + ")"),
        skrollrSpeed = skrollrSize - 100,
        skrollrStart = -skrollrSpeed,
        skrollrEnd = 0,
        $parallaxElement.attr("data-bottom-top", "top: " + skrollrStart + "%;").attr("data-top-bottom", "top: " + skrollrEnd + "%;")
      }),
      callSkrollInit && window.skrollr ? (vcSkrollrOptions = {
        forceHeight: !1,
        smoothScrolling: !1,
        mobileCheck: function() {
          return !1
        }
      },
      window.vcParallaxSkroll = skrollr.init(vcSkrollrOptions),
      window.vcParallaxSkroll) : !1
    }
    function fullHeightRow() {
      var $element = $(".vc_row-o-full-height:first");
      if ($element.length) {
        var $window, windowHeight, offsetTop, fullHeight;
        $window = $(window),
        windowHeight = $window.height(),
        offsetTop = $element.offset().top,
        windowHeight > offsetTop && (fullHeight = 100 - offsetTop / (windowHeight / 100),
        $element.css("min-height", fullHeight + "vh"))
      }
      $(document).trigger("vc-full-height-row", $element)
    }
    function fixIeFlexbox() {
      var ua = window.navigator.userAgent
        , msie = ua.indexOf("MSIE ");
      (msie > 0 || navigator.userAgent.match(/Trident.*rv\:11\./)) && $(".vc_row-o-full-height").each(function() {
        "flex" === $(this).css("display") && $(this).wrap('<div class="vc_ie-flexbox-fixer"></div>')
      })
    }
    var $ = window.jQuery;
    $(window).off("resize.vcRowBehaviour").on("resize.vcRowBehaviour", fullWidthRow).on("resize.vcRowBehaviour", fullHeightRow),
    fullWidthRow(),
    fullHeightRow(),
    fixIeFlexbox(),
    vc_initVideoBackgrounds(),
    parallaxRow()
  }
}

/*-----------------------------------------------------------------------------------*/
/* Select
/*-----------------------------------------------------------------------------------*/

jQuery('.wpcf7-form-control-wrap > select').each(function() {
    jQuery(this).parent('.wpcf7-form-control-wrap').addClass('anps-select-wrap');
});

/*-----------------------------------------------------------------------------------*/
/* Google Maps (using gmaps.js)
/*-----------------------------------------------------------------------------------*/

function isFloat(n) {
    return parseFloat(n.match(/^-?\d*(\.\d+)?$/)) > 0;
}

function checkCoordinates(str) {
    if (!str) {
        return false;
    }

    str = str.split(',');
    var isCoordinate = true;

    if (str.length !== 2 || !isFloat(str[0].trim()) || !isFloat(str[1].trim())) {
        isCoordinate = false;
    }

    return isCoordinate;
}

jQuery(function($) {
  	$('.map').each(function() {
	    /* Options */
	    var gmap = {
			zoom   : ($(this).attr('data-zoom')) ? parseInt($(this).attr('data-zoom')) : 15,
			address: $(this).attr('data-address'),
			markers: $(this).attr('data-markers'),
			icon   : $(this).attr('data-icon'),
			typeID : $(this).attr('data-type'),
			ID     : $(this).attr('id'),
			styles : $(this).attr('data-styles') ? JSON.parse($(this).attr('data-styles')): '',
	    };

	    var gmapScroll = ($(this).attr('data-scroll')) ? $(this).attr('data-scroll') : 'false';
	    var markersArray = [];
	    var bound = new google.maps.LatLngBounds();

	    if( gmapScroll == 'false' ) {
			gmap.draggable = false;
			gmap.scrollwheel = false;
	    }

	    if( gmap.markers ) {
	    	gmap.markers = gmap.markers.split('|');

	    	/* Get markers and their options */
			gmap.markers.forEach(function(marker) {
				if( marker ) {
					marker = $.parseJSON(marker);

					if( checkCoordinates(marker.address) ) {
						marker.position = marker.address.split(',');
						delete marker.address;
					}

					markersArray.push(marker);
				}
		    });

            /* Initialize map */
			$('#' + gmap.ID).gmap3({
                zoom       : gmap.zoom,
                draggable  : gmap.draggable,
                scrollwheel: gmap.scrollwheel,
                mapTypeId  : google.maps.MapTypeId[gmap.typeID],
                styles     : gmap.styles
            }).on({
                'tilesloaded': function() {
                    if (typeof window.anpsMapsLoaded !== 'undefined') {
                        window.anpsMapsLoaded();
                    }
                }
            }).marker(markersArray).then(function(results) {
                var center = null;

                window.anpsMarkers = results;

                if( typeof results[0].position.lat !== 'function' ||
                    typeof results[0].position.lng !== 'function' ) {
                    return false;
                }

                results.forEach(function(m, i) {
                    if( markersArray[i].center ) {
                        center = new google.maps.LatLng(m.position.lat(), m.position.lng());
                    } else {
                        bound.extend(new google.maps.LatLng(m.position.lat(), m.position.lng()));
                    }
                });

                if( !center ) {
                    center = bound.getCenter();
                }

                this.get(0).setCenter(center);
            }).infowindow({
                content: ''
            }).then(function (infowindow) {
                var map = this.get(0);
                this.get(1).forEach(function(marker) {
                    if( marker.data !== '' ) {
                        marker.addListener('click', function() {
                            infowindow.setContent(decodeURIComponent(marker.data));
                            infowindow.open(map, marker);
                        });
                    }
                });
            });
	    } else {
            console.error('No markers found. Add markers to the Google maps item using Visual Composer.');
        }
    });

    function resizeMiniCart() {
        if (window.innerWidth < 768) {
            $('.preheader-wrap .hidden-lg.cartwrap .mini-cart').width(window.innerWidth - 60);
        } else {
            $('.preheader-wrap .hidden-lg.cartwrap .mini-cart').attr('style', '');
        }
    }

    resizeMiniCart();

    $(window).on('resize', resizeMiniCart);

    /*-----------------------------------------------------------------------------------*/
    /* Visual Compoesr chart changes
    /*-----------------------------------------------------------------------------------*/

    /* Line chart */

    $('.anps_line-chart').each(function() {
        var data = JSON.parse($(this).attr('data-vc-values'));
        var chart = $(this).find('.anps_line-chart-canvas').get(0).getContext('2d');
        chart.canvas.width = $(this).parents(".wpb_column").width();
        chart.canvas.height = $(this).attr('data-anps-height') || 221;

        new Chart(chart).Line(data, {
            responsive: true
        });
    });

    /* Round chart */

    $('.anps_round-chart').each(function() {
        var data = JSON.parse($(this).attr('data-vc-values'));
        var chart = $(this).find('.anps_round-chart-canvas').get(0).getContext('2d');
        chart.canvas.width = $(this).parents(".wpb_column").width();
        chart.canvas.height = $(this).attr('data-anps-height') * 1.76 || 221;

        new Chart(chart).Pie(data, {
            responsive: true
        });
    });

    $('.vc_tta-style-anps-ts-2 .vc_tta-tabs-container.col-sm-3').each(function() {
        $(this).siblings('.col-sm-9').find('.vc_tta-panel-body > *').css('min-height', $(this).height() + 'px');
    });

    $('.product-header').on('click', 'a', function(e) {
      if( $(this).css('opacity') != 1 ) {
        e.preventDefault();
        return false;
      }
    });

    $('.tnp-email').attr('placeholder', $('.tnp-field label').text());
    $('.tnp-field-button').on('click', function (e) {
        if(e.target.nodeName == 'DIV') {
            $(this).find('.tnp-button').click();
        }
    });
});
