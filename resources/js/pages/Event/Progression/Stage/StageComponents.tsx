import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { ButtonBase, Box, Typography } from '@mui/material'

export function StageSeparator({ title }) {
	return <Box my={2} textAlign='center'>
		<Typography component='span' variant='overline' style={{ padding: '8px 32px', borderLeft: '2px solid grey', borderRight: '2px solid grey' }}>{title}</Typography>
	</Box>
}

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

export function CompactMatchups({ matchups, title }) {
	if (matchups.filter(m => m != null).length == 0) return null

	return <>
		<Typography sx={{ textDecoration: 'underline', paddingTop: 2, paddingBottom: 2 }}>{title}</Typography>
		{matchups.filter(m => m != null).map(m =>
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
