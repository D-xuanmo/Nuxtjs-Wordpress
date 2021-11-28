export default function ({ $axios, redirect }) {
  $axios.onRequest(config => {
    if (config.method === 'get') {
      config.data && (config.progress = config.data.progress)
      config.data = null
    } else {
      const contentType = config.headers['Content-Type']
      if (contentType && contentType.indexOf('multipart/form-data') === -1) {
        const data = {}
        for (const [key, value] of Object.entries(config.data)) {
          key !== 'progress' && (data[key] = value)
        }
        config.data = data
      }
      config.progress = config.headers.progress
      delete config.headers.progress
    }
    return config
  })

  $axios.onResponse(response => {
    if (response.config.url.indexOf('xm/v2') !== -1) {
      return {
        headers: response.headers,
        data: response.data
      }
    }
    response.data = {
      data: response.data,
      status: response.status,
      headers: response.headers,
      statusText: response.statusText
    }
    return response
  })

  $axios.onError(error => {
    const { data } = error.response.data
    if ([500, 502, 404].includes(data.status)) {
      redirect(`/${data.status}`)
    } else {
      return Promise.reject(error.response)
    }
  })
}
