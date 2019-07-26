import {Component, Input, NgModule, OnInit} from '@angular/core';
import { StateService } from '@uirouter/angular';
import { MatExpansionModule } from '@angular/material';
import { ProgressService } from "../services/progress.service";
import { RootService } from "../services/root.service";
import { Semantics } from "../models/semantics";
import { NotificationService } from "../services/notification.service";
import { WordType, WordTypes } from "../models/word-type";
import {ReportService} from "../services/report.service";
import {Observable} from "rxjs";

@NgModule({
  imports: [MatExpansionModule],
  exports: [MatExpansionModule]
})
@Component({
  selector: 'app-report',
  templateUrl: './report.component.html',
  styleUrls: ['./report.component.css']
})
export class ReportComponent implements OnInit {
  @Input() data;
  semantics = <Semantics>{};
  groupedConnects: any;
  wordTypes: WordType[] = WordTypes;
  correlationDistributionData: any;

  constructor(public progress: ProgressService, public root: RootService, private notificationService: NotificationService,
              private state: StateService, private reportService: ReportService) {
    this.progress.circular = true;
  }

  redirectToSearch(lexemeId) {
    this.state.go('search', {lexeme_id: lexemeId});
  }

  private correlationDistributionReport() {
    let params = {
      report_type: 'correlation_distribution',
      semantic_id: this.semantics.semantic_id
    };
    this.progress.circular = true;
    this.reportService.get(params).subscribe((res: any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.correlationDistributionData = res.data;
      } else {
        this.notificationService.show(res.message);
      }
    }, () => {
      this.progress.circular = false;
    })
  }

  private getSemantics() {
    this.data.subscribe((res: any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.semantics = res.data;
        this.groupConnectsByLanguage();
        this.correlationDistributionReport();
      } else {
        this.semantics = <Semantics>{};
        this.notificationService.show(res.message);
      }
    }, () => {
      this.progress.circular = false;
    });
  }

  private groupConnectsByLanguage() {
    let languages = [];
    this.groupedConnects = [];
    this.root.getData('languages').subscribe((data: any) => { languages = data; });
    languages.forEach(language => {
      let data = {
        languageId: language.language_id,
        flag: language.flag,
        name: language.name,
        data: []
      };
      this.semantics.connects.forEach(connect => {
        (connect.language_id === language.language_id) && (data.data.push(connect));
      });
      data.data.length && this.groupedConnects.push(data);
    });
  }

  ngOnInit() {
    this.getSemantics();
  }

}
