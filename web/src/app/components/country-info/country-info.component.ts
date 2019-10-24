import {Component, Input, OnChanges, OnInit, SimpleChanges} from '@angular/core';
import {ReportService} from "../../services/report.service";
import {ProgressService} from "../../services/progress.service";
import {Country} from "../../models/country";
import {NotificationService} from "../../services/notification.service";

@Component({
  selector: 'app-country-info',
  templateUrl: './country-info.component.html',
  styleUrls: ['./country-info.component.css']
})
export class CountryInfoComponent implements OnChanges {
    @Input() languageId: number;
    private _country: Country;

    constructor(public progress: ProgressService, private reportService: ReportService, private notificationService: NotificationService) {
        this.progress.circular = true;
    }

    private fetchCountryInfo(languageId: number) {
        let params = {
            report_type: 'country_info',
            language_id: this.languageId
        };
        this.progress.circular = true;
        this.reportService.get(params).subscribe((res: any) => {
            this.progress.circular = false;
            if (res.status === 'success') {
              this.country = res.data;
            } else {
              this.notificationService.show(res.message);
            }
        }, () => {
            this.progress.circular = false;
        });
    }

    ngOnChanges(changes: SimpleChanges): void {
        changes.languageId && changes.languageId.currentValue &&
            this.fetchCountryInfo(Number(changes.languageId.currentValue));
    }

    get country(): Country {
        return this._country;
    }

    set country(value: Country) {
        this._country = value;
    }

}
