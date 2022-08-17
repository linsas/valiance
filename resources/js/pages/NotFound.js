import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { Button, Box } from '@material-ui/core'
import { Alert, AlertTitle } from '@material-ui/lab'

function NotFound() {
	return <Alert severity='error'>
		<AlertTitle>There's nothing here!</AlertTitle>
		<Box my={1}>Looks like you've taken a wrong turn.</Box>
		<Button variant='outlined' color='primary' component={RouterLink} to='/'>Back to Home</Button>
	</Alert>
}

export default NotFound
