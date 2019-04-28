$('#accordion').find('.card-header').each(function (i, el) {
    $(el).one('click', function () {
        let cardId = $(this).children().first()[0].id;
        let currentCardBody = $(el).siblings().find('.panel-body').find('.row');
        $('.lds-ripple').show('slow');
        $.ajax({
            url: Routing.generate('ue_result'),
            type: "POST",
            dataType: 'json',
            data: 'id='+cardId,
            crossDomain: true,
            success: function(rtnData) {
                let i = 0;
                $.each(rtnData['reponses'], (key, value)=>{
                    i++;
                    let canvas = $("<div class='col-md-6'><canvas></canvas></div>");
                    canvas.find('canvas').attr('id', 'bar-chart'+i+''+cardId);
                    // canvas.width = 300;
                    currentCardBody.append(canvas);
                    canvas = currentCardBody.find("#bar-chart"+i+''+cardId);

                    // Bar chart
                    new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(value),
                            datasets: [
                                {
                                    label: Object.values(value),
                                    backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                                    data: Object.values(value).map((v)=>{ return 100*v }),
                                }
                            ]
                        },
                        options: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: 'Q-'+i+')'+ ' ' + key
                            },
                            // responsive: false,
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        callback: function(tick) {
                                            return tick.toString() + '%';
                                        },
                                        beginAtZero: true,
                                        max: 100
                                    }
                                }],
                            },
                            tooltips: {
                                callbacks: {
                                    label: function(tooltipItem, data) {
                                        let label = '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += Math.round(tooltipItem.yLabel * 100) / 100 + '%';
                                        return label;
                                    }
                                }
                            }
                        }
                    });
                });
            },
            error: function(rtnData) {
                console.log(rtnData);
            },
            complete: function () {
                $('.lds-ripple').hide('slow');
            }
        });
    })
});