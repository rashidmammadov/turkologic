export interface Etymon {
  etymon_id: number,
  language_id: number;
  word: string;
  pronunciation: string;
  type: number;
  description: string;
  sources: [{
    sample: string,
    reference: string
  }];
}
