import React from 'react'
import { IEventMatchup } from '../../EventTypes'
import { BracketSingleElimination8Team } from '../Components/BracketStage'

export default function SingleElimination8Team({ matchups }: {
	matchups: Array<IEventMatchup>
}) {
	const qf1 = matchups.find(m => m.key === 'qf1') ?? null
	const qf2 = matchups.find(m => m.key === 'qf2') ?? null
	const qf3 = matchups.find(m => m.key === 'qf3') ?? null
	const qf4 = matchups.find(m => m.key === 'qf4') ?? null
	const sf1 = matchups.find(m => m.key === 'sf1') ?? null
	const sf2 = matchups.find(m => m.key === 'sf2') ?? null
	const finals = matchups.find(m => m.key === 'f') ?? null

	return <BracketSingleElimination8Team
		quarterfinals1={qf1}
		quarterfinals2={qf2}
		quarterfinals3={qf3}
		quarterfinals4={qf4}
		semifinals1={sf1}
		semifinals2={sf2}
		finals={finals}
	/>
}
