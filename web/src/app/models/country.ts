export interface Country {
    country_id?: number | string;
    country_name: string;
    flag: string;
    description?: string;
    capital: string;
    currency_unit?: string,
    population?: number | string,
    language: string;
    language_relation: string[];
}
