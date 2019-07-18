import { Injectable } from '@angular/core';
import { Observable } from "rxjs";

@Injectable({
  providedIn: 'root'
})
export class RootService {
  data: Observable<any>;

  constructor() {}

  setData(key: string, data:any) {
    if (!this.data) {
      this.data = Object();
    }
    this.data[key] = data;
  }

  getData(key: string):any {
    return new Observable(observer => {
      observer.next(this.data ? this.data[key] : null);
    });
  }
}
