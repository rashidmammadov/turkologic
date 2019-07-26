import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {environment} from "../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class ReportService {

  constructor(private httpClient: HttpClient) { }

  public get(params) {
    return this.httpClient.get(environment.apiUrl + 'report', {
      params: params
    });
  }

}
