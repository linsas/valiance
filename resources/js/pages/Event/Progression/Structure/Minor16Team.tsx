import React from 'react';
import { IMatchup } from '../../../Matchup/MatchupTypes';
import { BracketSingleElimination8Team, BracketDoubleElimination4Team } from '../Components/BracketStage';
import { StageSeparator } from "../Components/StageSeparator";

export default function Minor16Team({ matchups }: {
	matchups: Array<IMatchup>
}) {
	const a1 = matchups.filter(m => m.key === 'ao')[0]
	const a2 = matchups.filter(m => m.key === 'ao')[1]
	const aw = matchups.find(m => m.key === 'aw') ?? null
	const al = matchups.find(m => m.key === 'al') ?? null
	const ad = matchups.find(m => m.key === 'ad') ?? null

	const b1 = matchups.filter(m => m.key === 'bo')[0]
	const b2 = matchups.filter(m => m.key === 'bo')[1]
	const bw = matchups.find(m => m.key === 'bw') ?? null
	const bl = matchups.find(m => m.key === 'bl') ?? null
	const bd = matchups.find(m => m.key === 'bd') ?? null

	const c1 = matchups.filter(m => m.key === 'co')[0]
	const c2 = matchups.filter(m => m.key === 'co')[1]
	const cw = matchups.find(m => m.key === 'cw') ?? null
	const cl = matchups.find(m => m.key === 'cl') ?? null
	const cd = matchups.find(m => m.key === 'cd') ?? null

	const d1 = matchups.filter(m => m.key === 'do')[0]
	const d2 = matchups.filter(m => m.key === 'do')[1]
	const dw = matchups.find(m => m.key === 'dw') ?? null
	const dl = matchups.find(m => m.key === 'dl') ?? null
	const dd = matchups.find(m => m.key === 'dd') ?? null

	const qf1 = matchups.find(m => m.key === 'qf1') ?? null
	const qf2 = matchups.find(m => m.key === 'qf2') ?? null
	const qf3 = matchups.find(m => m.key === 'qf3') ?? null
	const qf4 = matchups.find(m => m.key === 'qf4') ?? null
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
		<StageSeparator title='Group C' />
		<BracketDoubleElimination4Team
			opening1={c1}
			opening2={c2}
			upper={cw}
			lower={cl}
			deciding={cd}
		/>
		<StageSeparator title='Group D' />
		<BracketDoubleElimination4Team
			opening1={d1}
			opening2={d2}
			upper={dw}
			lower={dl}
			deciding={dd}
		/>
		<StageSeparator title='Playoffs' />
		<BracketSingleElimination8Team
			quarterfinals1={qf1}
			quarterfinals2={qf2}
			quarterfinals3={qf3}
			quarterfinals4={qf4}
			semifinals1={sf1}
			semifinals2={sf2}
			finals={finals}
		/>
	</>
}
