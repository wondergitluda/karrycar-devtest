export interface Shipment {
    id: number;
    team: {
        name: string;
    };
    from: string;
    to: string;
    start_referents: any[];
    end_referents: any[];
}
