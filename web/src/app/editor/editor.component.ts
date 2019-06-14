import { Component, NgModule, OnInit } from '@angular/core';
import { FormControl, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms';
import { MatAutocompleteModule, MatButtonModule, MatCardModule, MatFormFieldModule, MatInputModule, MatSelectModule }
  from '@angular/material';
import { LanguageService } from "../services/language.service";
import { ProgressService } from "../services/progress.service";
import { TDKService } from "../services/tdk.service";
import { Etymon } from "../models/etymon";
import { WordType, WordTypes } from "../models/word-type";
import { InputErrorStateMatcher } from "../services/error.service";

@NgModule({
  imports: [FormsModule, MatAutocompleteModule, MatButtonModule, MatCardModule, MatFormFieldModule, MatInputModule,
    MatSelectModule, ReactiveFormsModule],
  exports: [FormsModule, MatAutocompleteModule, MatButtonModule, MatCardModule, MatFormFieldModule, MatInputModule,
    MatSelectModule, ReactiveFormsModule]
})
@Component({
  selector: 'app-editor',
  templateUrl: './editor.component.html',
  styleUrls: ['./editor.component.css']
})
export class EditorComponent implements OnInit {
  etymon = <Etymon>{};
  objectKeys = Object.keys;
  regions: Object;
  dialects: Object;
  tdkWord: string;
  wordForm = new FormControl('', [Validators.required]);
  wordTypes: WordType[] = WordTypes;

  constructor(private languageService: LanguageService, private progress: ProgressService, private tdkService: TDKService) {}

  matcher = new InputErrorStateMatcher();

  getCities() {
    this.progress.circular = true;
    this.languageService.getRegions().subscribe((res : any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.regions = res.data.regions;
      }
    });
  }

  fetchFromTDK(word) {
    this.progress.circular = true;
    this.tdkService.getDialects(word).subscribe((res : any) => {
      this.progress.circular = false;
      if (!res.error && res.length) {
        this.dialects = res[0];
      }
    });
  }

  save(etymon) {
    this.etymon;
  }

  ngOnInit() {
    this.getCities();
    this.etymon.sources = [{sample: '', reference: ''}];
  }

}
