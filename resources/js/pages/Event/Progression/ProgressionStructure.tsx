import React from 'react'

import SingleElim4Team from './Structure/SingleElimination4Team'
import SingleElim8Team from './Structure/SingleElimination8Team'
import Minor8Team from './Structure/Minor8Team'
import Minor16Team from './Structure/Minor16Team'
import Major24Team from './Structure/Major24Team'

function ProgressionStructure({ event }) {
	const matchups = event.matchups

	if (event.format === 1) {
		return <SingleElim4Team matchups={matchups} />
	} else if (event.format === 2) {
		return <SingleElim8Team matchups={matchups} />
	} else if (event.format === 3) {
		return <Minor8Team matchups={matchups} />
	} else if (event.format === 4) {
		return <Minor16Team matchups={matchups} />
	} else if (event.format === 5) {
		return <Major24Team matchups={matchups} />
	}
	return null
}

export default ProgressionStructure
