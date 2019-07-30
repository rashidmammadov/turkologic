import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class EditorService {

  constructor(private httpClient: HttpClient) { }

  public post(lexeme) {
    return this.httpClient.post(environment.apiUrl + 'editor', lexeme);
  }

  public put(lexeme) {
    return this.httpClient.put(environment.apiUrl + 'editor', lexeme);
  }

  public delete(params) {
    return this.httpClient.delete(environment.apiUrl + 'editor', params);
  }

}
