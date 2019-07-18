import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { UIRouterModule } from "@uirouter/angular";
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule } from '@angular/common/http';
import { FlexLayoutModule } from '@angular/flex-layout';
import { MatIconModule, MatProgressBarModule, MatProgressSpinnerModule, MatListModule, MatSnackBarModule, MatToolbarModule } from '@angular/material';
import { AppComponent } from './app.component';
import { EditorComponent } from './editor/editor.component';
import { AutocompleteComponent } from './autocomplete/autocomplete.component';
import { SearchComponent } from './search/search.component';
import { MainComponent } from './main/main.component';

/** States */
const states = [
  { name: '/', url: '/', component: MainComponent },
  { name: 'editor', url: 'editor', component: EditorComponent },
  { name: 'search', url: 'search/:lexeme_id', component: SearchComponent }
];

@NgModule({
  declarations: [AppComponent, EditorComponent, AutocompleteComponent, SearchComponent, MainComponent],
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
