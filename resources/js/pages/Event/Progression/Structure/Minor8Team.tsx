import React from 'react'
import { IMatchup } from '../../../Matchup/MatchupTypes';
import { BracketSingleElimination4Team, BracketDoubleElimination4Team } from '../Components/BracketStage'
import { StageSeparator } from "../Components/StageSeparator";

export default function Minor8Team({ matchups }: {
	matchups: Array<IMatchup>
}) {
	const a1 = matchups.filter(m => m.key === 'ao')[0]
	const a2 = matchups.filter(m => m.key === 'ao')[1]
	const aw = matchups.find(m => m.key === 'aw') ?? null
	const al = matchups.find(m => m.key === 'al') ?? null

	const b1 = matchups.filter(m => m.key === 'bo')[0]
	const b2 = matchups.filter(m => m.key === 'bo')[1]
	const bw = matchups.find(m => m.key === 'bw') ?? null
	const bl = matchups.find(m => m.key === 'bl') ?? null

	const ad = matchups.find(m => m.key === 'ad') ?? null
	const bd = matchups.find(m => m.key === 'bd') ?? null

	const sf1 = matchups.find(m => m.key === 'sf1') ?? null
	const sf2 = matchups.find(m => m.key === 'sf2') ?? null
	const finals = matchups.find(m => m.key === 'f') ?? null
	return <>
		<StageSeparator title='Group A' />
		<BracketDoubleElimination4Team
			opening1={a1}
			opening2={a2}
			upper={aw}
			lower={al}
			deciding={ad}
		/>
		<StageSeparator title='Group B' />
		<BracketDoubleElimination4Team
			opening1={b1}
			opening2={b2}
			upper={bw}
			lower={bl}
			deciding={bd}
		/>
		<StageSeparator title='Playoffs' />
		<BracketSingleElimination4Team
			semifinals1={sf1}
			semifinals2={sf2}
			finals={finals}
		/>
	</>
}
