$(document).ready(function () {
    let cards = $("#allCard").children();
    let len = cards.length;
    let current = 0;

    $('#next').prop('disabled', true);
    //Validation : Disable next button if not valid
    cards.each((i, c) => {
        let selected = [];
        $(c).find('select').each((i, el)=>{
            $(el).on('change', function (e) {
                selected.includes($(this).attr('id')) ? selected.push() : selected.push($(this).attr('id'));
                if (selected.length === $(c).find('select').length){
                    $('#next').prop('disabled', false);
                }
            })
        });
    });

    if (len > 0){
        // console.log(len);
        $('#progressInfo').text(current+1+'/'+len);
        let elem = document.getElementById("myBar");
        let width = Math.floor(current*100/len);
        elem.style.width = width + '%';
        elem.innerHTML = width + '%';

        for (let i = 1; i < len; i++) {
            $(cards[i].tagName.toLocaleLowerCase()+'#'+cards[i].getAttribute('id')+'.'+
                cards[i].getAttribute('class')).css('display', 'none');
        }

        $("#next").on('click', function (ev) {
            ev.preventDefault();
            $(this).prop('disabled', true);
            //Bar rating validation
            $(cards[current]).find('select').each((i, el)=>{
               if (!$(el).val()) {
                   $(el).parent().css('border', '2px solid #FF0B20');
                   return false
               }
            });
            $('#progressInfo').text((current+2)+'/'+len);
            width = Math.floor((current + 2)*100/len);
            elem.style.width = width + '%';
            elem.innerHTML = width + '%';

            $(cards[current].tagName.toLocaleLowerCase()+'#'+cards[current].getAttribute('id')+'.'+
                cards[current].getAttribute('class'))
                .toggle("slide", {direction: "left"}, 500);
            current++;
            setTimeout(function () {
                $(cards[current].tagName.toLocaleLowerCase()+'#'+cards[current].getAttribute('id')+'.'+
                    cards[current].getAttribute('class'))
                    .toggle("slide", {direction: "right"}, 600);
            }, 600);
            $("#prev").css('display', 'inline');
            if(current === len-1) {
                $("#next").hide();
                $("#finish").css('display', 'inline');
            }
        });

        $("#prev").on('click', function (ev) {
            ev.preventDefault();
            $('#next').prop('disabled', false);
            width = Math.floor(current*100/len);
            elem.style.width = width + '%';
            elem.innerHTML = width + '%';

            $(cards[current].tagName.toLocaleLowerCase()+'#'+cards[current].getAttribute('id')+'.'+
                cards[current].getAttribute('class'))
                .toggle("slide", {direction: "right"}, 500);
            $('#progressInfo').text(current+'/'+len);
            current--;
            setTimeout(function () {
                $(cards[current].tagName.toLocaleLowerCase()+'#'+cards[current].getAttribute('id')+'.'+
                    cards[current].getAttribute('class'))
                    .toggle("slide", {direction: "left"}, 600);
            }, 600);
        });
        $("#next").css('display', 'inline');
        if (current === 0) {
            $("#prev").css('display', 'none');
            $("#next").css('display', 'inline');
        }
        if(current === len-2) {
            $("#finish").hide();
        }
    }else {
        $("#next").hide();
        $("#finish").show();
    }

});
    // $("#prev").click(function (ev) {
    //     ev.preventDefault();
    //     $(cards[current-1].tagName.toLocaleLowerCase()+'#'+cards[current-1].getAttribute('id')+'.'+
    //         cards[current-1].getAttribute('class')).show(500);
    //     $(cards[current].tagName.toLocaleLowerCase()+'#'+cards[current].getAttribute('id')+'.'+
    //         cards[current].getAttribute('class')).hide(500);
    //     current--;
    //     $("#next").css('display', 'inline');
    //
    //     if(current === 0) {
    //         $("#prev").css('display', 'none');
    //         $("#next").css('display', 'inline');
    //     }
    //     if(current === len-2) {
    //         $("#finish").css('display', 'none');
    //     }
    // });

    // function pass(index, cards) {
    //     let current_index = index, prev_index;
    //     let current_card = cards[index], prev_card;
    //     let attrs = current_card.tagName.toLocaleLowerCase()+'#'+current_card.getAttribute('id')+'.'+ current_card.getAttribute('class');
    //     //On cache le card courant
    //     $(attrs).hide();
    //     current_index = current_index + 1;
    //     current_card = cards[current_index];
    //     attrs = current_card.tagName.toLocaleLowerCase()+'#'+current_card.getAttribute('id')+'.'+ current_card.getAttribute('class');
    //     $(attrs).show();
    //     $(attrs).addClass('animated slideInRight');
    //
    //     //on cache le card precedent
    //     prev_index = current_index - 1;
    //     prev_card = cards[prev_index];
    //     prev_card.style.display="none";
    //
    //     $("#prev").css('display', 'inline');
    //     if(current_index === len-1) {
    //         $("#next").css('display', 'none');
    //         $("#finish").css('display', 'inline');
    //     }
    // }

    // function back(current_index, cards) {
    //     let current = current_index;
    //     let current_card = cards[current], next_card;
    //     let attrs = current_card.tagName.toLocaleLowerCase()+'#'+current_card.getAttribute('id')+'.'+ current_card.getAttribute('class');
    //
    //     attrs = attrs.split(" ")[0];
    //     $(attrs).removeClass("animated slideInRight").addClass("animated slideOutRight")
    //
    //     current = current - 1;
    //     next_card = cards[current];
    //     console.log(next_card);
    //     attrs = next_card.tagName.toLocaleLowerCase()+'#'+next_card.getAttribute('id')+'.'+ next_card.getAttribute('class');
    //     $(attrs).show();
    //
    // }
    // console.log($('.rating').children())
// });

$('#finish').on('click', function (ev) {
    // ev.preventDefault();
    // console.log($('#form').serializeArray().map(function (v) {
    //     return v;
    // }));
});
