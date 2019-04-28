$(function() {
    $('.notation').on('hide.bs.select', function (e) {
        console.log(e);
        $(this).trigger("focusout");
    });
    function ratingEnable() {
        $('.notation').barrating('show', {
            theme: 'bars-movie',
            initialRating: '',
            onSelect(value, text, event){
                $(event.target).parent().parent().css('border', '')
            }
        });
    }
    ratingEnable();
});
