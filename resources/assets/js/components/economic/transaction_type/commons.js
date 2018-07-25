import vuestore from '../../../vuestore'

export default {
    transactionCategoryLoader(){
        return vuestore.request({method: 'get', path: lroutes.api.admin.transaction_category.index});
    }
}