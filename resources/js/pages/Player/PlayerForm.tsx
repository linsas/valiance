import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, TextField } from '@mui/material'
import { Autocomplete } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { IPlayerPayload } from './PlayerTypes'
import { ITeamBasic } from '../Team/TeamTypes'

function PlayerForm({
	open,
	player: defaultPlayer,
	onSubmit,
	onClose
}:{
	open: boolean,
	player: IPlayerPayload,
	onSubmit: (player: IPlayerPayload) => void,
	onClose: () => void,
}) {
	const context = React.useContext(AppContext)

	const [player, setPlayer] = React.useState(defaultPlayer)

	React.useEffect(() => {
		if (!open) return
		setPlayer(defaultPlayer)
	}, [open])

	const [teamsList, setTeamsList] = React.useState<Array<ITeamBasic>>([])
	const [isLoadingTeams, fetchTeams] = useFetch<{ data: Array<ITeamBasic> }>('/api/teams')

	React.useEffect(() => {
		if (!open) return
		if (isLoadingTeams) return
		fetchTeams().then(response => setTeamsList(response.json?.data ?? []), context.notifyFetchError)
	}, [open])

	const changeAlias = (alias: string) => setPlayer(p => ({ ...p, alias: alias }))
	const changeTeam = (team: ITeamBasic | null) => setPlayer(p => ({ ...p, team: team }))

	return <>
		<Dialog open={open} fullWidth>
			<DialogTitle>Player</DialogTitle>
			<DialogContent>
				<TextField
					autoFocus
					variant='filled'
					margin='normal'
					label='Alias'
					type='text'
					value={player.alias}
					onChange={event => changeAlias(event.target.value)}
					fullWidth
				/>
				<Autocomplete
					options={teamsList}
					value={player.team}
					getOptionLabel={option => option.name || ''}
					isOptionEqualToValue={(option, value) => option.id === value?.id}
					onChange={(_event, option) => changeTeam(option)}
					renderInput={params => <TextField {...params} margin='normal' variant='filled' label='Team' />}
				/>
			</DialogContent>
			<DialogActions>
				<Button onClick={onClose}>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(player)}>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default PlayerForm
