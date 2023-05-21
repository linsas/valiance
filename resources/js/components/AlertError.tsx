import React from 'react'
import { Typography } from '@mui/material'
import { Alert, AlertTitle } from '@mui/material'

import { ApplicationError } from '../main/AppTypes'

function AlertError({ error }: {
	error: ApplicationError
}) {
	return <Alert severity='error'>
		<AlertTitle>{error.title}</AlertTitle>
		<Typography>{error.message}</Typography>
	</Alert>

}

export default AlertError
