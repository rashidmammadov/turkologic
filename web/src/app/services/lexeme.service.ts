import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class LexemeService {

  constructor(private httpClient: HttpClient) { }

  public getByLanguage(lexeme, languageId) {
    return this.httpClient.get(environment.apiUrl + 'lexeme', {
      params: {
        lexeme: lexeme,
        language_id: languageId
      }
    });
  }
}
