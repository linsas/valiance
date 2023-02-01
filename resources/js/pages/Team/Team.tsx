import React from 'react'
import { useParams } from 'react-router-dom';
import { Box, Typography, Paper, List, ListItem, ListItemText, ListItemIcon } from '@mui/material'
import { Alert, Skeleton } from '@mui/material'
import PlayerIcon from '@mui/icons-material/Person'
import AddIcon from '@mui/icons-material/Add';
import RemoveIcon from '@mui/icons-material/Remove';

import useFetch from '../../utility/useFetch'
import AlertError from '../../components/AlertError'
import ListItemLink from '../../components/ListItemLink'
import { ITeam, ITeamTransfer } from './TeamTypes'
import TeamEdit from './TeamEdit'
import TeamDelete from './TeamDelete'

function TeamParticipations({ team }: {
	team: ITeam,
}) {
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

function TeamPlayers({ team }: {
	team: ITeam,
}) {
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
						<ListItemIcon>
							<PlayerIcon />
						</ListItemIcon>
						<ListItemText>
							<Typography component='span'>{player.alias}</Typography>
						</ListItemText>
					</ListItemLink>
				)}
			</List>
		</Paper>
	</>
}

function TeamTransfer({ transfer }: {
	transfer: ITeamTransfer,
}) {
	if (transfer == null) return null

	return <ListItemLink to={'/Players/' + transfer.player.id}>
		<ListItemText>
			<Typography style={{ flex: '1 1 auto', display: 'flex', whiteSpace: 'pre-wrap' }}>
				{transfer.date}:
				{transfer.isTransferringAway ? <RemoveIcon /> : <AddIcon />}
				{transfer.player.alias}
				{' '}
				<Typography component='span' color='textSecondary'>
					{transfer.isTransferringAway ? 'left' : 'joined'}
					{transfer.otherTeam != null ? <>
						{' '}
						({transfer.isTransferringAway ? 'transferred to' : 'transferred from'} {transfer.otherTeam.name})
					</> : null}
				</Typography>
			</Typography>
		</ListItemText>
	</ListItemLink>
}

function TeamHistory({ team }: {
	team: ITeam,
}) {
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

interface FetchResponse {
	data: ITeam;
}

function Team() {
	const routeParams = useParams<{ id: string }>()

	const [team, setTeam] = React.useState<ITeam | null>(null)
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchTeam] = useFetch<FetchResponse>('/api/teams/' + routeParams.id)

	const getTeam = () => {
		fetchTeam().then(response => setTeam(response.json?.data || null), setError)
	}
	React.useEffect(() => getTeam(), [])

	if (errorFetch != null) return <AlertError error={errorFetch} />

	if (isLoading) return <>
		<Skeleton variant='rectangular' height={150} />
		<Box py={1} />
		<Skeleton variant='rectangular' height={50} />
		<Box py={1} />
		<Skeleton variant='rectangular' height={50} />
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
