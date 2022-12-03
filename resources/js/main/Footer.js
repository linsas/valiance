import React from 'react'
import { makeStyles } from '@mui/styles'
import { Typography } from '@mui/material'

const useStyles = makeStyles(theme => ({
	footer: {
		marginTop: theme.spacing(2),
		padding: theme.spacing(2),
		opacity: 0.2,
	}
}))

function Footer() {
	const classes = useStyles()

	return <>
		<footer className={classes.footer}>
			<Typography color='textSecondary' variant='body2'>&copy; 2022</Typography>
		</footer>
	</>
}

export default Footer
