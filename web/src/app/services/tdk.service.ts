import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class TDKService {

  constructor(private httpClient: HttpClient) { }

  public getMeans(word) {
    return this.httpClient.get(environment.tdkUrl.general, {
      params: {
        ara: word
      }
    })
  }

  public getDialects(word, dialectId = null) {
    !dialectId && (dialectId = 1);
    return this.httpClient.get(environment.tdkUrl.dialect, {
      params: {
        ara: word,
        lehce: dialectId
      }
    });
  }
}
