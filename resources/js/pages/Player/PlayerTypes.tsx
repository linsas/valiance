export interface IPlayerBasic {
	id: number;
	alias: string;
	team: {
		id: number;
		name: string;
	} | null;
}

export type IPlayerPayload = Omit<IPlayerBasic, 'id'>

export interface IPlayerTransfer {
	date: string;
	team: {
		id: string;
		name: string;
	} | null;
}

export interface IPlayer extends IPlayerBasic {
	history: Array<IPlayerTransfer>;
	participations: Array<{
		tournament: {
			id: string;
			name: string;
		};
		team: {
			id: string;
			name: string;
		};
	}>;
}
