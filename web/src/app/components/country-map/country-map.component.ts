import {Component, ElementRef, HostListener, Input, OnChanges, OnInit, ViewChild} from '@angular/core';
import * as d3 from "d3";
import {geoMap} from "../../../assets/data/geo-map";
import {max} from "rxjs/operators";

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
        height = width / 4 >= 80 ? width / 4 : 80;
        svg = d3.select(element).append('svg').classed('country-map', true)
            .attr('width', width - margin).attr('height', height - margin);
    }

    private draw(countryName: string) {
        const data = geoMap.features.find((country) => {
            return country.properties.name.toLowerCase() === countryName.toLowerCase();
        });

        if (data) {
            const points = this.getPoints(data.geometry.type, data.geometry.coordinates);

            projection = d3.geoMercator()
                .center([
                    (points.minLongitude + points.maxLongitude) / 2,
                    (points.minLatitude + points.maxLatitude) / 2
                ])
                .scale(this.getScale(points))
                .translate([width / 2, height / 2]);

            svg.append("g")
                .selectAll("path")
                .data([data])
                .enter()
                .append("path")
                .attr("fill", "#673ab7")
                .attr("d", d3.geoPath()
                  .projection(projection)
                )
                .style("stroke", "none");
        } else {
            svg.remove();
        }
    }

    private getPoints(type, coordinates) {
        let points = {
            minLatitude: 0,
            minLongitude: 0,
            maxLatitude: 0,
            maxLongitude: 0
        };
        let findMinMax = (coordinate) => {
            coordinate.forEach((mapPoints) => {
                mapPoints.forEach((point) => {
                    (points.minLongitude === 0 || points.minLongitude > point[0]) && (points.minLongitude = point[0]);
                    points.maxLongitude < point[0] && (points.maxLongitude = point[0]);
                    (points.minLatitude === 0 || points.minLatitude > point[1]) && (points.minLatitude = point[1]);
                    points.maxLatitude < point[1] && (points.maxLatitude = point[1]);
                });
            });
        };

        if (type === 'MultiPolygon') {
            coordinates.forEach(findMinMax);
        } else if (type === 'Polygon') {
            findMinMax(coordinates);
        }
        return points;
    }

    private getScale(points) {
        const diffLatitude = points.maxLatitude - points.minLatitude;
        const diffLongitude = points.maxLongitude - points.minLongitude;
        const result = d3.max([diffLatitude, diffLongitude]);
        return (1 / result) * 5000;
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
