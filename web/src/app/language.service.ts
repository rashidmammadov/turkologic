import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { apiUrl } from "../main";

@Injectable({
  providedIn: 'root'
})
export class LanguageService {

  constructor(private httpClient: HttpClient) { }

  public getRegions() {
    return this.httpClient.get(apiUrl + 'data?regions=true');
  }
}
