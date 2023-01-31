import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, FormControl, InputLabel, MenuItem, Select } from '@mui/material'

import mapList from '../../data/maps'
import { IGame, IMatchup } from './MatchupTypes'

function MatchupMapForm({ open, matchup, onSubmit, onClose }: {
	open: boolean,
	matchup: IMatchup,
	onSubmit: (game: Array<string>) => void
	onClose: () => void
}) {
	const [items, setItems] = React.useState<Array<string>>([])

	React.useEffect(() => {
		if (!open) return
		setItems(matchup.games.map(g => g.map))
	}, [open])

	const changeMap = (selectedIndex: number, changedMap: string) => setItems(l => l.map((exisitngMap, index) => index === selectedIndex ? changedMap : exisitngMap))

	return <>
		<Dialog open={open} fullWidth>
			<DialogTitle>Matchup maps</DialogTitle>
			<DialogContent>

				{items.map((map, index) =>
					<FormControl key={index} variant='filled' margin='normal' fullWidth>
						<InputLabel id={'label-select-map-' + index}>Map {index + 1}</InputLabel>
						<Select
							labelId={'label-select-map-' + index}
							value={map}
							onChange={event => changeMap(index, event.target.value)}
						>
							{mapList.map(m => <MenuItem key={m.id} value={m.id}>{m.name}</MenuItem>)}
						</Select>
					</FormControl>
				)}

			</DialogContent>
			<DialogActions>
				<Button onClick={onClose}>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(items)}>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default MatchupMapForm
