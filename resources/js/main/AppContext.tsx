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

interface ApplicationContext {
    jwt?: JWT | null;
    setJWT?: React.Dispatch<JWT>;
    notifyFetchError?: (error: any) => void;
}

const AppContext = React.createContext<ApplicationContext>({})

export default AppContext
