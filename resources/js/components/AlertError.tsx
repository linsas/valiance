import React from 'react'
import { Typography } from '@mui/material'
import { Alert, AlertTitle } from '@mui/material'

import { ApplicationError } from '../main/AppTypes'
import { fetchErrorMessageOrNull, isFetchError } from '../utility/useFetch'

function AlertError({ error }: {
	error: ApplicationError
}) {

	if (!isFetchError(error)) {
		return <Alert severity='error'>
			<AlertTitle>An error occured</AlertTitle>
			<Typography>{error.message}</Typography>
		</Alert>
	}

	const message = fetchErrorMessageOrNull(error)
	if (message != null)
		return <Alert severity='error'>
			<AlertTitle>{error.result.status} {error.result.statusText}</AlertTitle>
			<Typography>{message}</Typography>
		</Alert>

	else
		return <Alert severity='error'>
			<AlertTitle>An error occured</AlertTitle>
			<Typography>{error.result.status} {error.result.statusText}</Typography>
		</Alert>

}

export default AlertError
