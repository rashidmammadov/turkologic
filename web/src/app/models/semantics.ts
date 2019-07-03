import {Lexeme} from "./lexeme";

export interface Semantics {
  semantic_id: number;
  lexeme_id: number;
  type: number;
  meaning: string;
  sample: string;
  reference: string;
  belong_to: number;
  connects: Lexeme[];
}
