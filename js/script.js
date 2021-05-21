
//jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

// when the modal is opened...
$('#modal-fullscreen').on('show.bs.modal', function () {
  // call play() on the <video> DOM element 
  $('#video')[0].play()
})
// when the modal is opened...
$('#modal-fullscreen').on('hide.bs.modal', function () {
  // call pause() on the <video> DOM element 
  $('#video')[0].pause()
})
