import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class SemanticsService {

  constructor(private httpClient: HttpClient) { }

  public getById(semanticId) {
    return this.httpClient.get(environment.apiUrl + 'semantics', {
      params: {
        semantic_id: semanticId
      }
    });
  }
}
