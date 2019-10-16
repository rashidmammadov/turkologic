import { Component, Input, OnInit } from '@angular/core';
import { StateService } from '@uirouter/angular';
import { ProgressService } from "../../services/progress.service";
import { RootService } from "../../services/root.service";
import { Semantics } from "../../models/semantics";
import { NotificationService } from "../../services/notification.service";
import { WordType, WordTypes } from "../../models/word-type";
import { ReportService } from "../../services/report.service";
import {Lexeme} from "../../models/lexeme";

@Component({
  selector: 'app-report',
  templateUrl: './report.component.html',
  styleUrls: ['./report.component.css']
})
export class ReportComponent implements OnInit {
  @Input() data;
  lexeme = <Lexeme>{};
  semantics = <Semantics>{};
  groupedConnects: any;
  wordTypes: WordType[] = WordTypes;
  correlationDistributionData: any;
  fakeEquivalentData: any;
  similarityRatioData: any;

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
    });
  }

  private fakeEquivalentReport() {
    let params = {
      report_type: 'fake_equivalent',
      lexeme: this.lexeme.lexeme
    };
    this.progress.circular = true;
    this.reportService.get(params).subscribe((res: any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.fakeEquivalentData = res.data;
      } else {
        this.notificationService.show(res.message);
      }
    }, () => {
      this.progress.circular = false;
    });
  }

  private similarityRatioReport() {
    let params = {
      report_type: 'similarity_ratio',
      semantic_id: this.semantics.semantic_id
    };
    this.progress.circular = true;
    this.reportService.get(params).subscribe((res: any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.similarityRatioData = res.data;
      } else {
        this.notificationService.show(res.message);
      }
    }, () => {
      this.progress.circular = false;
    });
  }

  private getSemantics() {
    this.data.subscribe((res: any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.lexeme = res.data;
        if (this.lexeme) {
          this.semantics = this.lexeme.semantics_list[0];
          this.groupConnectsByLanguage();
          this.correlationDistributionReport();
          this.fakeEquivalentReport();
          this.similarityRatioReport();
        }
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
        (Number(connect.language_id) === Number(language.language_id)) && (data.data.push(connect));
      });
      data.data.length && this.groupedConnects.push(data);
    });
  }

  ngOnInit() {
    this.getSemantics();
  }

}
