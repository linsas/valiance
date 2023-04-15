import React from 'react';
import { EventStructure } from '../EventStructure'
import { BracketSingleElimination8Team, BracketDoubleElimination4Team } from '../Components/BracketStage';
import { StageSeparator } from "../Components/StageSeparator";

export default function Minor16Team({ structure }: {
	structure: EventStructure
}) {
	const openingRound = structure.getRoundMatchupsOrDefault(1)
	const openingA1 = openingRound.filter(m => m.significance === 'ao')[0]
	const openingA2 = openingRound.filter(m => m.significance === 'ao')[1]
	const openingB1 = openingRound.filter(m => m.significance === 'bo')[0]
	const openingB2 = openingRound.filter(m => m.significance === 'bo')[1]
	const opneingC1 = openingRound.filter(m => m.significance === 'co')[0]
	const openingC2 = openingRound.filter(m => m.significance === 'co')[1]
	const openingD1 = openingRound.filter(m => m.significance === 'do')[0]
	const openingD2 = openingRound.filter(m => m.significance === 'do')[1]

	const secondRound = structure.getRoundMatchupsOrDefault(2)
	const winnersA = secondRound.find(m => m.significance === 'aw') ?? null
	const losersA = secondRound.find(m => m.significance === 'al') ?? null
	const winnersB = secondRound.find(m => m.significance === 'bw') ?? null
	const losersB = secondRound.find(m => m.significance === 'bl') ?? null
	const winnersC = secondRound.find(m => m.significance === 'cw') ?? null
	const losersC = secondRound.find(m => m.significance === 'cl') ?? null
	const winnersD = secondRound.find(m => m.significance === 'dw') ?? null
	const losersD = secondRound.find(m => m.significance === 'dl') ?? null

	const decidingRound = structure.getRoundMatchupsOrDefault(3)
	const decidingA = decidingRound.find(m => m.significance === 'ad') ?? null
	const decidingB = decidingRound.find(m => m.significance === 'bd') ?? null
	const decidingC = decidingRound.find(m => m.significance === 'cd') ?? null
	const decidingD = decidingRound.find(m => m.significance === 'dd') ?? null

	const quarterfinals = structure.getRoundMatchupsOrDefault(4)
	const qf1 = quarterfinals[0]
	const qf2 = quarterfinals[1]
	const qf3 = quarterfinals[2]
	const qf4 = quarterfinals[3]

	const semifinals = structure.getRoundMatchupsOrDefault(5)
	const sf1 = semifinals[0]
	const sf2 = semifinals[1]

	const finals = structure.getRoundMatchupsOrDefault(6)
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
		<StageSeparator title='Group C' />
		<BracketDoubleElimination4Team
			opening1={opneingC1}
			opening2={openingC2}
			upper={winnersC}
			lower={losersC}
			deciding={decidingC}
		/>
		<StageSeparator title='Group D' />
		<BracketDoubleElimination4Team
			opening1={openingD1}
			opening2={openingD2}
			upper={winnersD}
			lower={losersD}
			deciding={decidingD}
		/>
		<StageSeparator title='Playoffs' />
		<BracketSingleElimination8Team
			quarterfinals1={qf1}
			quarterfinals2={qf2}
			quarterfinals3={qf3}
			quarterfinals4={qf4}
			semifinals1={sf1}
			semifinals2={sf2}
			finale={finalMatchup}
		/>
	</>
}
