import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { ButtonBase, Box, Typography } from '@mui/material'
import { IEventMatchup } from '../../EventTypes'

const bracketMatchupSx = {
	border: '1px solid dimgrey',
	width: 200,
	height: 80,
	display: 'grid',
	gridTemplateRows: '1fr 1fr',
	gridTemplateColumns: '1fr 30px',
	alignItems: 'center',
}

const bracketMatchupButtonSx = {
	...bracketMatchupSx,
	'&:hover': {
		bgcolor: 'action.hover',
		'@media (hover: none)': { bgcolor: 'transparent', },
	},
	'&:focus-visible': {
		bgcolor: 'action.selected',
	},
}

export function BracketContainer({ children }: {
	children: React.ReactNode
}) {
	return <Box sx={{
		display: 'grid',
		alignItems: 'center',
		gridAutoRows: '1fr',
		gridTemplateColumns: '1fr 1fr 1fr', // fixed to 3 rounds
		gap: 2,
	}}>
		{children}
	</Box>
}

export function BracketMatchup({ matchup, area }: {
	matchup: IEventMatchup | null,
	area: string,
}) {
	if (matchup == null)
		return <>
			<Box sx={bracketMatchupSx} style={{ gridArea: area }}>
				<Typography component='span' sx={{ textAlign: 'right' }} color='textSecondary'><i>TBD</i></Typography>
				<Typography component='span' sx={{ textAlign: 'center' }} color='textSecondary'></Typography>
				<Typography component='span' sx={{ textAlign: 'right' }} color='textSecondary'><i>TBD</i></Typography>
				<Typography component='span' sx={{ textAlign: 'center' }} color='textSecondary'></Typography>
			</Box>
		</>

	return <>
		<ButtonBase sx={bracketMatchupButtonSx} component={RouterLink} to={'/Matchups/' + matchup.id} style={{ gridArea: area }}>
			<Typography component='span' sx={{ textAlign: 'right' }}>{matchup.team1}</Typography>
			<Typography component='span' sx={{ textAlign: 'center' }} color='textSecondary'>{matchup.score1}</Typography>
			<Typography component='span' sx={{ textAlign: 'right' }}>{matchup.team2}</Typography>
			<Typography component='span' sx={{ textAlign: 'center' }} color='textSecondary'>{matchup.score2}</Typography>
		</ButtonBase>
	</>
}
