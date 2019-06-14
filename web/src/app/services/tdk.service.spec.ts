import { TestBed } from '@angular/core/testing';

import { TDKService } from './tdk.service';

describe('TDKService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: TDKService = TestBed.get(TDKService);
    expect(service).toBeTruthy();
  });
});
