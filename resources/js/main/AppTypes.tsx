interface JWTPayload {
	iat: number;
	nbf: number;
	exp: number;
}

export interface JWT {
	raw: string;
	payload: JWTPayload;
}

export interface ApplicationContext {
	jwt: JWT | null;
	setJWT: React.Dispatch<JWT>;
	notifyFetchError: (error: ApplicationError) => void;
}

export interface ApplicationError {
	/** The technical name of the error */
	name: string;
	message: string;
}