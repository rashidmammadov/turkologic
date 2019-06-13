import { Component, NgModule, OnInit } from '@angular/core';
import { FormControl, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms';
import { MatAutocompleteModule, MatCardModule, MatFormFieldModule, MatInputModule, MatSelectModule } from '@angular/material';
import { LanguageService } from "../language.service";
import { Etymon } from "../models/etymon";
import { WordType, WordTypes } from "../models/word-type";
import { InputErrorStateMatcher } from "../error.service";

@NgModule({
  imports: [FormsModule, MatAutocompleteModule, MatCardModule, MatFormFieldModule, MatInputModule, MatSelectModule, ReactiveFormsModule],
  exports: [FormsModule, MatAutocompleteModule, MatCardModule, MatFormFieldModule, MatInputModule, MatSelectModule, ReactiveFormsModule]
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
  wordForm = new FormControl('', [Validators.required]);
  wordTypes: WordType[] = WordTypes;

  constructor(private languageService: LanguageService) {}

  matcher = new InputErrorStateMatcher();

  getCities() {
    this.languageService.getRegions().subscribe((res : any) => {
      if (res.status === 'success') {
        this.regions = res.data.regions;
      }
    });
  }

  save(etymon) {
    this.etymon;
  }

  ngOnInit() {
    this.getCities();
  }

}
