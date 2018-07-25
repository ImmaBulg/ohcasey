import axios from 'axios'

export default {
    loading: false,

    request(settings){
        return new Promise((resolve, reject) => {
            axios[settings.method](settings.path, settings.method == 'get' ? {params: settings.params} : settings.params)
              .then(response => {
                //do somthing before resolve for any request
                resolve(response)
            })
            .catch(error => {
                //do somthing before reject for any request
                reject(error)
            })
        })
    }
}