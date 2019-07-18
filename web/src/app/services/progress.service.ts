import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ProgressService {
  public circular = true;
  public linear = false;

  constructor() { }
}
