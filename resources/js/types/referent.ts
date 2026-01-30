export type ReferentScope = 'start' | 'end';

export interface ReferentPivot {
    id: number;
    scope: ReferentScope;
    created_at?: string;
    updated_at?: string;
}

export interface Referent {
    id: number;
    name: string;
    last_name: string;
    email: string | null;
    phone: string | null;
    team_id: number;
    team?: {
        id: number;
        name: string;
    };
    pivot?: ReferentPivot;
    created_at?: string;
    updated_at?: string;
}

export interface SearchReferent extends Referent {
    already_attached: boolean;
}
