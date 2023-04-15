import React from 'react'

import { IEvent } from '../EventTypes'
import SingleElim4Team from './Structure/SingleElimination4Team'
import SingleElim8Team from './Structure/SingleElimination8Team'
import Minor8Team from './Structure/Minor8Team'
import Minor16Team from './Structure/Minor16Team'
import Major24Team from './Structure/Major24Team'
import AgnosticStructure from './Structure/AgnosticStructure'
import { EventStructure } from './EventStructure'

function ProgressionStructure({ event }: {
	event: IEvent
}) {
	const eventStructure = new EventStructure(event.rounds)

	if (event.format === 1) {
		return <SingleElim4Team structure={eventStructure} />
	} else if (event.format === 2) {
		return <SingleElim8Team structure={eventStructure} />
	} else if (event.format === 3) {
		return <Minor8Team structure={eventStructure} />
	} else if (event.format === 4) {
		return <Minor16Team structure={eventStructure} />
	} else if (event.format === 5) {
		return <Major24Team structure={eventStructure} />
	}
	return <AgnosticStructure rounds={event.rounds} />
}

export default ProgressionStructure
