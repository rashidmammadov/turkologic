import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { UIRouterModule, Transition } from "@uirouter/angular";
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule } from '@angular/common/http';
import { FlexLayoutModule } from '@angular/flex-layout';
import { MatIconModule, MatProgressBarModule, MatProgressSpinnerModule, MatListModule, MatSnackBarModule, MatToolbarModule } from '@angular/material';
import { AppComponent } from './app.component';
import { EditorComponent } from './editor/editor.component';
import { AutocompleteComponent } from './autocomplete/autocomplete.component';
import { BubbleMapComponent } from './bubble-map/bubble-map.component';
import { SearchComponent } from './search/search.component';
import { MainComponent } from './main/main.component';
import { ReportComponent } from './report/report.component';
import { LexemeService } from "./services/lexeme.service";
import { SemanticsService } from "./services/semantics.service";
import { HeatMapComponent } from './heat-map/heat-map.component';

/** States */
export function getSemantics(trans, semanticsService) {
  return semanticsService.getById(trans.params().semantic_id);
}
export function getLexeme(trans, lexemeService) {
  return lexemeService.getById(trans.params().lexeme_id);
}
const states = [
  { name: '/', url: '/', component: MainComponent },
  { name: 'editor', url: 'editor', component: EditorComponent },
  { name: 'report', url: 'report/:semantic_id', component: ReportComponent,
    resolve: [{
      token: 'data',
      deps: [Transition, SemanticsService],
      resolveFn: getSemantics
    }]
  },
  { name: 'search', url: 'search/:lexeme_id', component: SearchComponent,
    resolve: [{
      token: 'data',
      deps: [Transition, LexemeService],
      resolveFn: getLexeme
    }]
  }
];

@NgModule({
  declarations: [AppComponent, EditorComponent, AutocompleteComponent, BubbleMapComponent, SearchComponent, MainComponent,
    ReportComponent,
    HeatMapComponent],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    EditorComponent,
    FlexLayoutModule,
    HttpClientModule,
    MatIconModule,
    MatProgressBarModule,
    MatProgressSpinnerModule,
    MatListModule,
    MatSnackBarModule,
    MatToolbarModule,
    UIRouterModule.forRoot({ states: states, useHash: true, otherwise: '/' }),
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {}
