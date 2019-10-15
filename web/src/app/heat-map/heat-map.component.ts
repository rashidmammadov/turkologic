import {Component, ElementRef, HostListener, Input, OnChanges, ViewChild} from '@angular/core';
import * as d3 from 'd3';

let svg, legend, width, height, tooltip, color, margin = 8,
  legendData = [0.9, 0.8, 0.6, 0.4, 0.2, 0.1];
@Component({
  selector: 'app-heat-map',
  templateUrl: './heat-map.component.html',
  styleUrls: ['./heat-map.component.css']
})
export class HeatMapComponent implements OnChanges {
    // @ts-ignore
    @ViewChild('chart') chartContainer: ElementRef;
    @Input() data: any;

    constructor() { }

    private prepare() {
        d3.select('svg.heat-map').remove();
        const element = this.chartContainer.nativeElement;

        width = element.clientWidth;
        height = width / 2.5;
        svg = d3.select(element).append('svg').classed('heat-map', true)
            .attr('width', width).attr('height', height)
            .append('g').attr('transform', 'translate(' + margin * 2 + ',' + margin * 2 + ')');

        legend = svg.append('g').attr('transform', 'translate(' + (width - margin * 7) + ',' + margin * 2 + ')')
            .selectAll('.legend').data(legendData);

        // @ts-ignore
        color = d3.scaleLinear().domain([0, 1]).range(['#ffd300', '#586949']);

        tooltip = d3.select(element).append('div')
            .attr('class', 'directive-tooltip')
            .style('display', 'none');
    }

    private draw(data) {
        let keys = d3.map(data,(d: any) => { return d.fromLexeme + ':' + d.fromLanguage; }).keys();

        const x = d3.scaleBand().range([ 0, width - margin * 6 ]).domain(keys).padding(0.05);

        svg.append('g').classed('axisX', true)
            .call(d3.axisBottom(x).tickSize(0))
            .selectAll('text').text(d => d.split(':')[0]);

        const y = d3.scaleBand().range([ 0, height ]).domain(keys).padding(0.05);

        svg.append('g').classed('axisY', true)
            .call(d3.axisLeft(y).tickSize(0))
            .selectAll('text').text(d => d.split(':')[0]);

        legend.enter().append('rect').classed('legend', true)
            .attr('y', (d, i) => {return (height / (legendData.length * 2)) * i})
            .attr('width', 16)
            .attr('height', height / (legendData.length * 2))
            .attr('fill', (d) => color(d));

        legend.enter().append('text')
            .style('font-size', '10px')
            .attr('x', margin * 2 + 4)
            .attr('y', (d, i) => {return (height / (legendData.length * 2)) * i + height / (legendData.length * 4)})
            .text((d) => { return d * 100 + '%'; });

        const rectX = d3.scaleBand().range([ 0, width - margin * 10 ]).domain(keys).padding(0.05);
        const rectY = d3.scaleBand().range([ 0, height - margin * 4 ]).domain(keys).padding(0.05);
        const rects = svg.append('g').attr('transform', 'translate(' + margin * 2 + ',' + margin * 2 + ')')
            .classed('rects', true).selectAll().data(data);

        rects.exit().remove();
        rects.enter().append('rect')
            .attr('x', (d) => { return rectX(d.fromLexeme + ':' + d.fromLanguage) })
            .attr('y', (d) => { return rectY(d.toLexeme + ':' + d.toLanguage) })
            .attr('rx', 4)
            .attr('ry', 4)
            .attr('width', rectX.bandwidth())
            .attr('height', rectY.bandwidth())
            .style('fill', (d) => { return color(d.ratio); })
            .on('mouseenter', this.showTooltip)
            .on('mousemove', this.showTooltip)
            .on('mouseleave', this.hideTooltip);
    }

    private showTooltip(d) {
        let event = d3.event;
        tooltip.style('left', event.offsetX + 'px');
        tooltip.style('top', event.offsetY - margin + 'px');

        tooltip.style('display', 'inline-block');
        tooltip.html('<b>' + d.fromLexeme + '</b> (' + d.fromPronunciation + ') ' + d.fromLanguage + '<br/>' +
            '<b>' + d.toLexeme + '</b> (' + d.toPronunciation + ') ' + d.toLanguage + '<br/>' +
            'İlişki Yüzdesi: ' +  d.ratio * 100 + '%');
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
