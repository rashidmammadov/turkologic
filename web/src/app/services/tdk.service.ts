import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class TDKService {

  constructor(private httpClient: HttpClient) { }

  public getMeans(word) {
    return this.httpClient.get(environment.apiUrl + 'tdk', {
      params: {
        word: word
      }
    })
  }
}
