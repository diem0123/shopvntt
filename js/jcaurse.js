(function ($) {
    $(function () {
        var jcarousel = $('.slide-partner .jcarousel');
		
        jcarousel
            .on('jcarousel:reload jcarousel:create', function () {
                var width = jcarousel.innerWidth();

                if (width >= 950) {
                    width = width / 3;
                }
                else if (width >= 700) {
                    width = width / 3;
                }
                else if (width >= 500) {
                    width = width / 2;
                }

                jcarousel.jcarousel('items').css('width', width + 'px');
            })
            .jcarousel({
                wrap: 'circular'
            });

        $('.slide-partner .jcarousel').jcarouselAutoscroll({ autostart: false });
		
		var jcarouselfood = $('.slide-foods .jcarousel');
		jcarouselfood
            .on('jcarousel:reload jcarousel:create', function () {
                var width = jcarouselfood.innerWidth();

                if (width >= 1050) {
                    width = width / 4;
                }
                else if (width >= 950) {
                    width = width / 3;
                }
				else if (width >= 500) {
                    width = width / 2;
                }
                else if (width >= 400) {
                    width = width / 1;
                }

                jcarouselfood.jcarousel('items').css('width', width + 'px');
            })
            .jcarousel({
                wrap: 'circular'
            });
		$('.slide-foods .jcarousel').jcarouselAutoscroll({ autostart: false });

        $('.jcarousel-control-prev')
            .jcarouselControl({
                target: '-=1'
            });

        $('.jcarousel-control-next')
            .jcarouselControl({
                target: '+=1'
            });
    });
})(jQuery);