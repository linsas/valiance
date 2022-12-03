import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, TextField } from '@mui/material'

function TeamForm({ open, team: defaultTeam, onSubmit, onClose }) {
	if (defaultTeam == null) defaultTeam = {}
	const [team, setTeam] = React.useState(defaultTeam)

	React.useEffect(() => {
		if (!open) return
		setTeam(defaultTeam)
	}, [open])

	const changeName = name => setTeam(t => ({ ...t, name: name }))

	return <>
		<Dialog open={open} fullWidth>
			<DialogTitle>Team</DialogTitle>
			<DialogContent>
				<TextField
					autoFocus
					variant='filled'
					margin='normal'
					label='Name'
					type='text'
					value={team.name}
					onChange={event => changeName(event.target.value)}
					fullWidth
				/>
			</DialogContent>
			<DialogActions>
				<Button onClick={onClose} color='primary'>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(team)} color='primary'>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default TeamForm
