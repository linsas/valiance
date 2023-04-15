import React from 'react'
import { IEventMatchup } from '../../EventTypes'
import { BracketSingleElimination4Team } from '../Components/BracketStage'

export default function SingleElimination4Team({ matchups }: {
	matchups: Array<IEventMatchup>
}) {
	const sf1 = matchups.find(m => m.significance === 'sf1') ?? null
	const sf2 = matchups.find(m => m.significance === 'sf2') ?? null
	const finals = matchups.find(m => m.significance === 'f') ?? null

	return <BracketSingleElimination4Team
		semifinals1={sf1}
		semifinals2={sf2}
		finals={finals}
	/>
}
