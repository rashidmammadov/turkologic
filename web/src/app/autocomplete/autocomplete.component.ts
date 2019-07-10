import { Component, EventEmitter, Input, NgModule, OnInit, Output } from '@angular/core';
import { FormControl, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatAutocompleteModule, MatInputModule } from '@angular/material';
import { LexemeService } from "../services/lexeme.service";
import { Observable, of } from 'rxjs'
import { Semantics } from "../models/semantics";
import { catchError, debounceTime, map, switchMap } from "rxjs/operators";

@NgModule({
  imports: [FormsModule, MatAutocompleteModule, MatInputModule, ReactiveFormsModule],
  exports: [FormsModule, MatAutocompleteModule, MatInputModule, ReactiveFormsModule]
})
@Component({
  selector: 'app-autocomplete',
  templateUrl: './autocomplete.component.html',
  styleUrls: ['./autocomplete.component.css']
})
export class AutocompleteComponent implements OnInit {
  @Input() label: string;
  @Input() params: Object;
  @Input() searchText: string;
  @Output() selectedResult = new EventEmitter();
  @Output() searchTextChange = new EventEmitter();

  results: Observable<any> = null;
  AutocompleteCtrl = new FormControl();
  progress = false;
  defaultSemantics: Semantics;

  constructor(private lexemeService: LexemeService) { }

  lookup(value: string): Observable<any> {
    // @ts-ignore
    return this.lexemeService.getByLanguage(value, this.params.languageId).pipe(
      map((res: any) => {
        this.progress = false;
        let list = [];
        if (res.status === 'success') {
          list = res.data;
        }
        return list;
      }),
      catchError(_ => {
        this.progress = false;
        return of(null);
      })
    );
  }

  setSelectedOption(data, index) {
    let dataCopy = {...data};
    dataCopy.fetched = true;
    dataCopy.semantics_list = [];
    if (index >= 0) {
      dataCopy.semantics_list[0] = {...data.semantics_list[index]};
      dataCopy.semantics_list[0].fetched = true;
    } else {
      dataCopy.semantics_list[0] = <Semantics>{};
    }
    let passedData = {params: this.params, data: dataCopy};
    this.selectedResult.emit(passedData);
  }

  ngOnInit() {
    this.results = this.AutocompleteCtrl.valueChanges
      .pipe(
        debounceTime(300),
        switchMap((value) => {
          this.searchTextChange.emit(this.searchText);
          if (!!value) {
            this.progress = true;
            return this.lookup(value);
          } else {
            return [];
          }
        })
      );
  }

}
