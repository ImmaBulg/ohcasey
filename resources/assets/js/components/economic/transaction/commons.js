import axios from 'axios'
import vuestore from '../../../vuestore'

export default {
    transactionTypeLoader(){
        return vuestore.request({method: 'get', path: lroutes.api.admin.transaction_type.index});
    }
}