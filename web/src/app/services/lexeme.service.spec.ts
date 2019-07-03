import { TestBed } from '@angular/core/testing';

import { LexemeService } from './lexeme.service';

describe('LexemeService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: LexemeService = TestBed.get(LexemeService);
    expect(service).toBeTruthy();
  });
});
