import React from 'react'
import { Typography } from '@material-ui/core'
import { Alert, AlertTitle } from '@material-ui/lab'

function AlertError({ error }) {
	if (error.name === 'ResponseNotOkError') {
		if (error.result?.json?.message != null)
			return <Alert severity='error'>
				<AlertTitle>{error.result.status} {error.result.statusText}</AlertTitle>
				<Typography>{error.result.json.message}</Typography>
			</Alert>

		else
			return <Alert severity='error'>
				<AlertTitle>An error occured</AlertTitle>
				<Typography>{error.result.status} {error.result.statusText}</Typography>
			</Alert>
	}

	return <Alert severity='error'>
		<AlertTitle>An error occured</AlertTitle>
		<Typography>{error.message}</Typography>
	</Alert>
}

export default AlertError
