import axios from 'axios';

let baseUrl = "http://143.198.208.110:8115/api";
if (location.hostname === "localhost" || location.hostname === "127.0.0.1"){
  baseUrl = "http://localhost:8115/api";
}

const axiosIns = axios.create({
    baseURL: baseUrl,
// You can add your headers here
// ================================
// baseURL: 'https://some-domain.com/api/',
// timeout: 1000,
// headers: {'X-Custom-Header': 'foobar'}
})

export default axiosIns
