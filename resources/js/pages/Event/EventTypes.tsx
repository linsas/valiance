export interface IEventBasic {
	id: number;
	name: string;
	format: number;
}

export type IEventPayload = Omit<IEventBasic, 'id'>

export interface IParticipant {
	name: string;
	team: {
		id: number;
		name: string;
	};
	players: Array<{
		id: number;
		alias: string;
	}>;
}

export interface IFormParticipant {
	name: string;
	team: {
		id: number;
		name: string;
	};
}

export interface IEventMatchup {
	id: number;
	significanceKey: string;
	team1: string;
	team2: string;
	score1: number;
	score2: number;
}

export interface IEventRound {
	number: number;
	matchups: Array<IEventMatchup>;
}

export interface IEvent extends IEventBasic {
	participants: Array<IParticipant>;
	rounds: Array<IEventRound>;
}
