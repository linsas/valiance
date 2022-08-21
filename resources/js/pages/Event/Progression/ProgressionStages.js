import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { ButtonBase, Typography, makeStyles, alpha, Hidden } from '@material-ui/core'

const useStyles = makeStyles(theme => ({
	bracket: {
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

export function BracketSingleElim4Team({ semifinals1, semifinals2, finals }) {
	const styles = useStyles()

	return <div className={styles.bracket}>
		<BracketMatchup area='1/1/2/2' matchup={semifinals1} />
		<BracketMatchup area='2/1/3/2' matchup={semifinals2} />
		<BracketMatchup area='1/2/3/3' matchup={finals} />
	</div>
}

export function BracketSingleElim8Team({ quarterfinals1, quarterfinals2, quarterfinals3, quarterfinals4, semifinals1, semifinals2, finals }) {
	const styles = useStyles()

	return <div className={styles.bracket}>
		<BracketMatchup area='1/1/2/2' matchup={quarterfinals1} />
		<BracketMatchup area='2/1/3/2' matchup={quarterfinals2} />
		<BracketMatchup area='3/1/4/2' matchup={quarterfinals3} />
		<BracketMatchup area='4/1/5/2' matchup={quarterfinals4} />
		<BracketMatchup area='1/2/3/3' matchup={semifinals1} />
		<BracketMatchup area='3/2/5/3' matchup={semifinals2} />
		<BracketMatchup area='1/3/5/4' matchup={finals} />
	</div>
}
