<?php
/**
 * Timeline JavaScript helper using flot
 * 
 * This view uses Flot (http://code.google.com/p/flot/) to display data in a AJAX way
 * 
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2011
 * @link http://github.com/lowfill/libform_utils
 * 
 * @param $vars['start_date'] Initial timiline date
 * @param $vars['endpoint'] Data source to be called. The name is used for display a view 'timiline/<endpoint>_data. See libform_utils/views/default/timiline/* for examples
 * @param $vars['internalname'] An array with a row by each column to be displayed. See flexigrid documentation for all posible options
 * @param $vars['default_series'] An array with the series to be loaded by default
 */
// FIXME update to 1.8
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	    var start = new Date();
	    start.setYear('<?php echo $vars["start_date"]?>');
	    start.setMonth(0);
	    var end = new Date();

	    var geral_options = {
	        series:{
	            points: {show:true},
	            lines:{show:true,fill:true}
	        },
	        xaxis: { mode: "time",tickLength: 5,max: end.getTime()},
	        selection: { mode: "x" },
	        grid: { markings: weekendAreas,hoverable: true, autoHighlight: true },
	        crosshair: { mode: "x" },
	        legend: {position:'nw'}
	    };

	    var overview_options = {
	        series: {
	            lines: { show: true, lineWidth: 1 },
	            shadowSize: 0
	        },
	        xaxis: { ticks: [], mode: "time" ,min:start.getTime(),max: end.get},
	        yaxis: { ticks: [], min: 0, autoscaleMargin: 0.1 },
	        selection: { mode: "x" },
	        legend:{show:false}
	    }

	    var placeholder = $("#time_line_<?php echo $vars['internalname']?>");
	    var overviewholder = $("#time_line_overview_<?php echo $vars["internalname"]?>");

	    var data = [];
	    var overview_data = [];
	    var alreadyFetched = {};
	    var d = []; 

	    var updateLegendTimeout = null;
	    var latestPosition = null;

	    var plot = $.plot(placeholder, [d], geral_options);
	    var overview = $.plot(overviewholder, [d], overview_options);


	    function retrieveData(serie, zoom) {
			var url = '<?php echo $vars["url"]?>pg/timeline/<?php echo $vars["endpoint"];?>/?serie=' + serie + '&zoom=' + zoom;
	        if (!alreadyFetched[serie]) {
				
	            $.getJSON(url,
	                     function(series) {
	                         if (!series.moov_event) {
	                             series.label += ' ({d MMM YYYY}): {nnnn}';
	                         }
	                         series.zoom = zoom;
	                         alreadyFetched[serie] = series;
	                         data.push(alreadyFetched[serie]);
	                         overview_data.push(alreadyFetched[serie]);

	                         plot = $.plot(placeholder, data, geral_options);
	                         overview = $.plot(overviewholder, overview_data, overview_options);

	                     });
	        }
	        else {
	            data = [];
	            overview_data = [];
	            for (var fetched in alreadyFetched) {
	                if (fetched != serie && alreadyFetched[fetched].zoom == zoom) {
	                    data.push(alreadyFetched[fetched]);
	                    overview_data.push(alreadyFetched[fetched]);
	                }
	            }

	            data = ( data.length > 0 ) ? data : [d];
	            overview_data = ( overview_data.length > 0 ) ? overview_data : [d];

	            plot = $.plot(placeholder, data, geral_options);
	            overview = $.plot(overviewholder, overview_data, overview_options);
	            alreadyFetched[serie] = false;
	        }
	    }

	    function updateLegend() {
	        updateLegendTimeout = null;

	        var legends = $("#time_line_<?php echo $vars['internalname']?> .legendLabel");
	        legends.each(function () {
	            // fix the widths so they don't jump around
	            $(this).css('width', $(this).width());
	        });

	        var pos = latestPosition;
	        var axes = plot.getAxes();
	        if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max ||
	                pos.y < axes.yaxis.min || pos.y > axes.yaxis.max)
	            return;

	        var i, j, dataset = plot.getData();
	        for (i = 0; i < dataset.length; ++i) {
	            var series = dataset[i];
	            if (series.label != undefined && !series.moov_event) {
	                // find the nearest points, x-wise
	                for (j = 0; j < series.data.length; ++j)
	                    if (series.data[j][0] > pos.x)
	                        break;

	                // now interpolate
	                var d,y, p1 = series.data[j - 1], p2 = series.data[j];
	                if (p1 == null && p2 != null) {
	                    y = p2[1];
	                    d = new Date(p2[0]);
	                }
	                else {
	                    y = p1[1];
	                    d = new Date(p1[0]);
	                }
	                var x = $.plot.formatDate(d, '%d %b %y');
	                var legend = series.label.replace(/{nnnn}/, y);
	                legend = legend.replace(/{d MMM YYYY}/, x);
	                legends.eq(i).text(legend);
	            }
	        }
	    }

	    placeholder.bind("plothover", function (event, pos, item) {
	        latestPosition = pos;
	        if (!updateLegendTimeout) {
	            updateLegendTimeout = setTimeout(updateLegend, 50);
	        }
	    });

	    placeholder.bind('plotselected',function(event,ranges){
	        plot = $.plot(placeholder, data,$.extend(true, {}, geral_options, {
	                              xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to }
	                          }));
	        overview.setSelection(ranges,true);
	        zoomRanges = ranges;
	    });

	    overviewholder.bind('plotselected',function(event,ranges){
	        plot.setSelection(ranges);
	    });

	    $(".series").click(function(event) {
	    	var serie = $(this).attr('name');
	        var zoom = $('.zoom:checked').val();
	        //var color = $('.zoom:checked').data();
	        retrieveData(serie, zoom);
	    });

	    $(".zoom").change(function() {
	        $(".series:checked").each(function() {
	            var serie = $(this).attr('name');
	            var zoom = $('.zoom:checked').val();
	            alreadyFetched[serie] = false;
	            data = [];
	            overview_data = [];
	            retrieveData(serie, zoom);
	        });
	    });
	    <?php if(!empty($vars['default_series'])){
	    	foreach($vars['default_series'] as $serie){
	    ?>
	    $("#<?echo $serie;?>").trigger('click');
	    <?php
	     	} 
	    }
	    ?>
	    
	});

</script>