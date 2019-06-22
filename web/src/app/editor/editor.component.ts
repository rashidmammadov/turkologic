import { Component, NgModule, OnInit } from '@angular/core';
import { FormControl, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms';
import { MatAutocompleteModule, MatButtonModule, MatCardModule, MatFormFieldModule, MatInputModule, MatSelectModule,
  MatTooltipModule } from '@angular/material';
import { LanguageService } from "../services/language.service";
import { NotificationService } from "../services/notification.service";
import { ProgressService } from "../services/progress.service";
import { TDKService } from "../services/tdk.service";
import { Etymon } from "../models/etymon";
import { Lexeme } from "../models/lexeme";
import { WordType, WordTypes } from "../models/word-type";
import { InputErrorStateMatcher } from "../services/error.service";

@NgModule({
  imports: [FormsModule, MatAutocompleteModule, MatButtonModule, MatCardModule, MatFormFieldModule, MatInputModule,
    MatSelectModule, ReactiveFormsModule, MatTooltipModule],
  exports: [FormsModule, MatAutocompleteModule, MatButtonModule, MatCardModule, MatFormFieldModule, MatInputModule,
    MatSelectModule, ReactiveFormsModule, MatTooltipModule]
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
  wordTypes: WordType[] = WordTypes;

  constructor(private languageService: LanguageService, private progress: ProgressService, private tdkService: TDKService,
              private notificationService: NotificationService) {}

  matcher = new InputErrorStateMatcher();

  addMoreSource() {
    if (this.etymon.sources.length < 3) {
      this.etymon.sources.push({sample: '', reference: ''});
    }
  }

  getLanguages() {
    this.progress.circular = true;
    this.languageService.getLanguages().subscribe((res : any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.languages = res.data;
      }
    });
  }

  fetchFromTDK(word) {
    this.progress.circular = true;
    this.tdkService.getMeans(word).subscribe((res : any) => {
      this.progress.circular = false;
      if (!res.error && res.length) {
        this.lexeme.lexeme = res[0].madde;
      } else {
        this.lexeme = <Lexeme>{};
        notificationService.show(res.error);
      }
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
