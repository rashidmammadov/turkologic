import { Component } from '@angular/core';
import { ProgressService } from 'src/app/services/progress.service';
import { DomSanitizer } from "@angular/platform-browser";
import { MatIconRegistry } from "@angular/material";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'etymology';

  public constructor(private progress: ProgressService, private domSanitizer: DomSanitizer, public matIconRegistry: MatIconRegistry) {
    matIconRegistry.addSvgIcon('add', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/add.svg'));
    matIconRegistry.addSvgIcon('expand-less', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/expand-less.svg'));
    matIconRegistry.addSvgIcon('expand-more', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/expand-more.svg'));
    matIconRegistry.addSvgIcon('remove', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/remove.svg'));
    matIconRegistry.addSvgIcon('save-changes', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/save-changes.svg'));
    /** flags */
    matIconRegistry.addSvgIcon('azerbaijan', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/azerbaijan.svg'));
    matIconRegistry.addSvgIcon('kazakhstan', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/kazakhstan.svg'));
    matIconRegistry.addSvgIcon('kyrgyzstan', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/kyrgyzstan.svg'));
    matIconRegistry.addSvgIcon('turkey', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/turkey.svg'));
    matIconRegistry.addSvgIcon('turkmenistan', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/turkmenistan.svg'));
    matIconRegistry.addSvgIcon('uyghur', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/uyghur.svg'));
    matIconRegistry.addSvgIcon('uzbekistan', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/uzbekistan.svg'));
  }

}
