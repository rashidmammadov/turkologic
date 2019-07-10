import { Component, NgModule, OnInit } from '@angular/core';
import { FormControl, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms';
import { MatAutocompleteModule, MatButtonModule, MatCardModule, MatDividerModule, MatExpansionModule, MatFormFieldModule,
  MatInputModule, MatSelectModule, MatTooltipModule } from '@angular/material';
import { LanguageService } from "../services/language.service";
import { NotificationService } from "../services/notification.service";
import { ProgressService } from "../services/progress.service";
import { TDKService } from "../services/tdk.service";
import { Dialect, Dialects } from "../models/dialect";
import { Etymon } from "../models/etymon";
import { Lexeme } from "../models/lexeme";
import { Semantics } from "../models/semantics";
import { WordType, WordTypes } from "../models/word-type";
import { InputErrorStateMatcher } from "../services/error.service";

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
  etymon = <Etymon>{};
  lexeme = <Lexeme>{};

  languages: Object;
  show = {etymology: true};
  tdkWord: string;
  wordForm = new FormControl('', [Validators.required]);
  dialects: Dialect[] = Dialects;
  wordTypes: WordType[] = WordTypes;

  constructor(private languageService: LanguageService, private progress: ProgressService, private tdkService: TDKService,
              private notificationService: NotificationService) {}

  matcher = new InputErrorStateMatcher();

  addConnections(semanticId, languageId) {
    let newConnect = <Lexeme>{};
    let newSemantics = <Semantics>{};
    newConnect.language_id = languageId;
    newConnect.semantics_list = [newSemantics];
    this.lexeme.semantics_list[semanticId].connects.push(newConnect);
  }

  addMoreSource() {
    if (this.etymon.sources.length < 3) {
      this.etymon.sources.push({sample: '', reference: ''});
    }
  }

  set(connect) {
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
    this.lexeme.semantics_list[data.params.semanticsId].connects[data.params.connectId] = data.data;
  }

  getLanguages() {
    this.progress.circular = true;
    this.languageService.getLanguages().subscribe((res : any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.languages = res.data;
      } else {
        this.notificationService.show(res.message);
      }
    });
  }

  getSemantics(connect) {
    return connect.semantics;
  }

  fetchFromTDK(word) {
    this.progress.circular = true;
    this.tdkService.getMeans(word).subscribe((res : any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.lexeme = res.data.lexeme;
        this.notificationService.show(res.message);
      } else {
        this.notificationService.show(res.message);
      }
    });
  }

  removeConnect(semantics, languageId, connectId) {
    let filtered = semantics.connects.filter(c => c.language_id === languageId);
    semantics.connects = semantics.connects.filter(c => c.language_id !== languageId);
    filtered.splice(connectId, 1);
    semantics.connects = semantics.connects.concat(filtered);
  }

  removeSource(id) {
    this.etymon.sources.splice(id, 1);
  }

  saveChanges() {
    this.lexeme;
  }

  ngOnInit() {
    this.getLanguages();
    this.etymon.sources = [{sample: '', reference: ''}];
  }

}
