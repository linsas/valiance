import React from 'react';
import { IEventMatchup } from '../../EventTypes';
import { BracketSingleElimination8Team } from '../Components/BracketStage';
import { SwissStage } from '../Components/SwissStage';
import { StageSeparator } from "../Components/StageSeparator";

export default function Major24Team({ matchups }: {
	matchups: Array<IEventMatchup>
}) {
	const c00 = matchups.filter(m => m.key === 'c00')
	const c01 = matchups.filter(m => m.key === 'c01')
	const c10 = matchups.filter(m => m.key === 'c10')
	const c02 = matchups.filter(m => m.key === 'c02')
	const c11 = matchups.filter(m => m.key === 'c11')
	const c20 = matchups.filter(m => m.key === 'c20')
	const c21 = matchups.filter(m => m.key === 'c21')
	const c12 = matchups.filter(m => m.key === 'c12')
	const c22 = matchups.filter(m => m.key === 'c22')

	const l00 = matchups.filter(m => m.key === 'l00')
	const l01 = matchups.filter(m => m.key === 'l01')
	const l10 = matchups.filter(m => m.key === 'l10')
	const l02 = matchups.filter(m => m.key === 'l02')
	const l11 = matchups.filter(m => m.key === 'l11')
	const l20 = matchups.filter(m => m.key === 'l20')
	const l21 = matchups.filter(m => m.key === 'l21')
	const l12 = matchups.filter(m => m.key === 'l12')
	const l22 = matchups.filter(m => m.key === 'l22')

	const qf1 = matchups.find(m => m.key === 'qf1') ?? null
	const qf2 = matchups.find(m => m.key === 'qf2') ?? null
	const qf3 = matchups.find(m => m.key === 'qf3') ?? null
	const qf4 = matchups.find(m => m.key === 'qf4') ?? null
	const sf1 = matchups.find(m => m.key === 'sf1') ?? null
	const sf2 = matchups.find(m => m.key === 'sf2') ?? null
	const finals = matchups.find(m => m.key === 'f') ?? null
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
			finals={finals}
		/>
	</>
}
