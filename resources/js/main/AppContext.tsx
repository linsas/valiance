import React from 'react'

interface JWTPayload {
    iat: number;
    nbf: number;
    exp: number;
}

export interface JWT {
    raw: string;
    payload: JWTPayload;
}

export interface ApplicationError {
	name: string;
	message: string;
}

interface ApplicationContext {
    jwt: JWT | null;
    setJWT: React.Dispatch<JWT>;
    notifyFetchError: (error: ApplicationError) => void;
}

const AppContext = React.createContext<ApplicationContext>({} as ApplicationContext)

export default AppContext
