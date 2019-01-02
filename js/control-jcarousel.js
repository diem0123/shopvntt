$(document).ready(function(){
    $('#home_fading_notice').innerfade({
        animationtype: 'slide',
        speed: 750,
        timeout: 7000,
        type: 'sequence',
        containerheight: '20px'
    });
    
    $('#home_vip_store').jcarousel({
        auto: 3,
        wrap: 'circular',
        scroll: 4,
        animation: 1000,
        initCallback: mycarousel_initCallback
    });
});

function mycarousel_initCallback(carousel)
{
    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};