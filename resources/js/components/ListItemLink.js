import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { ListItem } from '@material-ui/core'
import ChevronRightIcon from '@material-ui/icons/ChevronRight'

function ListItemLink({ children, to, dense = false, noChevron = false }) {
	return <ListItem button dense={dense} component={RouterLink} to={to}>
		{children}
		{!noChevron && <ChevronRightIcon />}
	</ListItem>
}

export default ListItemLink
