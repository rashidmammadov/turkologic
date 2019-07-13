import {Semantics} from "./semantics";
import {Etymon} from "./etymon";

export interface Lexeme {
  lexeme_id: number;
  lexeme: string;
  pronunciation: string;
  latin_text: string;
  alphabet: string;
  language_id: number;
  etymon: Etymon;
  semantics_list: Semantics[];
  fetched: boolean;
}
