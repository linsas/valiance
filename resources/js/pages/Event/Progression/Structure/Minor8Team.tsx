import React from 'react'
import { EventStructure } from '../EventStructure'
import { BracketSingleElimination4Team, BracketDoubleElimination4Team } from '../Components/BracketStage'
import { StageSeparator } from "../Components/StageSeparator";

export default function Minor8Team({ structure }: {
	structure: EventStructure
}) {
	const openingRound = structure.getRoundMatchupsOrDefault(1)
	const openingA1 = openingRound.filter(m => m.significance === 'ao')[0]
	const openingA2 = openingRound.filter(m => m.significance === 'ao')[1]
	const openingB1 = openingRound.filter(m => m.significance === 'bo')[0]
	const openingB2 = openingRound.filter(m => m.significance === 'bo')[1]

	const secondRound = structure.getRoundMatchupsOrDefault(2)
	const winnersA = secondRound.find(m => m.significance === 'aw') ?? null
	const losersA = secondRound.find(m => m.significance === 'al') ?? null
	const winnersB = secondRound.find(m => m.significance === 'bw') ?? null
	const losersB = secondRound.find(m => m.significance === 'bl') ?? null

	const decidingRound = structure.getRoundMatchupsOrDefault(3)
	const decidingA = decidingRound.find(m => m.significance === 'ad') ?? null
	const decidingB = decidingRound.find(m => m.significance === 'bd') ?? null

	const semifinals = structure.getRoundMatchupsOrDefault(4)
	const sf1 = semifinals[0]
	const sf2 = semifinals[1]

	const finals = structure.getRoundMatchupsOrDefault(5)
	const finalMatchup = finals[0]

	return <>
		<StageSeparator title='Group A' />
		<BracketDoubleElimination4Team
			opening1={openingA1}
			opening2={openingA2}
			upper={winnersA}
			lower={losersA}
			deciding={decidingA}
		/>
		<StageSeparator title='Group B' />
		<BracketDoubleElimination4Team
			opening1={openingB1}
			opening2={openingB2}
			upper={winnersB}
			lower={losersB}
			deciding={decidingB}
		/>
		<StageSeparator title='Playoffs' />
		<BracketSingleElimination4Team
			semifinals1={sf1}
			semifinals2={sf2}
			finale={finalMatchup}
		/>
	</>
}
