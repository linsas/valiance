import React from 'react'
import ReactDOM from 'react-dom'

function App() {
	return <div>
		<h1>React App</h1>
	</div>
}

if (document.getElementById('root')) {
	ReactDOM.render(<App />, document.getElementById('root'))
}
