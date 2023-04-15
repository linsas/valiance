import { IEventRound } from '../EventTypes';

export class EventStructure {
	rounds: IEventRound[];
	constructor(rounds: Array<IEventRound>) {
		this.rounds = rounds;
	}
	hasRound(number: number) {
		return this.rounds.length >= number;
	}
	getRoundMatchupsOrDefault(roundNumber: number) {
		if (this.hasRound(roundNumber))
			return this.rounds[roundNumber - 1].matchups;
		return [];
	}
}
