import React from 'react'

import AppContext from '../main/AppContext'
import { ApplicationError } from '../main/AppTypes';

export interface FetchResult<T> {
	response: Response;
	data: T | null;
}

class FetchError<T> implements ApplicationError {
	public title: string;
	public message: string;
	public result: FetchResult<T>;

	constructor(title: string, message: string, result: FetchResult<T>) {
		this.title = title
		this.message = message
		this.result = result
	}
}

type HttpMethod = 'GET' | 'POST' | 'PUT' | 'DELETE'

const apiEndpointPrefix = '/api'

function neverResolve<T>(): Promise<FetchResult<T>> {
	return new Promise(() => { })
}

export default function useFetch<T>(apiEndpoint: string, method: HttpMethod = 'GET'): [
	boolean,
	(data?: unknown) => Promise<FetchResult<T>>
] {
	const [isLoading, setIsLoading] = React.useState(false)

	const context = React.useContext(AppContext)

	const url = apiEndpointPrefix + apiEndpoint

	const controllerRef = React.useRef(new AbortController())
	const isMountedRef = React.useRef(true)

	const fetchData = React.useCallback((payload: any) => {
		controllerRef.current.abort()
		controllerRef.current = new AbortController()
		setIsLoading(true)

		// console.log(window.location.host + url)

		const headers: HeadersInit = {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		}
		if (context.jwt != null) {
			headers['Authorization'] = 'JWT ' + context.jwt.raw
		}

		return fetch(url, {
			signal: controllerRef.current.signal,
			method,
			headers,
			body: JSON.stringify(payload),
		}).then(async response => {
			let json: unknown = null

			if (response.headers.get('Content-Type') === 'application/json') {
				json = await response.json()
			}

			return { response, json }
		}).then(({ response, json }) => {
			const result: FetchResult<T> = {
				response: response,
				data: null,
			}

			// console.log({ response, json })

			if (json != null && typeof json === 'object' && 'data' in json) {
				result.data = json.data as T
			}

			if (!isMountedRef.current) {
				return neverResolve<T>()
			}

			setIsLoading(false)

			if (!response.ok) {
				if (json != null && typeof json === 'object' && 'message' in json) {
					throw new FetchError('An error occured', String(json.message), result)
				}

				throw new FetchError(response.status + ' ' + response.statusText, 'The server did not return a 2xx response.', result)
			}

			if (response.status === 204) {
				return result
			}

			if (result.data == null) {
				throw new FetchError('Bad Response', 'The server did not return any content.', result)
			}

			return result
		}).catch((error: unknown) => {
			if (!isMountedRef.current) return neverResolve<T>()

			setIsLoading(false)

			if (error instanceof FetchError) {
				return Promise.reject(error)
			}

			if (error instanceof DOMException) {
				return Promise.reject({ title: 'Aborted', message: 'The request was interrupted.' } as ApplicationError)
			}

			console.log(error)
			return Promise.reject({ title: 'Unknown Error', message: 'An unknown error has occured.' } as ApplicationError)
		})
	}, [url, method, context.jwt])

	React.useEffect(() => {
		isMountedRef.current = true
		return () => {
			controllerRef.current.abort()
			isMountedRef.current = false
		}
	}, [url, method])

	return [isLoading, fetchData,]
}
