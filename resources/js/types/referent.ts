export interface Referent {
    [x: string]: any;
    id: number;
    team: {
        name: string;
    };
    name: string;
    last_name: string;
    email: any[];
    phone: any[];
}
