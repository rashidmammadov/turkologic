import { Injectable } from '@angular/core';
import { ErrorStateMatcher } from "@angular/material";
import { FormControl, FormGroupDirective, NgForm } from "@angular/forms";

@Injectable({
  providedIn: 'root'
})
export class InputErrorStateMatcher implements ErrorStateMatcher {
  isErrorState(control: FormControl | null, form: FormGroupDirective | NgForm | null): boolean {
    const isSubmitted = form && form.submitted;
    return !!(control && control.invalid && (control.dirty || control.touched || isSubmitted));
  }
}

@Injectable({
  providedIn: 'root'
})
export class ErrorService {

  constructor() { }
}
