import React from 'react'

import { BracketSingleElim4Team } from './ProgressionStages'

function SingleElim4Team({ matchups }) {
	const sf1 = matchups.find(m => m.key === 'sf1')
	const sf2 = matchups.find(m => m.key === 'sf2')
	const finals = matchups.find(m => m.key === 'f')

	return <BracketSingleElim4Team
		semifinals1={sf1}
		semifinals2={sf2}
		finals={finals}
	/>
}

function ProgressionMatchups({ event }) {
	const matchups = event.matchups

	if (event.format === 1) {
		return <SingleElim4Team matchups={matchups} />
	}
	return null
}

export default ProgressionMatchups
