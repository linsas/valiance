import React from 'react'
import { ApplicationContext } from './AppTypes'

const AppContext = React.createContext<ApplicationContext>({} as ApplicationContext)

export default AppContext
