import React from 'react'

import { BracketSingleElim4Team, BracketSingleElim8Team } from './ProgressionStages'

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

function SingleElim8Team({ matchups }) {
	const qf1 = matchups.find(m => m.key === 'qf1')
	const qf2 = matchups.find(m => m.key === 'qf2')
	const qf3 = matchups.find(m => m.key === 'qf3')
	const qf4 = matchups.find(m => m.key === 'qf4')
	const sf1 = matchups.find(m => m.key === 'sf1')
	const sf2 = matchups.find(m => m.key === 'sf2')
	const finals = matchups.find(m => m.key === 'f')

	return <BracketSingleElim8Team
		quarterfinals1={qf1}
		quarterfinals2={qf2}
		quarterfinals3={qf3}
		quarterfinals4={qf4}
		semifinals1={sf1}
		semifinals2={sf2}
		finals={finals}
	/>
}

function ProgressionMatchups({ event }) {
	const matchups = event.matchups

	if (event.format === 1) {
		return <SingleElim4Team matchups={matchups} />
	} else if (event.format === 2) {
		return <SingleElim8Team matchups={matchups} />
	}
	return null
}

export default ProgressionMatchups
