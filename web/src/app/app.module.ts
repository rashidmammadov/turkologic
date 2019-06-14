import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { UIRouterModule } from "@uirouter/angular";
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule } from '@angular/common/http';
import { FlexLayoutModule } from '@angular/flex-layout';
import { MatProgressSpinnerModule, MatToolbarModule } from '@angular/material';
import { AppComponent } from './app.component';
import { EditorComponent } from './editor/editor.component';

/** States */

let editorState = { name: 'editor', url: '/editor',  component: EditorComponent };

@NgModule({
  declarations: [AppComponent, EditorComponent],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    EditorComponent,
    FlexLayoutModule,
    HttpClientModule,
    MatProgressSpinnerModule,
    MatToolbarModule,
    UIRouterModule.forRoot({ states: [ editorState ], useHash: true})
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
