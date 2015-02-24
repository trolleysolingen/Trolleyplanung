$(function () {

    Highcharts.data({
        csv: document.getElementById('tsv').innerHTML,
        itemDelimiter: '\t',
        parsed: function (columns) {

            var brands = {},
                brandsData = [],
                versions = {},
                drilldownSeries = [];

            // Parse percentage strings
            columns[1] = $.map(columns[1], function (value) {
                value = parseFloat(value);
                return value;
            });
			
			var oneMonth = "";

            $.each(columns[0], function (i, name) {
                var brand,
                    version;

                if (i > 0) {

                    // Split into brand and version
                    version = name.match(/([0-9]+[\.0-9x]*)/);
                    if (version) {
                        version = version[0];
                    }
                    brand = name.replace(version, '');

                    // Create the main data
                    if (!brands[brand]) {
                        brands[brand] = columns[1][i];
                    } else {
                        brands[brand] += columns[1][i];
                    }

                    // Create the version data
                    if (version !== null) {
                        if (!versions[brand]) {
                            versions[brand] = [];
                        }
						var monthYear = version.split(".");
						var month = "";
						var bla = monthYear[0];
						switch (bla) {
							case '01':
								month = "Januar";
								break;
							case '02':
								month = "Februar";
								break;
							case '03':
								month = "März";
								break;
							case '04':
								month = "April";
								break;
							case '05':
								month = "Mai";
								break;
							case '06':
								month = "Juni";
								break;
							case '07':
								month = "Juli";
								break;
							case '08':
								month = "August";
								break;
							case '09':
								month = "September";
								break;
							case '10':
								month = "Oktober";
								break;
							case '11':
								month = "November";
								break;
							case '12':
								month = "Dezember";
								break;
						}
                        versions[brand].push([month + ' ' + monthYear[1], columns[1][i]]);
						oneMonth = month + ' ' + monthYear[1];
                    }
                }

            });

            $.each(brands, function (name, y) {
                brandsData.push({
                    name: name,
                    y: y,
                    drilldown: versions[name] ? name : null
                });
            });
			
			//Delete to prevent clicking
			if(columns[0].length > 6) {
				$.each(versions, function (key, value) {
					drilldownSeries.push({
						name: key,
						id: key,
						data: value
					});
				});
			}
			
			var title = "";
			if(columns[0].length > 6) {
				title = 'Bericht der letzten 12 Monate';
				desc = 'Klicke auf die einzelne Spalte um eine detaillierte Ansicht zu bekommen.';
			} else {
				title = 'Bericht vom ' + oneMonth;
				desc = '';
			}

            // Create the chart
            $('#container').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
					text: title
                },
                subtitle: {
                    text: desc
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: ''
                    }
                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
                },

                series: [{
                    name: 'Jahresübersicht',
                    colorByPoint: true,
                    data: brandsData
                }],
                drilldown: {
                    series: drilldownSeries
                }
            });
        }
    });
});