import { Component, NgModule, OnInit } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatAutocompleteModule, MatButtonModule, MatCardModule, MatDividerModule, MatExpansionModule, MatFormFieldModule,
  MatInputModule, MatSelectModule, MatTooltipModule } from '@angular/material';
import { EditorService } from "../services/editor.service";
import { LanguageService } from "../services/language.service";
import { NotificationService } from "../services/notification.service";
import { ProgressService } from "../services/progress.service";
import { TDKService } from "../services/tdk.service";
import { Dialect } from "../models/dialect";
import { Lexeme } from "../models/lexeme";
import { Semantics } from "../models/semantics";
import { WordType, WordTypes } from "../models/word-type";

@NgModule({
  imports: [FormsModule, MatAutocompleteModule, MatButtonModule, MatCardModule, MatDividerModule, MatExpansionModule,
    MatFormFieldModule, MatInputModule, MatSelectModule, ReactiveFormsModule, MatTooltipModule],
  exports: [FormsModule, MatAutocompleteModule, MatButtonModule, MatCardModule, MatDividerModule, MatExpansionModule,
    MatFormFieldModule, MatInputModule, MatSelectModule, ReactiveFormsModule, MatTooltipModule]
})
@Component({
  selector: 'app-editor',
  templateUrl: './editor.component.html',
  styleUrls: ['./editor.component.css']
})
export class EditorComponent implements OnInit {
  lexeme = <Lexeme>{};
  languages: Object;
  tdkWord: string;
  dialects: Dialect[];
  wordTypes: WordType[] = WordTypes;

  constructor(private editorService: EditorService, private languageService: LanguageService,
              public progress: ProgressService, private tdkService: TDKService,
              private notificationService: NotificationService) {}

  addConnections(semanticId, languageId) {
    let newConnect = <Lexeme>{};
    let newSemantics = <Semantics>{};
    newConnect.language_id = languageId;
    newConnect.semantics_list = [newSemantics];
    this.lexeme.semantics_list[semanticId].connects.push(newConnect);
  }

  addMoreSource() {
    if (this.lexeme.etymon.sources.length < 3) {
      this.lexeme.etymon.sources.push({sample: '', reference: ''});
    }
  }

  clearConnect(connect) {
    if (connect.fetched) {
      connect.lexeme_id = null;
      connect.pronunciation = '';
      connect.fetched = false;
      connect.semantics_list[0] = <Semantics>{};
    }
  }

  getConnectionsByLanguageId(connects, languageId) {
    if (connects.length) {
      return connects.filter(c => c.language_id === languageId);
    }
  }

  searchedLexeme(data) {
    let connects = this.lexeme.semantics_list[data.params.semanticsId].connects;
    let groupedConnects = this.getConnectionsByLanguageId(connects, data.params.languageId);
    let filtered = connects.filter(c => c.language_id !== data.params.languageId);
    groupedConnects[data.params.connectId] = data.data;
    this.lexeme.semantics_list[data.params.semanticsId].connects = filtered.concat(groupedConnects);
  }

  getLanguages() {
    this.progress.circular = true;
    this.languageService.getLanguages().subscribe((res : any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.languages = res.data;
        this.dialects = [];
        res.data.forEach((lang) => {lang.status && lang.flag && lang.language_id !== 21 && this.dialects.push(lang); });
      } else {
        this.notificationService.show(res.message);
      }
    }, () => {
      this.progress.circular = false;
      this.notificationService.show('Bir şeyler ters gitti.');
    });
  }

  fetchFromTDK(word) {
    this.progress.circular = true;
    this.tdkService.getMeans(word).subscribe((res : any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.lexeme = res.data;
        if (!this.lexeme.etymon.sources) {
          this.lexeme.etymon.sources = [{sample: '', reference: ''}];
        }
        this.notificationService.show(res.message);
      } else {
        this.notificationService.show(res.message);
      }
    }, () => {
      this.progress.circular = false;
      this.notificationService.show('Bir şeyler ters gitti.');
    });
  }

  removeConnect(semantics, languageId, connectId) {
    let filtered = semantics.connects.filter(c => c.language_id === languageId);
    semantics.connects = semantics.connects.filter(c => c.language_id !== languageId);
    filtered.splice(connectId, 1);
    semantics.connects = semantics.connects.concat(filtered);
  }

  removeSource(id) {
    this.lexeme.etymon.sources.splice(id, 1);
  }

  saveChanges() {
    this.progress.circular = true;
    this.editorService.post(this.lexeme).subscribe((res: any) => {
      this.progress.circular = false;
      this.notificationService.show(res.message);
    }, () => {
      this.progress.circular = false;
      this.notificationService.show('Bir şeyler ters gitti.');
    });
  }

  ngOnInit() {
    this.getLanguages();
  }

}
