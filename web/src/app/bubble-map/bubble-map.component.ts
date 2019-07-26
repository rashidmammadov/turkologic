import { Component, Input, OnInit } from '@angular/core';
import { geoMap } from "../../assets/data/geo-map";
import * as d3 from 'd3';

let svg, tooltip;
@Component({
  selector: 'app-bubble-map',
  template: '',
  styleUrls: ['./bubble-map.component.css']
})
export class BubbleMapComponent implements OnInit {
  @Input() data: any;

  constructor() { }

  ngOnInit() {
    svg = d3.select('app-bubble-map').append('svg').attr('width', 600).attr('height', 300);
    let width = +svg.attr('width');
    let height = +svg.attr('height');

    let projection = d3.geoMercator()
      .center([0,20])
      .scale(99)
      .translate([ width / 2, height / 2 ]);

    tooltip = d3.select('app-bubble-map').append("div")
      .attr("class", "directive-tooltip")
      .style('display', 'none');

    this.data.length && ready(this.data);

    function ready(data) {

      // Create a color scale
      let allContinent = d3.map(data, d => {
        // @ts-ignore
        return d.name
      }).keys();
      let color = d3.scaleOrdinal()
        .domain(allContinent)
        .range(d3.schemePaired);

      // Add a scale for bubble size
      let valueExtent = d3.extent(data, d => {
        // @ts-ignore
        return +d.value;
      });
      let size = d3.scaleSqrt()
        .domain(valueExtent)
        .range([ 5, 10 ]);

      // Draw the map
      svg.append('g')
        .selectAll('path')
        .data(geoMap.features)
        .enter()
        .append('path')
        .attr('fill', '#b8b8b8')
        .attr('d', d3.geoPath().projection(projection))
        .style('stroke', '#707070')
        .style('opacity', .3);

      // Add circles:
      svg
        .selectAll('circles')
        .data(data.sort((a,b) => { return +b.value - +a.value }).filter((d,i) => { return i < 1000 }))
        .enter()
        .append('circle')
          .attr('cx', (d) => { return projection([+d.longitude, +d.latitude])[0] })
          .attr('cy', (d) => { return projection([+d.longitude, +d.latitude])[1] })
          .attr('r', (d) => { return size(+d.value) })
          .style('fill', (d) => { return color(d.name) })
          .attr('stroke', '#707070')
          .attr('stroke-width', 1)
          .attr('fill-opacity', .8)
          .on('mouseenter', function (d) {
            let event = d3.event;
            tooltip.style("left", event.offsetX + "px");
            tooltip.style("top", event.offsetY + "px");

            tooltip.style('display', 'inline-block');
            tooltip.html('<b>' + d.country + '</b><br/>' +
              'Karşılık Sayısı: ' +  d.value);
          });
    }
  }

}
