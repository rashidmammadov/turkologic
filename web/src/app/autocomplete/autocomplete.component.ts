import { Component, Input, NgModule, OnInit } from '@angular/core';
import { FormControl, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatAutocompleteModule, MatInputModule, MatProgressBarModule } from '@angular/material';
import { LexemeService } from "../services/lexeme.service";
import { Observable, of } from 'rxjs'
import { catchError, debounceTime, map, switchMap } from "rxjs/operators";

@NgModule({
  imports: [FormsModule, MatAutocompleteModule, MatInputModule, MatProgressBarModule, ReactiveFormsModule],
  exports: [FormsModule, MatAutocompleteModule, MatInputModule, MatProgressBarModule, ReactiveFormsModule]
})
@Component({
  selector: 'app-autocomplete',
  templateUrl: './autocomplete.component.html',
  styleUrls: ['./autocomplete.component.css']
})
export class AutocompleteComponent implements OnInit {
  vvv = '';
  @Input() label = 'Arama';
  @Input() selectedResult;
  results: Observable<any> = null;
  AutocompleteCtrl = new FormControl();
  progress = false;

  constructor(private lexemeService: LexemeService) { }

  lookup(value: string): Observable<any> {
    return this.lexemeService.getByLanguage(value, 22).pipe(
      map((res: any) => {
        this.progress = false;
        let list = [];
        if (res.status === 'success') {
          res.data.forEach((d) => {
            list = list.concat(d.semantics_list.map((semantics) => {
              semantics.lexeme = d.lexeme;
              return semantics;
            }));
          })
        }
        return list;
      }),
      catchError(_ => {
        this.progress = false;
        return of(null);
      })
    );
  }

  setSelectedOption(result) {
    this.selectedResult(result);
  }

  ngOnInit() {
    this.results = this.AutocompleteCtrl.valueChanges
      .pipe(
        debounceTime(300),
        switchMap((value) => {
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
