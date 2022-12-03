import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, FormControl, InputLabel, MenuItem, Select } from '@mui/material'

import mapList from '../../data/maps'

function MatchupMapForm({ open, matchup, onSubmit, onClose }) {
	const [items, setItems] = React.useState([])

	React.useEffect(() => {
		if (!open) return
		setItems(matchup.games.map(g => g.map))
	}, [open])

	const changeMap = (selectedIndex, changedMap) => setItems(l => l.map((exisitngMap, index) => index === selectedIndex ? changedMap : exisitngMap))

	return <>
		<Dialog open={open} fullWidth>
			<DialogTitle>Matchup maps</DialogTitle>
			<DialogContent>

				{items.map((map, index) =>
					<FormControl key={index} fullWidth margin='normal'>
						<InputLabel variant='filled' id='label-select-map-i'>Map {index + 1}</InputLabel>
						<Select
							variant='filled'
							labelId='label-select-map-i'
							id='select-map'
							value={map || ''}
							onChange={event => changeMap(index, event.target.value)}
						>
							{mapList.map(m => <MenuItem key={m.id} value={m.id}>{m.name}</MenuItem>)}
						</Select>
					</FormControl>
				)}

			</DialogContent>
			<DialogActions>
				<Button onClick={onClose} color='primary'>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(items)} color='primary'>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default MatchupMapForm
