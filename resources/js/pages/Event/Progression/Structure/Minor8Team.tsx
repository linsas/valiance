import React from 'react'
import { BracketSingleElimination4Team, BracketDoubleElimination4Team } from '../Components/BracketStage'
import { StageSeparator } from "../Components/StageSeparator";

export default function Minor8Team({ matchups }) {
	const a1 = matchups.filter(m => m.key === 'ao')[0]
	const a2 = matchups.filter(m => m.key === 'ao')[1]
	const aw = matchups.find(m => m.key === 'aw')
	const al = matchups.find(m => m.key === 'al')

	const b1 = matchups.filter(m => m.key === 'bo')[0]
	const b2 = matchups.filter(m => m.key === 'bo')[1]
	const bw = matchups.find(m => m.key === 'bw')
	const bl = matchups.find(m => m.key === 'bl')

	const ad = matchups.find(m => m.key === 'ad')
	const bd = matchups.find(m => m.key === 'bd')

	const sf1 = matchups.find(m => m.key === 'sf1')
	const sf2 = matchups.find(m => m.key === 'sf2')
	const finals = matchups.find(m => m.key === 'f')
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
