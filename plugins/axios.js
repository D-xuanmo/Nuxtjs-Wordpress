export default function ({ $axios, redirect }) {
  $axios.onRequest(config => {
    if (config.data) {
      let data = {}
      for (let [key, value] of Object.entries(config.data)) {
        key !== 'progress' && (data[key] = value)
      }
      if (config.method === 'get') {
        config.progress = config.data.progress
      } else {
        config.progress = config.headers.progress
        delete config.headers.progress
      }
      config.data = data
    }
    return config
  })

  $axios.onResponse(response => {
    response.data = {
      data: response.data,
      status: response.status,
      headers: response.headers,
      statusText: response.statusText
    }
    return response
  })

  $axios.onError(error => {
    const code = parseInt(error.response && error.response.status)
    if (code === 400) {
      redirect('/400')
    }
  })
}
