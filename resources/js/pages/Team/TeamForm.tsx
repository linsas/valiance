import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, TextField } from '@mui/material'
import { ITeamPayload } from './TeamTypes'

function TeamForm({ open, team: defaultTeam, onSubmit, onClose }: {
	open: boolean,
	team: ITeamPayload,
	onSubmit: (team: ITeamPayload) => void,
	onClose: () => void,
}) {
	const [team, setTeam] = React.useState<ITeamPayload>(defaultTeam)

	React.useEffect(() => {
		if (!open) return
		setTeam(defaultTeam)
	}, [open])

	const changeName = (name: string) => setTeam(t => ({ ...t, name: name }))

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
				<Button onClick={onClose}>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(team)}>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default TeamForm
