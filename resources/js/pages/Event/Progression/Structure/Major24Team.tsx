import React from 'react';
import { EventStructure } from '../EventStructure'
import { BracketSingleElimination8Team } from '../Components/BracketStage';
import { SwissStage } from '../Components/SwissStage';
import { StageSeparator } from "../Components/StageSeparator";

export default function Major24Team({ structure }: {
	structure: EventStructure
}) {
	const challengersOpeningRound = structure.getRoundMatchupsOrDefault(1)
	const c00 = challengersOpeningRound.filter(m => m.significance === 'c00')

	const challengersRound2 = structure.getRoundMatchupsOrDefault(2)
	const c01 = challengersRound2.filter(m => m.significance === 'c01')
	const c10 = challengersRound2.filter(m => m.significance === 'c10')

	const challengersRound3 = structure.getRoundMatchupsOrDefault(3)
	const c02 = challengersRound3.filter(m => m.significance === 'c02')
	const c11 = challengersRound3.filter(m => m.significance === 'c11')
	const c20 = challengersRound3.filter(m => m.significance === 'c20')

	const challengersRound4 = structure.getRoundMatchupsOrDefault(4)
	const c21 = challengersRound4.filter(m => m.significance === 'c21')
	const c12 = challengersRound4.filter(m => m.significance === 'c12')

	const challengersFinalRound = structure.getRoundMatchupsOrDefault(5)
	const c22 = challengersFinalRound.filter(m => m.significance === 'c22')


	const legendsOpeningRound = structure.getRoundMatchupsOrDefault(6)
	const l00 = legendsOpeningRound.filter(m => m.significance === 'l00')

	const legendsRound2 = structure.getRoundMatchupsOrDefault(7)
	const l01 = legendsRound2.filter(m => m.significance === 'l01')
	const l10 = legendsRound2.filter(m => m.significance === 'l10')

	const legendsRound3 = structure.getRoundMatchupsOrDefault(8)
	const l02 = legendsRound3.filter(m => m.significance === 'l02')
	const l11 = legendsRound3.filter(m => m.significance === 'l11')
	const l20 = legendsRound3.filter(m => m.significance === 'l20')

	const legendsRound4 = structure.getRoundMatchupsOrDefault(9)
	const l21 = legendsRound4.filter(m => m.significance === 'l21')
	const l12 = legendsRound4.filter(m => m.significance === 'l12')

	const legendsFinalRound = structure.getRoundMatchupsOrDefault(10)
	const l22 = legendsFinalRound.filter(m => m.significance === 'l22')


	const quarterfinals = structure.getRoundMatchupsOrDefault(11)
	const qf1 = quarterfinals[0]
	const qf2 = quarterfinals[1]
	const qf3 = quarterfinals[2]
	const qf4 = quarterfinals[3]

	const semifinals = structure.getRoundMatchupsOrDefault(12)
	const sf1 = semifinals[0]
	const sf2 = semifinals[1]

	const finals = structure.getRoundMatchupsOrDefault(13)
	const finalMatchup = finals[0]
	return <>
		<StageSeparator title="Challengers' Stage" />
		<SwissStage
			zerZer={c00}
			zerOne={c01} oneZer={c10}
			zerTwo={c02} oneOne={c11} twoZer={c20}
			oneTwo={c12} twoOne={c21}
			twoTwo={c22}
		/>
		<StageSeparator title="Legends' Stage" />
		<SwissStage
			zerZer={l00}
			zerOne={l01} oneZer={l10}
			zerTwo={l02} oneOne={l11} twoZer={l20}
			oneTwo={l12} twoOne={l21}
			twoTwo={l22}
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
