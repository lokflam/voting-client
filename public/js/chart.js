if(total > 0) {
    var data = [];
    var start = '';
    for(var key in coordinates) {
        if(!coordinates.hasOwnProperty(key)) {
            continue;
        }

        data.push({
            type: "scatter",
            mode: "lines+markers",
            name: key,
            x: coordinates[key].x,
            y: coordinates[key].y,
        });

        start = coordinates[key].x[coordinates[key].x.length-1];
    }

    var layout = {
        title: 'Ballot counts',
        xaxis: {
            autorange: true,
            rangeselector: {buttons: [{step: 'all'}]},
            rangeslider: {autorange: true},
            type: 'date'
        },
        yaxis: {
            autorange: true,
            type: 'linear'
        }
    };

    Plotly.newPlot('chart', data, layout, {displayModeBar: false, responsive: true});

    var vote_rate_data = [{
        values: [casted, (total-casted)],
        labels: ['casted', 'not casted'],
        type: 'pie'
    }];
    
    Plotly.newPlot('pie', vote_rate_data, {title: 'Vote rate'}, {displayModeBar: false, responsive: true});

    var cnt = 0;
    var interval = setInterval(function() {
        $.get(base_url+'/result/update?vote_id='+vote_id+'&start='+start, function(result) {
            if(result.success) {
                var new_x = [];
                var new_y = [];
                var coordinates = result.data.coordinates;
                for(var key in coordinates) {
                    if(!coordinates.hasOwnProperty(key)) {
                        continue;
                    }
                    
                    if(coordinates[key].x.length > 0) {
                        new_x.push(coordinates[key].x);
                        new_y.push(coordinates[key].y);
                    }
                }

                if(new_x.length > 0) {
                    var indices = [];
                    for(var i = 0; i < new_x.length; i++) {
                        indices.push(i);
                    }

                    Plotly.extendTraces('chart', {
                        x: new_x,
                        y: new_y
                    }, indices);

                    start = new_x[new_x.length-1];
                }

                Plotly.restyle('pie', 'values', [[result.data.casted, result.data.total-result.data.casted]]);
            }
        });
    
        if(cnt === 100) clearInterval(interval);
    }, 5000);
}
