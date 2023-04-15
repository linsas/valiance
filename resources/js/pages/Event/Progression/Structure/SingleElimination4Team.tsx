import React from 'react'
import { EventStructure } from '../EventStructure'
import { BracketSingleElimination4Team } from '../Components/BracketStage'

export default function SingleElimination4Team({ structure }: {
	structure: EventStructure
}) {
	const semifinals = structure.getRoundMatchupsOrDefault(1)
	const sf1 = semifinals[0] ?? null
	const sf2 = semifinals[1] ?? null

	const finals = structure.getRoundMatchupsOrDefault(2)
	const finalMatchup = finals[0] ?? null

	return <BracketSingleElimination4Team
		semifinals1={sf1}
		semifinals2={sf2}
		finale={finalMatchup}
	/>
}
