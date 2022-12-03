import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, TextField } from '@mui/material'
import { Autocomplete } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'

function PlayerForm({ open, player: defaultPlayer, onSubmit, onClose }) {
	const context = React.useContext(AppContext)

	if (defaultPlayer == null) defaultPlayer = {}
	const [player, setPlayer] = React.useState(defaultPlayer)

	React.useEffect(() => {
		if (!open) return
		setPlayer(defaultPlayer)
	}, [open])

	const [teamsList, setTeamsList] = React.useState(null)
	const [isLoadingTeams, fetchTeams] = useFetch('/api/teams')

	React.useEffect(() => {
		if (!open) return
		if (isLoadingTeams) return
		if (teamsList != null) return
		fetchTeams().then(response => setTeamsList(response.json.data), context.notifyFetchError)
	}, [open])

	const changeAlias = alias => setPlayer(p => ({ ...p, alias: alias }))
	const changeTeam = team => setPlayer(p => ({ ...p, team: team }))

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
					options={teamsList ?? []}
					value={player.team}
					getOptionLabel={option => option.name || ''}
					isOptionEqualToValue={(option, value) => option.id === value?.id}
					onChange={(_event, option) => changeTeam(option)}
					renderInput={params => <TextField {...params} margin='normal' variant='filled' label='Team' />}
				/>
			</DialogContent>
			<DialogActions>
				<Button onClick={onClose} color='primary'>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(player)} color='primary'>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default PlayerForm
