import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { makeStyles } from '@mui/styles'
import { alpha } from '@mui/material'
import { ButtonBase, Typography, Hidden } from '@mui/material'

import { CompactMatchupsList } from './CompactMatchupsList'

const useStyles = makeStyles(theme => ({
	bracketContainer: {
		display: 'grid',
		alignItems: 'center',
		gridAutoRows: '1fr',
		gridTemplateColumns: '1fr 1fr 1fr', // fixed to 3 rounds
		gap: theme.spacing(2),
	},
	bracketMatchupBox: {
		border: '1px solid dimgrey',
		width: 200,
		height: 80,
		display: 'grid',
		gridTemplateRows: '1fr 1fr',
		gridTemplateColumns: '1fr 30px',
		alignItems: 'center',
	},
	bracketMatchupButton: {
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
	bracketTeamName: {
		textAlign: 'right',
	},
	bracketScore: {
		textAlign: 'center',
	},
}))

function BracketMatchup({ matchup, area }) {
	const styles = useStyles()

	if (matchup == null)
		return <>
			<div className={styles.bracketMatchupBox} style={{ gridArea: area }}>
				<Typography component='span' className={styles.bracketTeamName} color='textSecondary'><i>TBD</i></Typography>
				<Typography component='span' className={styles.bracketScore} color='textSecondary'></Typography>
				<Typography component='span' className={styles.bracketTeamName} color='textSecondary'><i>TBD</i></Typography>
				<Typography component='span' className={styles.bracketScore} color='textSecondary'></Typography>
			</div>
		</>

	return <>
		<ButtonBase className={styles.bracketMatchupBox + ' ' + styles.bracketMatchupButton} component={RouterLink} to={'/Matchups/' + matchup.id} style={{ gridArea: area }}>
			<Typography component='span' className={styles.bracketTeamName}>{matchup.team1}</Typography>
			<Typography component='span' className={styles.bracketScore} color='textSecondary'>{matchup.score1}</Typography>
			<Typography component='span' className={styles.bracketTeamName}>{matchup.team2}</Typography>
			<Typography component='span' className={styles.bracketScore} color='textSecondary'>{matchup.score2}</Typography>
		</ButtonBase>
	</>
}

export function BracketSingleElimination4Team({ semifinals1, semifinals2, finals }) {
	const styles = useStyles()

	return <>
		<Hidden mdDown>
			<div className={styles.bracketContainer}>
				<BracketMatchup area='1/1/2/2' matchup={semifinals1} />
				<BracketMatchup area='2/1/3/2' matchup={semifinals2} />
				<BracketMatchup area='1/2/3/3' matchup={finals} />
			</div>
		</Hidden>
		<Hidden mdUp>
			<CompactMatchupsList title='Semifinals' matchups={[semifinals1, semifinals2]} />
			<CompactMatchupsList title='Grand Final' matchups={[finals]} />
		</Hidden>
	</>
}

export function BracketSingleElimination8Team({ quarterfinals1, quarterfinals2, quarterfinals3, quarterfinals4, semifinals1, semifinals2, finals }) {
	const styles = useStyles()

	return <>
		<Hidden mdDown>
			<div className={styles.bracketContainer}>
				<BracketMatchup area='1/1/2/2' matchup={quarterfinals1} />
				<BracketMatchup area='2/1/3/2' matchup={quarterfinals2} />
				<BracketMatchup area='3/1/4/2' matchup={quarterfinals3} />
				<BracketMatchup area='4/1/5/2' matchup={quarterfinals4} />
				<BracketMatchup area='1/2/3/3' matchup={semifinals1} />
				<BracketMatchup area='3/2/5/3' matchup={semifinals2} />
				<BracketMatchup area='1/3/5/4' matchup={finals} />
			</div>
		</Hidden>
		<Hidden mdUp>
			<CompactMatchupsList title='Quarterfinals' matchups={[quarterfinals1, quarterfinals2, quarterfinals3, quarterfinals4]} />
			<CompactMatchupsList title='Semifinals' matchups={[semifinals1, semifinals2]} />
			<CompactMatchupsList title='Grand Final' matchups={[finals]} />
		</Hidden>
	</>
}

export function BracketDoubleElimination4Team({ opening1, opening2, upper, lower, deciding }) {
	const styles = useStyles()

	return <>
		<Hidden mdDown>
			<div className={styles.bracketContainer}>
				<BracketMatchup area='1/1/2/2' matchup={opening1} />
				<BracketMatchup area='2/1/3/2' matchup={opening2} />
				<BracketMatchup area='1/2/3/3' matchup={upper} />
				<BracketMatchup area='3/2/5/3' matchup={lower} />
				<BracketMatchup area='3/3/4/4' matchup={deciding} />
			</div>
		</Hidden>
		<Hidden mdUp>
			<CompactMatchupsList title='Opening matches' matchups={[opening1, opening2]} />

			<CompactMatchupsList title='Upper Bracket match' matchups={[upper]} />
			<CompactMatchupsList title='Lower Bracket match' matchups={[lower]} />

			<CompactMatchupsList title='Deciding match' matchups={[deciding]} />
		</Hidden>
	</>
}
