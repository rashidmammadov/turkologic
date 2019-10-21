import {Component, ElementRef, HostListener, Input, OnChanges, OnInit, ViewChild} from '@angular/core';
import * as d3 from "d3";
import {geoMap} from "../../../assets/data/geo-map";

let svg, height, projection, tooltip, width, margin = 8;
@Component({
  selector: 'app-country-map',
  templateUrl: './country-map.component.html',
  styleUrls: ['./country-map.component.css']
})
export class CountryMapComponent implements OnInit, OnChanges {
    // @ts-ignore
    @ViewChild('chart') chartContainer: ElementRef;
    @Input() countryName: string;

    constructor() { }

    private prepare() {
        d3.select('svg.country-map').remove();
        const element = this.chartContainer.nativeElement;

        width = element.clientWidth;
        height = width / 2.5;
        svg = d3.select(element).append('svg').classed('country-map', true)
            .attr('width', width - margin).attr('height', height - margin);

        projection = d3.geoMercator()
            .center([40, 40])
            .scale(1500)
            .translate([ 0, height / 2 ]);

        tooltip = d3.select(element).append('div')
            .attr('class', 'directive-tooltip')
            .style('display', 'none');
    }

    private draw(countryName: string) {
        const data = geoMap.features.find((country) => {
            return country.properties.name === countryName;
        });

        svg.append("g")
            .selectAll("path")
            .data([data])
            .enter()
                .append("path")
                .attr("fill", "grey")
                .attr("d", d3.geoPath()
                  .projection(projection)
                )
                .style("stroke", "none")
    }

    @HostListener('window:resize', ['$event'])
    onResize() {
        this.prepare();
        this.countryName && this.draw(this.countryName);
    }

    ngOnInit() {
        this.prepare();
        this.countryName && this.draw(this.countryName);
    }

    ngOnChanges() {
        if (!this.countryName) { return; }
        this.prepare();
        this.countryName && this.draw(this.countryName);
    }

}
