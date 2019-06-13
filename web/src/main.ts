import { enableProdMode } from '@angular/core';
import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';

import { AppModule } from './app/app.module';
import { environment } from './environments/environment';
import 'hammerjs';

if (environment.production) {
  enableProdMode();
}

export const apiUrl:string = environment.apiUrl;

platformBrowserDynamic().bootstrapModule(AppModule)
  .catch(err => console.error(err));
