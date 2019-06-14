export interface Etymon {
  description: string;
  origin: number;
  pronunciation: string;
  reference: string;
  sources: [{
    sample: string,
    reference: string
  }];
  spelling: string;
  type: number;
  word: string;
}
