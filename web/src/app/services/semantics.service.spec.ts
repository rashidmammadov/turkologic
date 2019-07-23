import { TestBed } from '@angular/core/testing';

import { SemanticsService } from './semantics.service';

describe('SemanticsService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: SemanticsService = TestBed.get(SemanticsService);
    expect(service).toBeTruthy();
  });
});
