import React from 'react'
import { IEventMatchup } from '../../EventTypes';
import { BracketSingleElimination4Team, BracketDoubleElimination4Team } from '../Components/BracketStage'
import { StageSeparator } from "../Components/StageSeparator";

export default function Minor8Team({ matchups }: {
	matchups: Array<IEventMatchup>
}) {
	const a1 = matchups.filter(m => m.significance === 'ao')[0]
	const a2 = matchups.filter(m => m.significance === 'ao')[1]
	const aw = matchups.find(m => m.significance === 'aw') ?? null
	const al = matchups.find(m => m.significance === 'al') ?? null

	const b1 = matchups.filter(m => m.significance === 'bo')[0]
	const b2 = matchups.filter(m => m.significance === 'bo')[1]
	const bw = matchups.find(m => m.significance === 'bw') ?? null
	const bl = matchups.find(m => m.significance === 'bl') ?? null

	const ad = matchups.find(m => m.significance === 'ad') ?? null
	const bd = matchups.find(m => m.significance === 'bd') ?? null

	const sf1 = matchups.find(m => m.significance === 'sf1') ?? null
	const sf2 = matchups.find(m => m.significance === 'sf2') ?? null
	const finals = matchups.find(m => m.significance === 'f') ?? null
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
