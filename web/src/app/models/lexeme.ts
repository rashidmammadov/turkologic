import {Semantics} from "./semantics";

export interface Lexeme {
  lexeme_id: number;
  lexeme: string;
  pronunciation: string;
  latin_text: string;
  alphabet: string;
  language_id: number;
  semantics: Semantics[];
}
