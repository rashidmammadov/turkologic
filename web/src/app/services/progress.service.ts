import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ProgressService {
  public circular = false;
  public linear = false;

  constructor() { }
}
