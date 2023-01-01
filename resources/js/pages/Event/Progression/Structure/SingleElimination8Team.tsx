import React from 'react'
import { BracketSingleElimination8Team } from '../Components/BracketStage'

export default function SingleElimination8Team({ matchups }) {
	const qf1 = matchups.find(m => m.key === 'qf1')
	const qf2 = matchups.find(m => m.key === 'qf2')
	const qf3 = matchups.find(m => m.key === 'qf3')
	const qf4 = matchups.find(m => m.key === 'qf4')
	const sf1 = matchups.find(m => m.key === 'sf1')
	const sf2 = matchups.find(m => m.key === 'sf2')
	const finals = matchups.find(m => m.key === 'f')

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
