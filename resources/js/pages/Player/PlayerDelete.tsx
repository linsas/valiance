import React from 'react'
import { useNavigate } from 'react-router-dom'
import { Button } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { IPlayer } from './PlayerTypes'

function PlayerDelete({ player } :{
	player: IPlayer
}) {
	const context = React.useContext(AppContext)
	const navigate = useNavigate()

	const [isDeleting, fetchDelete] = useFetch('/players/' + player.id, 'DELETE')

	const handleDelete = () => {
		fetchDelete().then(() => navigate('/Players'), context.handleFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button onClick={() => handleDelete()}>Delete</Button>
	</>
}

export default PlayerDelete
