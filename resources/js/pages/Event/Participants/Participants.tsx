import React from 'react'
import { Box, Typography, Paper, List, ListItemText, ListItemIcon, Divider, Grid } from '@mui/material'
import PlayerIcon from '@mui/icons-material/Person'
import TeamIcon from '@mui/icons-material/Group'

import ListItemLink from '../../../components/ListItemLink'
import ParticipantsEdit from './ParticipantsEdit'

function ParticipantCard({ participant }) {
	return <Grid item xs={12} sm={6}>
		<Paper>
			<List disablePadding>
				<ListItemLink to={'/Teams/' + participant.team.id}>
					<ListItemIcon>
						<TeamIcon />
					</ListItemIcon>
					<ListItemText>
						{participant.name}
						{participant.name !== participant.team.name && <>
							<Typography variant='body2' component='span' color='textSecondary'> now known as </Typography>
							<Typography variant='body2' component='span'>{participant.team.name}</Typography>
						</>}
					</ListItemText>
				</ListItemLink>

				<Divider />

				{participant.players.length === 0 ? (
					<Box p={1}>
						<Typography align='center' color='textSecondary' gutterBottom>This participating team has no players.</Typography>
					</Box>
				) : (
					participant.players.map(player => <ListItemLink key={player.id} dense to={'/Players/' + player.id}>
						<ListItemIcon>
							<PlayerIcon />
						</ListItemIcon>
						<ListItemText>
							{player.alias}
						</ListItemText>
					</ListItemLink>)
				)}
			</List>
		</Paper>
	</Grid>
}

function Participants({ event, update }) {
	if (event.participants.length === 0) return <>
		<ParticipantsEdit event={event} update={update} />
		<Paper>
			<Box my={2} p={2}>
				<Typography align='center' color='textSecondary'>This event has no participants yet.</Typography>
			</Box>
		</Paper>
	</>

	return <>
		<ParticipantsEdit event={event} update={update} />
		<Grid container spacing={2}>
			{event.participants.map((p) => <ParticipantCard key={p.team.id} participant={p} />)}
		</Grid>
	</>
}

export default Participants
