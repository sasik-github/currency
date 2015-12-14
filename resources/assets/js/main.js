/**
 * Created by sasik on 12/14/15.
 */
var global;
d3.json('/api/currency', function (error, data) {

    var margin = {top: 40, right: 40, bottom: 40, left:40},
        width = 5000,
        height = 500;


    var x = d3.time.scale()
        .domain([new Date(data[0].date), d3.time.day.offset(new Date(data[data.length - 1].date), 1)])
        .rangeRound([0, width - margin.left - margin.right]);

    var y = d3.scale.linear()
        .domain([0, d3.max(data, function(d) { return d.usd; })])
        .range([height - margin.top - margin.bottom, 0]);

    var xAxis = d3.svg.axis()
        .scale(x)
        .orient('bottom')
        .ticks(d3.time.days, 1)
        .tickFormat(d3.time.format('%a %d'))
        .tickSize(0)
        .tickPadding(8);

    var yAxis = d3.svg.axis()
        .scale(y)
        .orient('left')
        .tickPadding(8);

    var zoom = d3.behavior.zoom()
        .x(x)
        .y(y)
        .scaleExtent([1, 32])
        .on("zoom", zoomed);

    var svg = d3.select('.container')
        .append('svg')
        .attr('width', width)
        .attr('height', height)
        .append('g')
        .attr('transform', 'translate(' + margin.left + ', ' +  margin.top +')')
        .call(zoom);



    svg.selectAll('.chart')
        .data(data)
        .enter().append('rect')
        .attr('class', 'bar')
        .attr('x', function(d) { return x(new Date(d.date)); })
        .attr('y', function(d) { return height - margin.top - margin.bottom - (height - margin.top - margin.bottom - y(d.usd)) })
        .attr('width', 30)
        .attr('height', function(d) { return height - margin.top - margin.bottom - y(d.usd) })
        ;

    svg.append('g')
        .attr('class', 'x axis')
        .attr('transform', 'translate(0, ' + (height - margin.top - margin.bottom) + ')')
        .call(xAxis);

    svg.append('g')
        .attr('class', 'y axis')
        .call(yAxis);




    function zoomed() {
        svg.select(".x.axis").call(xAxis);
        svg.select(".y.axis").call(yAxis);
    }
});