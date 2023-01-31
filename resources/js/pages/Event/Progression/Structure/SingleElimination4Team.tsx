import React from 'react'
import { IMatchup } from '../../../Matchup/MatchupTypes'
import { BracketSingleElimination4Team } from '../Components/BracketStage'

export default function SingleElimination4Team({ matchups }: {
	matchups: Array<IMatchup>
}) {
	const sf1 = matchups.find(m => m.key === 'sf1') ?? null
	const sf2 = matchups.find(m => m.key === 'sf2') ?? null
	const finals = matchups.find(m => m.key === 'f') ?? null

	return <BracketSingleElimination4Team
		semifinals1={sf1}
		semifinals2={sf2}
		finals={finals}
	/>
}
