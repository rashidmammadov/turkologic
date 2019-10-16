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
  lexeme = <Lexeme>{};
  languages: Dialect;

  constructor(private state: StateService) { }

  searchedLexeme(data) {
    this.lexeme = data;
    this.state.go('search', {lexeme_id: this.lexeme.lexeme_id});
  }

  ngOnInit() {}

}
