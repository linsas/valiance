import React from 'react'
import { Paper, Box, Typography, ListItem, ListItemText, Divider, List } from '@mui/material'
import { Skeleton } from '@mui/material'

import useFetch from '../../utility/useFetch'
import AlertError from '../../components/AlertError'
import ListItemLink from '../../components/ListItemLink'
import { ITeamBasic } from './TeamTypes'
import TeamCreate from './TeamCreate'

function TeamList() {
	const [teamsList, setTeamsList] = React.useState<Array<ITeamBasic>>([])
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchTeams] = useFetch<Array<ITeamBasic>>('/teams')

	const getTeams = () => {
		fetchTeams().then(response => setTeamsList(response.data ?? []), setError)
	}
	React.useEffect(() => getTeams(), [])

	if (isLoading) return <>
		<Skeleton variant='rectangular' height={50} />
		<Box py={1} />
		<Skeleton variant='rectangular' height={250} />
	</>

	if (errorFetch != null) return <AlertError error={errorFetch} />

	if (teamsList.length === 0)
		return <Paper>
			<Box p={4} textAlign='center'>
				<Typography variant='h5' color='textSecondary' gutterBottom>
					There are no teams yet.
				</Typography>
				<Typography color='textSecondary'>
					Add some and they'll show up here.
				</Typography>
			</Box>
			<TeamCreate update={getTeams} />
		</Paper>

	return <Paper>
		<List disablePadding>
			<ListItem>
				<ListItemText>Name</ListItemText>
			</ListItem>

			<Divider />

			{teamsList.map((item) => (
				<ListItemLink key={item.id} dense noChevron to={'/Teams/' + item.id}>
					<ListItemText>
						<Typography>
							{item.name}
						</Typography>
					</ListItemText>
				</ListItemLink>
			))}
		</List>

		<TeamCreate update={getTeams} />
	</Paper>
}

export default TeamList
