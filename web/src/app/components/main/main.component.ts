import { Component, OnInit } from '@angular/core';
import { Lexeme } from "../../models/lexeme";
import { StateService } from "@uirouter/angular";
import { Dialect } from "../../models/dialect";

@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.css']
})
export class MainComponent implements OnInit {
    lexeme: Lexeme;
    languages: Dialect;
    languageIdMap = [14, 21, 22, 24, 25, 26, 28, 32];
    selectedLanguageId: number;

    constructor(private state: StateService) {
        this.lexeme = <Lexeme>{};
        this.selectedLanguageId = this.languageIdMap[Math.floor(Math.random() * this.languageIdMap.length)];
    }

    searchedLexeme(data) {
        this.lexeme = data;
        this.state.go('search', {lexeme_id: this.lexeme.lexeme_id});
    }

    ngOnInit() {}

}
