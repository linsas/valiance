import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { ListItem } from '@mui/material'
import ChevronRightIcon from '@mui/icons-material/ChevronRight'

function ListItemLink({ children, to, dense = false, noChevron = false }) {
	return <ListItem button dense={dense} component={RouterLink} to={to}>
		{children}
		{!noChevron && <ChevronRightIcon />}
	</ListItem>
}

export default ListItemLink
