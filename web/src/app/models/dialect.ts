export interface Dialect {
  language_id: number;
  name: string;
  flag: string;
}

export const Dialects:Dialect[] = [
  {
    language_id: 22,
    name: 'Azerice',
    flag: 'azerbaijan'
  }, {
    language_id: 28,
    name: 'Kazakça',
    flag: 'kazakhstan'
  }, {
    language_id: 24,
    name: 'Özbekçe',
    flag: 'uzbekistan'
  }
];


