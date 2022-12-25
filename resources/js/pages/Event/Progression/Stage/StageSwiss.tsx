import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { makeStyles } from '@mui/styles'
import { alpha } from '@mui/material'
import { ButtonBase, Typography, Hidden } from '@mui/material'

import { CompactMatchups } from './StageComponents'

const useStyles = makeStyles(theme => ({
	swissContainer: {
		display: 'grid',
		gridAutoColumns: '1fr',
		gap: theme.spacing(2),
	},
	swissMatchupButton: {
		display: 'grid',
		gridTemplateColumns: '1fr auto 1fr',
		gap: theme.spacing(1),
		marginBottom: theme.spacing(1),
		padding: theme.spacing(1),
		border: '1px solid dimgrey',
		'&:hover': {
			backgroundColor: alpha(theme.palette.text.primary, theme.palette.action.hoverOpacity),
			'@media (hover: none)': {
				backgroundColor: 'transparent',
			},
		},
		'&:focus-visible': {
			backgroundColor: theme.palette.action.selected,
		},
	},
}))

export function SwissMatchups({ matchups, area }) {
	const styles = useStyles()

	return <div style={{ gridArea: area }}>
		{matchups.map(m =>
			<ButtonBase key={m.id} className={styles.swissMatchupButton} component={RouterLink} to={'/Matchups/' + m.id}>
				<Typography component='span' align='center'>{m.team1}</Typography>
				<Typography component='span' color='textSecondary'>{m.score1} : {m.score2}</Typography>
				<Typography component='span' align='center'>{m.team2}</Typography>
			</ButtonBase>
		)}
	</div>
}

export function SwissStage({ zerZer, zerOne, oneZer, zerTwo, oneOne, twoZer, oneTwo, twoOne, twoTwo }) {
	const styles = useStyles()

	return <>
		<Hidden mdDown>
			<div className={styles.swissContainer}>
				<SwissMatchups area='1/3/2/5' matchups={zerZer} />

				<SwissMatchups area='2/2/3/4' matchups={zerOne} />
				<SwissMatchups area='2/4/3/6' matchups={oneZer} />

				<SwissMatchups area='3/1/4/3' matchups={zerTwo} />
				<SwissMatchups area='3/3/4/5' matchups={oneOne} />
				<SwissMatchups area='3/5/4/7' matchups={twoZer} />

				<SwissMatchups area='4/2/5/4' matchups={oneTwo} />
				<SwissMatchups area='4/4/5/6' matchups={twoOne} />

				<SwissMatchups area='5/3/6/5' matchups={twoTwo} />
			</div>
		</Hidden>
		<Hidden mdUp>
			<CompactMatchups title='0-0 matches' matchups={zerZer} />

			<CompactMatchups title='0-1 matches' matchups={zerOne} />
			<CompactMatchups title='1-0 matches' matchups={oneZer} />

			<CompactMatchups title='0-2 matches' matchups={zerTwo} />
			<CompactMatchups title='1-1 matches' matchups={oneOne} />
			<CompactMatchups title='2-0 matches' matchups={twoZer} />

			<CompactMatchups title='1-2 matches' matchups={oneTwo} />
			<CompactMatchups title='2-1 matches' matchups={twoOne} />

			<CompactMatchups title='2-2 matches' matchups={twoTwo} />
		</Hidden>
	</>
}
