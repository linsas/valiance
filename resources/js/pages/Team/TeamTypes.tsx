export interface ITeamBasic {
	id: number;
	name: string;
}

export type ITeamPayload = Omit<ITeamBasic, 'id'>

export interface ITeamTransfer {
	player: {
		id: string;
		alias: string;
	};
	otherTeam: {
		id: string;
		name: string;
	};
	date: string;
	isLeaving: boolean;
}

export interface ITeamPlayer {
	id: number;
	alias: string;
}

export interface ITeam extends ITeamBasic {
	players: Array<ITeamPlayer>;
	transfers: Array<ITeamTransfer>;
	participations: Array<{
		name: string;
		tournament: {
			id: string;
			name: string;
		};
	}>;
}
