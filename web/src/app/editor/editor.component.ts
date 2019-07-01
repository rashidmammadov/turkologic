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
    // @ts-ignore
    let finder = this.lexeme.semantics[semanticId].connects.find(c => {return c.language_id === languageId});
    if (!finder) {
      let newConnect = <Lexeme>{};
      newConnect.language_id = languageId;
      newConnect.semantics = [<Semantics>{}];
      // @ts-ignore
      this.lexeme.semantics[semanticId].connects.push(newConnect);
    } else {
      let newSemantics = <Semantics>{};
      // @ts-ignore
      finder.semantics.push(newSemantics);
    }
  }

  addMoreSource() {
    if (this.etymon.sources.length < 3) {
      this.etymon.sources.push({sample: '', reference: ''});
    }
  }

  getConnectionsByLanguageId(connects, languageId) {
    if (connects.length) {
      let filtered = connects.filter(c => c.language_id === languageId);
      if (filtered && filtered[0] && filtered[0].semantics) {
        return filtered[0].semantics;
      }
    }
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

  fetchFromTDK(word) {
    this.progress.circular = true;
    this.tdkService.getMeans(word).subscribe((res : any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.lexeme = res.data.lexeme;
        this.lexeme.semantics = res.data.semantics;
        this.notificationService.show(res.message);
      } else {
        this.notificationService.show(res.message);
      }
    });
  }

  removeConnect(semantic, languageId, semanticId) {
    semantic.forEach(s => {
        s.language_id === languageId && (s.semantics.splice(semanticId, 1));
    });
  }

  removeSource(id) {
    this.etymon.sources.splice(id, 1);
  }

  ngOnInit() {
    this.getLanguages();
    this.etymon.sources = [{sample: '', reference: ''}];
  }

}
