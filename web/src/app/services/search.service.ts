import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class SearchService {

  constructor(private httpClient: HttpClient) { }

  public get(lexeme) {
    return this.httpClient.get(environment.apiUrl + 'search', {
      params: {
        lexeme: lexeme
      }
    })
  }
}
