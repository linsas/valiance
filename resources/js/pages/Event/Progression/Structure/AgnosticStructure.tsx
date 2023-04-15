import React from 'react'
import { IEventRound } from '../../EventTypes'
import { CompactMatchupsList } from '../Components/CompactMatchupsList';

export default function AgnosticProgressionStructure({ rounds }: {
	rounds: Array<IEventRound>
}) {

	return <>
		{rounds.map(round =>
			<CompactMatchupsList
				matchups={round.matchups}
				title={'Round '+round.number}
			/>
		)}
	</>
}
