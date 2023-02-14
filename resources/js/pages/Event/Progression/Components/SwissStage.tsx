import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { ButtonBase, Box, Typography, Hidden } from '@mui/material'

import { IEventMatchup } from '../../EventTypes'
import { CompactMatchupsList } from './CompactMatchupsList'

const swissMatchupButtonSx = {
	border: '1px solid dimgrey',
	display: 'grid',
	gridTemplateColumns: '1fr auto 1fr',
	gap: 1,
	marginBottom: 1,
	padding: 1,
	'&:hover': {
		bgcolor: 'action.hover',
		'@media (hover: none)': { bgcolor: 'transparent', },
	},
	'&:focus-visible': {
		bgcolor: 'action.selected',
	},
}

export function SwissMatchupsList({ matchups, area }: {
	matchups: Array<IEventMatchup>,
	area: string,
}) {
	return <div style={{ gridArea: area }}>
		{matchups.map(m =>
			<ButtonBase key={m.id} sx={swissMatchupButtonSx} component={RouterLink} to={'/Matchups/' + m.id}>
				<Typography component='span' align='center'>{m.team1}</Typography>
				<Typography component='span' color='textSecondary'>{m.score1} : {m.score2}</Typography>
				<Typography component='span' align='center'>{m.team2}</Typography>
			</ButtonBase>
		)}
	</div>
}

export function SwissStage({ zerZer, zerOne, oneZer, zerTwo, oneOne, twoZer, oneTwo, twoOne, twoTwo }: {
	zerZer: Array<IEventMatchup>,
	zerOne: Array<IEventMatchup>,
	oneZer: Array<IEventMatchup>,
	zerTwo: Array<IEventMatchup>,
	oneOne: Array<IEventMatchup>,
	twoZer: Array<IEventMatchup>,
	oneTwo: Array<IEventMatchup>,
	twoOne: Array<IEventMatchup>,
	twoTwo: Array<IEventMatchup>,
}) {
	return <>
		<Hidden mdDown>
			<Box sx={{ display: 'grid', gridAutoColumns: '1fr', gap: 2, }}>
				<SwissMatchupsList area='1/3/2/5' matchups={zerZer} />

				<SwissMatchupsList area='2/2/3/4' matchups={zerOne} />
				<SwissMatchupsList area='2/4/3/6' matchups={oneZer} />

				<SwissMatchupsList area='3/1/4/3' matchups={zerTwo} />
				<SwissMatchupsList area='3/3/4/5' matchups={oneOne} />
				<SwissMatchupsList area='3/5/4/7' matchups={twoZer} />

				<SwissMatchupsList area='4/2/5/4' matchups={oneTwo} />
				<SwissMatchupsList area='4/4/5/6' matchups={twoOne} />

				<SwissMatchupsList area='5/3/6/5' matchups={twoTwo} />
			</Box>
		</Hidden>
		<Hidden mdUp>
			<CompactMatchupsList title='0-0 matches' matchups={zerZer} />

			<CompactMatchupsList title='0-1 matches' matchups={zerOne} />
			<CompactMatchupsList title='1-0 matches' matchups={oneZer} />

			<CompactMatchupsList title='0-2 matches' matchups={zerTwo} />
			<CompactMatchupsList title='1-1 matches' matchups={oneOne} />
			<CompactMatchupsList title='2-0 matches' matchups={twoZer} />

			<CompactMatchupsList title='1-2 matches' matchups={oneTwo} />
			<CompactMatchupsList title='2-1 matches' matchups={twoOne} />

			<CompactMatchupsList title='2-2 matches' matchups={twoTwo} />
		</Hidden>
	</>
}
