/*
Flot plugin for thresholding data. Controlled through the option
"threshold" in either the global series options

  series: {
    threshold: [
    {
      min: number,
      max: number,
      color: colorspec
    },
    ...
    ]
  }

or in a specific series

  $.plot($("#placeholder"), [{ data: [ ... ], threshold: { ... }}])
*/

(function ($) {
    var options = {
        series: { threshold: null } // or { below: number, color: color spec}
    };
    
    function init(plot) {
        function thresholdData(plot, s, datapoints) {
            if (!s.threshold)
                return;
            
            var ps = datapoints.pointsize, k, i, x, y, p, prevp, prevk, interx, intery;
            var origpoints = datapoints.points;

            var ranges = s.threshold;

            var thresholded = [];

            var isStartSegment = false;

            for(k in ranges) {
                thresholded[k] = $.extend({}, s); // note: shallow copy

                if (k != 0) {
                    thresholded[k].datapoints = { points: [], pointsize: ps };
                } else {
                    thresholded[k].datapoints = datapoints;
                    thresholded[k].datapoints.points = [];
                }

                thresholded[k].label = null;
                thresholded[k].color = ranges[k].color;
                thresholded[k].threshold = null;
                thresholded[k].originSeries = s;
                thresholded[k].data = [];
            }

            for (i = 0; i < origpoints.length; i += ps) {
                x = origpoints[i];
                y = origpoints[i + 1];

                prevp = p;

                for(k in ranges) {
                    var min = ranges[k].min;
                    var max = ranges[k].max;

                    if (min <= y && y < max) {
                        p = thresholded[k].datapoints.points;
                        prevk = k;
                    }
                }

                if (prevp != p && x != null && i > 0 && origpoints[i - ps] != null) {
                    var interx_min = (x - origpoints[i - ps]) / (y - origpoints[i - ps + 1]) * (min - y) + x;
                    var interx_max = (x - origpoints[i - ps]) / (y - origpoints[i - ps + 1]) * (max - y) + x;

                    //if (ranges[prevk].max == min || ranges[prevk].min == min) {
                        // нет разрыва

                        if (ranges[prevk].max == min) {
                            // возрастание ф-ции

                            interx = interx_min;
                            intery = min;
                        } else {
                            // убывание ф-ции
                            interx = interx_max;
                            intery = max;
                        }

                        prevp.push(interx);
                        prevp.push(intery);
                        for (m = 2; m < ps; ++m)
                            prevp.push(origpoints[i + m]);

                        p.push(null); // start new segment
                        p.push(null);
                        for (m = 2; m < ps; ++m)
                            p.push(origpoints[i + m]);
                        p.push(interx);
                        p.push(intery);
                        for (m = 2; m < ps; ++m)
                            p.push(origpoints[i + m]);

                    //} else {


                    //}
                }

                p.push(x);
                p.push(y);
            }

            for (k in ranges) {
                if (thresholded[k].datapoints.points.length > 0) {
                    if (k != 0) {
                        plot.getData().push(thresholded[k]);
                    }
                }
            }

            //datapoints.points = newpoints;
            //thresholded.datapoints.points = threspoints;
        }
        
        plot.hooks.processDatapoints.push(thresholdData);
    }
    
    $.plot.plugins.push({
        init: init,
        options: options,
        name: 'thresholdext',
        version: '1.0'
    });
})(jQuery);
