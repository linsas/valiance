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
	swiss: {
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
	fallbackTitle: {
		textDecoration: 'underline',
		paddingTop: theme.spacing(2),
		paddingBottom: theme.spacing(2),
	},
	fallbackMatchup: {
		display: 'flex',
		rowGap: theme.spacing(1),
		columnGap: theme.spacing(3),
		padding: theme.spacing(1),
		justifyContent: 'start',
		'&:hover': {
			backgroundColor: alpha(theme.palette.text.primary, theme.palette.action.hoverOpacity),
			'@media (hover: none)': {
				backgroundColor: 'transparent',
			},
		},
		'&:focus-visible': {
			backgroundColor: theme.palette.action.selected,
		},
	}
}))

function FallbackMatchups({ matchups, title }) {
	const styles = useStyles()

	if (matchups.filter(m => m != null).length == 0) return null

	return <>
		<Typography className={styles.fallbackTitle}>{title}</Typography>
		{matchups.filter(m => m != null).map(m =>
			<ButtonBase key={m.id} className={styles.fallbackMatchup} component={RouterLink} to={'/Matchups/' + m.id}>
				<Typography component='span' align='center'>{m.team1}</Typography>
				<Typography component='span' color='textSecondary'>{m.score1} : {m.score2}</Typography>
				<Typography component='span' align='center'>{m.team2}</Typography>
			</ButtonBase>
		)}
	</>
}

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

	return <>
		<Hidden smDown>
			<div className={styles.bracket}>
				<BracketMatchup area='1/1/2/2' matchup={semifinals1} />
				<BracketMatchup area='2/1/3/2' matchup={semifinals2} />
				<BracketMatchup area='1/2/3/3' matchup={finals} />
			</div>
		</Hidden>
		<Hidden mdUp>
			<FallbackMatchups title='Semifinals' matchups={[semifinals1, semifinals2]} />
			<FallbackMatchups title='Grand Final' matchups={[finals]} />
		</Hidden>
	</>
}

export function BracketSingleElim8Team({ quarterfinals1, quarterfinals2, quarterfinals3, quarterfinals4, semifinals1, semifinals2, finals }) {
	const styles = useStyles()

	return <>
		<Hidden smDown>
			<div className={styles.bracket}>
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
			<FallbackMatchups title='Quarterfinals' matchups={[quarterfinals1, quarterfinals2, quarterfinals3, quarterfinals4]} />
			<FallbackMatchups title='Semifinals' matchups={[semifinals1, semifinals2]} />
			<FallbackMatchups title='Grand Final' matchups={[finals]} />
		</Hidden>
	</>
}

export function BracketDoubleElim4Team({ opening1, opening2, upper, lower, deciding }) {
	const styles = useStyles()

	return <>
		<Hidden smDown>
			<div className={styles.bracket}>
				<BracketMatchup area='1/1/2/2' matchup={opening1} />
				<BracketMatchup area='2/1/3/2' matchup={opening2} />
				<BracketMatchup area='1/2/3/3' matchup={upper} />
				<BracketMatchup area='3/2/5/3' matchup={lower} />
				<BracketMatchup area='3/3/4/4' matchup={deciding} />
			</div>
		</Hidden>
		<Hidden mdUp>
			<FallbackMatchups title='Opening matches' matchups={[opening1, opening2]} />

			<FallbackMatchups title='Upper Bracket match' matchups={[upper]} />
			<FallbackMatchups title='Lower Bracket match' matchups={[lower]} />

			<FallbackMatchups title='Deciding match' matchups={[deciding]} />
		</Hidden>
	</>
}

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
		<Hidden smDown>
			<div className={styles.swiss}>
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
			<FallbackMatchups title='0-0 matches' matchups={zerZer} />

			<FallbackMatchups title='0-1 matches' matchups={zerOne} />
			<FallbackMatchups title='1-0 matches' matchups={oneZer} />

			<FallbackMatchups title='0-2 matches' matchups={zerTwo} />
			<FallbackMatchups title='1-1 matches' matchups={oneOne} />
			<FallbackMatchups title='2-0 matches' matchups={twoZer} />

			<FallbackMatchups title='1-2 matches' matchups={oneTwo} />
			<FallbackMatchups title='2-1 matches' matchups={twoOne} />

			<FallbackMatchups title='2-2 matches' matchups={twoTwo} />
		</Hidden>
	</>
}
