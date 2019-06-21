export default function ({ $axios, redirect }) {
  $axios.onRequest(config => {
    if (config.data && config.data.progress !== undefined) {
      let data = {}
      for (let [key, value] of Object.entries(config.data)) {
        key !== 'progress' && (data[key] = value)
      }
      config.progress = config.data.progress
      config.data = data
    }
    console.log('Making request to ' + config.url)
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
