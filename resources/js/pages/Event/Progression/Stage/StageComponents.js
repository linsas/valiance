import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { ButtonBase, Box, Typography } from '@mui/material'
import { styled } from '@mui/material/styles';

export function StageSeparator({ title }) {
	return <Box my={2} textAlign='center'>
		<Typography component='span' variant='overline' style={{ padding: '8px 32px', borderLeft: '2px solid grey', borderRight: '2px solid grey' }}>{title}</Typography>
	</Box>
}

const CompactMatchupButton = styled(ButtonBase)(({ theme }) => ({
	display: 'flex',
	rowGap: 1,
	columnGap: 3,
	padding: 1,
	justifyContent: 'start',
	'&:hover': {
		backgroundColor: theme.palette.action.hover,
		'@media (hover: none)': { backgroundColor: 'transparent', },
	},
	'&:focus-visible': {
		backgroundColor: theme.palette.action.selected,
	},
}))

export function CompactMatchups({ matchups, title }) {
	if (matchups.filter(m => m != null).length == 0) return null

	return <>
		<Typography sx={{ textDecoration: 'underline', paddingTop: 2, paddingBottom: 2 }}>{title}</Typography>
		{matchups.filter(m => m != null).map(m =>
			<CompactMatchupButton
				key={m.id}
				component={RouterLink}
				to={'/Matchups/' + m.id}
			>
				<Typography component='span' align='center'>{m.team1}</Typography>
				<Typography component='span' color='textSecondary'>{m.score1} : {m.score2}</Typography>
				<Typography component='span' align='center'>{m.team2}</Typography>
			</CompactMatchupButton>
		)}
	</>
}
