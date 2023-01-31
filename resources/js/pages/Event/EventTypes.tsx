export interface IEventBasic {
	id: number;
	name: string;
	format: number;
}

export type IEventPayload = Omit<IEventBasic, 'id'>

export interface IEvent extends IEventBasic {
	participants: Array<{
		name: string;
		team: {
			id: string;
			name: string;
		};
		players: Array<{
			id: string;
			alias: string;
		}>;
	}>;
}
