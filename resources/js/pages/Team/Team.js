import React from 'react'
import { Box, Grid, Typography, Paper, List, ListItem, ListItemText } from '@mui/material'
import { Alert, Skeleton } from '@mui/material'
import AddIcon from '@mui/icons-material/Add';
import RemoveIcon from '@mui/icons-material/Remove';
import ArrowForwardIcon from '@mui/icons-material/ArrowForward';

import useFetch from '../../utility/useFetch'
import AlertError from '../../components/AlertError'
import ListItemLink from '../../components/ListItemLink'
import TeamEdit from './TeamEdit'
import TeamDelete from './TeamDelete'

function TeamParticipations({ team }) {
	if (team.participations.length === 0) return <Paper>
		<Box my={2} p={2}>
			<Typography align='center' color='textSecondary'>This team has not participated in any events.</Typography>
		</Box>
	</Paper>

	return <>
		<Paper>
			<List>
				<ListItem>
					<ListItemText>Participations:</ListItemText>
				</ListItem>
				{team.participations.map((participation) =>
					<ListItemLink key={participation.tournament.id} to={'/Events/' + participation.tournament.id}>

						<ListItemText>
							<Typography component='span'>{participation.tournament.name}</Typography>
							{team.name !== participation.name && <>
								<Typography component='span' color='textSecondary'> at the time as </Typography>
								<Typography component='span'>{participation.name}</Typography>
							</>}
						</ListItemText>

					</ListItemLink>
				)}
			</List>
		</Paper>
	</>
}

function TeamPlayers({ team }) {
	if (team.players.length === 0) return <Paper>
		<Box my={2} p={2}>
			<Typography align='center' color='textSecondary'>This team does not have any players.</Typography>
		</Box>
	</Paper>

	return <>
		<Paper>
			<List>
				<ListItem>
					<ListItemText>Players:</ListItemText>
				</ListItem>
				{team.players.map((player) =>
					<ListItemLink key={player.id} to={'/Players/' + player.id}>
						<ListItemText>
							<Typography component='span'>{player.alias}</Typography>
						</ListItemText>
					</ListItemLink>
				)}
			</List>
		</Paper>
	</>
}

function TeamTransfer({ transfer }) {
	if (transfer == null) return null

	return <ListItemLink to={'/Players/' + transfer.player.id}>
		<Grid container>
			<Grid item xs={2}>
				<Typography>{transfer.date}</Typography>
			</Grid>
			<Grid item xs={3}>
				{!transfer.isTransferringAway && transfer.otherTeam != null && <>
					<Typography sx={{ textAlign: 'center', display: 'inline-flex' }}>
						{transfer.otherTeam.name}
						<ArrowForwardIcon fontSize='small' />
					</Typography>
				</>}
			</Grid>
			<Grid item xs={4}>
				<Typography sx={{ textAlign: 'center', display: 'inline-flex' }}>
					{transfer.isTransferringAway ? <RemoveIcon /> : <AddIcon />}
					{transfer.player.alias}
				</Typography>
			</Grid>
			<Grid item xs={3}>
				{transfer.isTransferringAway && transfer.otherTeam != null && <>
					<Typography sx={{ textAlign: 'center', display: 'inline-flex' }}>
						<ArrowForwardIcon fontSize='small' />
						{transfer.otherTeam.name}
					</Typography>
				</>}
			</Grid>
		</Grid>
	</ListItemLink>
}

function TeamHistory({ team }) {
	if (team.transfers.length === 0) return null

	return <>
		<Paper>
			<List>
				<ListItem>
					<ListItemText>Team history:</ListItemText>
				</ListItem>
				{team.transfers.map((transfer, index) =>
					<TeamTransfer key={index} transfer={transfer} />
				)}
			</List>
		</Paper>
	</>
}

function Team(props) {
	const [team, setTeam] = React.useState(null)
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchTeam] = useFetch('/api/teams/' + props.match.params.id)

	const getTeam = () => {
		fetchTeam().then(response => setTeam(response.json.data), setError)
	}
	React.useEffect(() => getTeam(), [])

	if (errorFetch != null) return <AlertError error={errorFetch} />

	if (isLoading) return <>
		<Skeleton variant='rectangular' height={150} />
		<Box py={1} />
		<Skeleton variant='rectangular' height={50} />
	</>

	if (team == null) return <Alert severity='warning'>
		There is no data for this team.
	</Alert>

	return <>
		<Paper>
			<Box py={2}>
				<Box px={2} pb={1}>
					<Typography color='textSecondary' gutterBottom>Team</Typography>
					<Typography variant='h4'>{team.name}</Typography>
				</Box>

				<Box px={2} pt={1}>
					<TeamEdit team={team} update={getTeam} />
					<TeamDelete team={team} />
				</Box>
			</Box>
		</Paper>

		<Box my={2} />
		<TeamPlayers team={team} />

		<Box my={2} />
		<TeamParticipations team={team} />

		<Box my={2} />
		<TeamHistory team={team} />

	</>
}

export default Team
