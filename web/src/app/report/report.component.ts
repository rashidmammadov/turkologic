import { Component, Input, OnInit } from '@angular/core';
import { StateService } from '@uirouter/angular';
import { ProgressService } from "../services/progress.service";
import { RootService } from "../services/root.service";
import { Semantics } from "../models/semantics";
import { NotificationService } from "../services/notification.service";
import { WordType, WordTypes } from "../models/word-type";

@Component({
  selector: 'app-report',
  templateUrl: './report.component.html',
  styleUrls: ['./report.component.css']
})
export class ReportComponent implements OnInit {
  @Input() data;
  semantics = <Semantics>{};
  wordTypes: WordType[] = WordTypes;

  constructor(public progress: ProgressService, public root: RootService, private notificationService: NotificationService,
              private state: StateService) {
    this.progress.circular = true;
  }

  redirectToSearch(lexemeId) {
    this.state.go('search', {lexeme_id: lexemeId});
  }

  private getSemantics() {
    this.data.subscribe((res: any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        let languages = [];
        this.root.getData('languages').subscribe((data: any) => { languages = data; });
        this.semantics = res.data;
        this.semantics.connects.forEach(connect => {
          // @ts-ignore
          connect.flag = languages.find(language => { return language.language_id === connect.language_id; }).flag;
        });
      } else {
        this.semantics = <Semantics>{};
        this.notificationService.show(res.message);
      }
    }, () => {
      this.progress.circular = false;
    });
  }

  ngOnInit() {
    this.getSemantics();
  }

}
