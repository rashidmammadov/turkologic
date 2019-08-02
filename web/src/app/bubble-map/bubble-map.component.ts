import { Component, ElementRef, HostListener, Input, OnChanges, ViewChild } from '@angular/core';
import { geoMap } from "../../assets/data/geo-map";
import * as d3 from 'd3';

let svg, cities, color, height, projection, tooltip, width, margin = 8;
@Component({
  selector: 'app-bubble-map',
  templateUrl: './bubble-map.html',
  styleUrls: ['./bubble-map.component.css']
})
export class BubbleMapComponent implements OnChanges {
  // @ts-ignore
  @ViewChild('chart') chartContainer: ElementRef;
  @Input() data: any;

  constructor() { }

  private prepare() {
    d3.select('svg.bubble-map').remove();
    const element = this.chartContainer.nativeElement;

    width = element.clientWidth;
    height = width / 2.5;
    svg = d3.select(element).append('svg').classed('bubble-map', true)
      .attr('width', width - margin).attr('height', height - margin);

    projection = d3.geoMercator()
      .center([50, 50])
      .scale(160)
      .translate([ width / 2, height / 2 ]);

    tooltip = d3.select('app-bubble-map').append('div')
      .attr('class', 'directive-tooltip')
      .style('display', 'none');
  }

  private draw(data) {
    cities = d3.map(data, d => {
      // @ts-ignore
      return d.name
    }).keys();

    color = d3.scaleOrdinal()
      .domain(cities)
      .range(d3.schemePaired);

    let valueExtent = d3.extent(data, d => {
      // @ts-ignore
      return +d.value;
    });

    let size = d3.scaleSqrt()
      .domain(valueExtent)
      .range([ 5, 10 ]);

    svg.append('g')
      .selectAll('path')
      .data(geoMap.features)
      .enter()
        .append('path')
        .attr('fill', '#b8b8b8')
        .attr('d', d3.geoPath().projection(projection))
        .style('stroke', '#707070')
        .style('opacity', .3);

    svg.selectAll('circles')
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
        .on('mouseenter', this.showTooltip)
        .on('mousemove', this.showTooltip)
        .on('mouseleave', this.hideTooltip);
  }

  private showTooltip(d) {
    let event = d3.event;
    tooltip.style("left", event.offsetX + "px");
    tooltip.style("top", event.offsetY + "px");

    tooltip.style('display', 'inline-block');
    tooltip.html('<b>' + d.country + '</b><br/>' +
      'Karşılık Sayısı: ' +  d.value);
  }

  private hideTooltip() {
    tooltip.style('display', 'none');
  }

  @HostListener('window:resize', ['$event'])
  onResize() {
    this.prepare();
    this.draw(this.data);
  }

  ngOnChanges() {
    if (!this.data) { return; }
    this.prepare();
    this.draw(this.data);
  }

}
