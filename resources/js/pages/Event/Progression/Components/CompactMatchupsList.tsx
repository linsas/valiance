import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { ButtonBase, Typography } from '@mui/material'
import { IEventMatchup } from '../../EventTypes'

const compactMatchupButtonSx = {
	display: 'flex',
	rowGap: 1,
	columnGap: 3,
	padding: 1,
	justifyContent: 'start',
	'&:hover': {
		bgcolor: 'action.hover',
		'@media (hover: none)': { bgcolor: 'transparent', },
	},
	'&:focus-visible': {
		bgcolor: 'action.selected',
	},
}

export function CompactMatchupsList({ matchups, title }: {
	matchups: Array<IEventMatchup | null>,
	title: string,
}) {
	const filteredList = matchups.filter((m): m is IEventMatchup => m != null)
	if (filteredList.length == 0) return null

	return <>
		<Typography sx={{ textDecoration: 'underline', paddingTop: 2, paddingBottom: 2 }}>{title}</Typography>
		{filteredList.map(m =>
			<ButtonBase
				sx={compactMatchupButtonSx}
				key={m.id}
				component={RouterLink}
				to={'/Matchups/' + m.id}
			>
				<Typography component='span' align='center'>{m.team1}</Typography>
				<Typography component='span' color='textSecondary'>{m.score1} : {m.score2}</Typography>
				<Typography component='span' align='center'>{m.team2}</Typography>
			</ButtonBase>
		)}
	</>
}
