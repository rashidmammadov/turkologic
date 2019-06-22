import { Injectable } from '@angular/core';
import { MatSnackBar } from "@angular/material";

@Injectable({
  providedIn: 'root'
})
export class NotificationService {

  constructor(private snackBar: MatSnackBar) { }

  public show(message, action = null) {
    this.snackBar.open(message, action || 'tamam', {duration: 2000});
  }
}
