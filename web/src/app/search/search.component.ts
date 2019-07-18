import { Component, NgModule, OnInit } from '@angular/core';
import { UIRouter, StateService } from "@uirouter/angular";
import { ProgressService } from "../services/progress.service";
import { NotificationService } from "../services/notification.service";
import { LexemeService } from "../services/lexeme.service";
import { RootService } from "../services/root.service";
import { Lexeme } from "../models/lexeme";
import { WordType, WordTypes } from "../models/word-type";

@NgModule({
  imports: [],
  exports: []
})
@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css']
})
export class SearchComponent implements OnInit {
  lexeme = <Lexeme>{};
  lexemeId: number;
  flag: string;
  wordTypes: WordType[] = WordTypes;

  constructor(public progress: ProgressService, private router: UIRouter, private lexemeService: LexemeService,
              private notificationService: NotificationService, private state: StateService, public root: RootService) {
    this.lexemeId = this.router.globals.params.lexeme_id;
  }

  searchedLexeme(data) {
    this.lexemeId = data.lexeme_id;
    this.state.go('search', {lexeme_id: this.lexemeId});
  }

  private getLexeme(lexemeId) {
    this.progress.circular = true;
    this.lexemeService.getById(lexemeId).subscribe((res: any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        let languages = [];
        this.root.getData('languages').subscribe((data: any) => { languages = data; });
        this.lexeme = res.data;
        this.flag = languages.find(language => { return language.language_id === this.lexeme.language_id; }).flag;
      } else {
        this.lexeme = <Lexeme>{};
        this.notificationService.show(res.message);
      }
    }, () => {
      this.progress.circular = false;
    });
  }

  ngOnInit() {
    this.getLexeme(this.lexemeId);
  }

}
