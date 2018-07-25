import vuestore from './../../vuestore'

export default {
    menuLinkLoader(){
        return vuestore.request({method: 'get', path: lroutes.api.admin.menu_links.index});
    }
}