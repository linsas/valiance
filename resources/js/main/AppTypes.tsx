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
	handleFetchError: (error: ApplicationError) => void;
}

export interface ApplicationError {
	title: string;
	message: string;
}
