@inject('ohcaseyCartHelper', 'App\Ohcasey\Cart')
<script type="text/javascript">
    /*
     * Cart
     */

    var _CART = {!! json_encode(isset($cart) ? $cart->cartSet : $ohcaseyCartHelper->get()->cartSet) !!};
    var CART = {};
    for (var i = 0, len = _CART.length; i < len; ++i) {
        CART[_CART[i].cart_set_id] = _CART[i];
    }

    /*
     * Cart (cases)
     */
    var _CARTCASE = {!! json_encode(isset($cart) ? $cart->cartSetCase : $ohcaseyCartHelper->get()->cartSetCase) !!};
    var CARTCASE = {};
    for (var i = 0, len = _CARTCASE.length; i < len; ++i) {
        CARTCASE[_CARTCASE[i].cart_set_id] = _CARTCASE[i];
    }

    /*
     * Promotion code
     */
    var PROMOTION_CODE = {!! json_encode(isset($cart) ? $cart->promotionCode : $ohcaseyCartHelper->get()->promotionCode) !!};

</script>