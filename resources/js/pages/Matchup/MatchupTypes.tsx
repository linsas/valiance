export interface IMatchupBasic {
	id: number;
	team1: string;
	team2: string;
	tournament: string;
	maps: Array<string>;
}

export interface IGameMap {
	id: number;
	name: string;
	color: string;
}

export interface IGame {
	number: number;
	map: IGameMap | null;
	score1: string | null;
	score2: string | null;
}

export interface IMatchup {
	id: number;
	significance: string;
	team1: {
		id: number;
		name: string;
	};
	team2: {
		id: number;
		name: string;
	};
	tournament: {
		id: number;
		name: string;
	};
	score1: number;
	score2: number;
	games: Array<IGame>;
}
