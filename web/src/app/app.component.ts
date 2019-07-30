import { Component } from '@angular/core';
import { ProgressService } from 'src/app/services/progress.service';
import { DomSanitizer } from "@angular/platform-browser";
import { MatIconRegistry } from "@angular/material";
import { LanguageService } from "./services/language.service";
import { RootService } from "./services/root.service";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'etymology';

  public constructor(public progress: ProgressService, private domSanitizer: DomSanitizer, public matIconRegistry: MatIconRegistry,
                     public root: RootService, private languageService: LanguageService) {
    matIconRegistry.addSvgIcon('add', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/add.svg'));
    matIconRegistry.addSvgIcon('expand-less', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/expand-less.svg'));
    matIconRegistry.addSvgIcon('expand-more', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/expand-more.svg'));
    matIconRegistry.addSvgIcon('remove', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/remove.svg'));
    matIconRegistry.addSvgIcon('save-changes', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/save-changes.svg'));
    matIconRegistry.addSvgIcon('search', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/search.svg'));
    /** flags */
    matIconRegistry.addSvgIcon('azerbaijan', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/azerbaijan.svg'));
    matIconRegistry.addSvgIcon('kazakhstan', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/kazakhstan.svg'));
    matIconRegistry.addSvgIcon('kyrgyzstan', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/kyrgyzstan.svg'));
    matIconRegistry.addSvgIcon('tatarstan', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/tatarstan.svg'));
    matIconRegistry.addSvgIcon('turkey', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/turkey.svg'));
    matIconRegistry.addSvgIcon('turkmenistan', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/turkmenistan.svg'));
    matIconRegistry.addSvgIcon('uyghur', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/uyghur.svg'));
    matIconRegistry.addSvgIcon('uzbekistan', domSanitizer.bypassSecurityTrustResourceUrl('assets/icons/flags/uzbekistan.svg'));

    this.getLanguages();
  }

  private getLanguages() {
    this.languageService.getLanguages().subscribe((res: any) => {
      this.progress.circular = false;
      if (res.status === 'success') {
        this.root.setData('languages', res.data);
      }
    }, () => {
      this.progress.circular = false;
    });
  }

}
