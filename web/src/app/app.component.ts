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

    public constructor(public progress: ProgressService, private domSanitizer: DomSanitizer, public matIconRegistry: MatIconRegistry,
                       public root: RootService, private languageService: LanguageService) {
        /** icons */
        const iconArray = ['add', 'expand-less', 'expand-more', 'remove', 'save-changes', 'search'];
        /** flags */
        const flagArray = ['azerbaijan', 'kazakhstan', 'kyrgyzstan', 'tatarstan', 'turkey', 'turkmenistan', 'uyghur', 'uzbekistan'];

        iconArray.forEach(icon => this.matIconRegistry.addSvgIcon(icon,
            this.domSanitizer.bypassSecurityTrustResourceUrl(`assets/icons/${icon}.svg`)));
        flagArray.forEach(flag => this.matIconRegistry.addSvgIcon(flag,
            this.domSanitizer.bypassSecurityTrustResourceUrl(`assets/icons/flags/${flag}.svg`)));
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
