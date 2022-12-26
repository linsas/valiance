import React from 'react'

type JWTPayload = {
    iat: number,
    nbf: number,
    exp: number,
}

type JWT = {
    raw: string
    payload: JWTPayload
}

type ApplicationContext = {
    jwt?: JWT,
    setJWT?: React.Dispatch<JWT>
    notifyFetchError?: (error: any) => void,
}

const AppContext = React.createContext<ApplicationContext>({})

export default AppContext
