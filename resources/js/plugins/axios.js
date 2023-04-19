import axios from 'axios'

const axiosIns = axios.create({
    baseURL: 'http://http://localhost:8115/',
// You can add your headers here
// ================================
// baseURL: 'https://some-domain.com/api/',
// timeout: 1000,
// headers: {'X-Custom-Header': 'foobar'}
})

export default axiosIns
