import React from 'react'
import { EventStructure } from '../EventStructure'
import { BracketSingleElimination8Team } from '../Components/BracketStage'

export default function SingleElimination8Team({ structure }: {
	structure: EventStructure
}) {
	const quarterfinals = structure.getRoundMatchupsOrDefault(1)
	const qf1 = quarterfinals[0] ?? null
	const qf2 = quarterfinals[1] ?? null
	const qf3 = quarterfinals[2] ?? null
	const qf4 = quarterfinals[3] ?? null

	const semifinals = structure.getRoundMatchupsOrDefault(2)
	const sf1 = semifinals[0] ?? null
	const sf2 = semifinals[1] ?? null

	const finals = structure.getRoundMatchupsOrDefault(3)
	const finalMatchup = finals[0] ?? null

	return <BracketSingleElimination8Team
		quarterfinals1={qf1}
		quarterfinals2={qf2}
		quarterfinals3={qf3}
		quarterfinals4={qf4}
		semifinals1={sf1}
		semifinals2={sf2}
		finale={finalMatchup}
	/>
}
