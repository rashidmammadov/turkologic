import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormControl } from '@angular/forms';
import { LexemeService } from "../../services/lexeme.service";
import { SearchService } from "../../services/search.service";
import { Observable, of } from 'rxjs'
import { Semantics } from "../../models/semantics";
import { catchError, debounceTime, map, switchMap } from "rxjs/operators";
import { RootService } from "../../services/root.service";

@Component({
  selector: 'app-autocomplete',
  templateUrl: './autocomplete.component.html',
  styleUrls: ['./autocomplete.component.css']
})
export class AutocompleteComponent implements OnInit {
    @Input() label: string;
    @Input() params: Object;
    @Input() searchText: string;
    @Input() type: string;
    @Output() selectedResult = new EventEmitter();
    @Output() searchTextChange = new EventEmitter();

    results: Observable<any> = null;
    AutocompleteCtrl = new FormControl();
    progress = false;

    constructor(private lexemeService: LexemeService, private searchService: SearchService, private root: RootService) { }

    getLexemes(value: string): Observable<any> {
        let languages = [];
        this.root.getData('languages').subscribe((data: any) => { languages = data; });
        return this.searchService.get(value).pipe(
            map((res: any) => {
                this.progress = false;
                let list = [];
                if (res.status === 'success') {
                  list = res.data;
                  list.forEach(item => {item.flag = languages.find(language => {
                          return Number(language.language_id) === Number(item.language_id);
                      }).flag;
                  });
                }
                return list;
            }),
            catchError((_) => {
                this.progress = false;
                return of(null);
            })
        )
    }

    getLexemeSemanticsByLanguage(value: string): Observable<any> {
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
            catchError((_) => {
                this.progress = false;
                return of(null);
            })
        );
    }

    setSelectedOption(data) {
        this.selectedResult.emit(data);
    }

    setSelectedSubOption(data, index) {
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
                        return this.type === 'search' ? this.getLexemes(value) : this.getLexemeSemanticsByLanguage(value);
                    } else {
                        return [];
                    }
                })
            );
    }

}
