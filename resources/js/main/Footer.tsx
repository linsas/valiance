import React from 'react'
import { Box, Typography } from '@mui/material'

function Footer() {
	return <>
		<Box sx={{ marginTop: 2, padding: 2, opacity: 0.2 }} component='footer'>
			<Typography color='textSecondary' variant='body2'>&copy; 2022</Typography>
		</Box>
	</>
}

export default Footer
