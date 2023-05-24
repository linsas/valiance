import React from 'react'
import { Box, Typography } from '@mui/material'

function Footer() {
	const envObject = process.env
	if (envObject == null) return null

	const dateString = envObject.buildDate
	// const dateString = envObject.buildDate == null ? null : (new Date(envObject.buildDate)).toString()

	return <>
		<Box component='footer' sx={{ marginTop: 2, padding: 2, opacity: 0, '&:hover': { opacity: 0.2 } }}>
			<Typography color='textSecondary' variant='body2' component='span'>
				{envObject.revision} {dateString == null ? null : ' - ' + dateString}
			</Typography>
		</Box>
	</>
}

export default Footer
