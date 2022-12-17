import React from 'react'
import { BracketSingleElim4Team } from '../ProgressionStages'

export default function SingleElimination4Team({ matchups }) {
	const sf1 = matchups.find(m => m.key === 'sf1')
	const sf2 = matchups.find(m => m.key === 'sf2')
	const finals = matchups.find(m => m.key === 'f')

	return <BracketSingleElim4Team
		semifinals1={sf1}
		semifinals2={sf2}
		finals={finals}
	/>
}
