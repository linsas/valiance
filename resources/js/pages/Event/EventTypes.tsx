export interface IEventBasic {
	id: number;
	name: string;
	format: number;
}

export type IEventPayload = Omit<IEventBasic, 'id'>

export type IParticipant = {
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

export type IParticipantPayload = {
	id: number;
	name: string;
}

export interface IEvent extends IEventBasic {
	participants: Array<IParticipant>;
	matchups: Array<{}>;
}
