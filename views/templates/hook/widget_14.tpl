{**
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate.com <info@idnovate.com>
*  @copyright 2020 idnovate.com
*  @license   See above
*}

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    {$tawkto_script}

    Tawk_API.onLoad = function(){
        {if isset($customer_name) && $customer_name && isset($customer_mail) && $customer_mail}
            Tawk_API.addEvent('visitor', {
                'name'  : '{$customer_name|escape:'quotes':'UTF-8'}',
                'email' : '{$customer_mail|escape:'quotes':'UTF-8'}'
            }, function(error){});
        {/if}
    };

    {if isset($products_twk) && count($products_twk)}
        Tawk_API.onLoad = function(){
            Tawk_API.addEvent('products-in-the-cart', {
                'cart-ID' : '{$id_cart|intval}',
                {foreach from=$products_twk item=product_twk name=i}
                'product-{$smarty.foreach.i.iteration|escape:'quotes':'UTF-8'}' : 'ID = {$product_twk.id_product|escape:'quotes':'UTF-8'} | Name = {$product_twk.name|escape:'quotes':'UTF-8'} | Quantity = {$product_twk.quantity|escape:'quotes':'UTF-8'}',
                {/foreach}
            }, function(error){});
        };

        Tawk_API.onChatStarted = function(){
            Tawk_API.addEvent('products-in-the-cart', {
                'cart-ID' : '{$id_cart|intval}',
                {foreach from=$products_twk item=product_twk name=i}
                'product-{$smarty.foreach.i.iteration|escape:'quotes':'UTF-8'}' : 'ID = {$product_twk.id_product|escape:'quotes':'UTF-8'} | Name = {$product_twk.name|escape:'quotes':'UTF-8'} | Quantity = {$product_twk.quantity|escape:'quotes':'UTF-8'}',
                {/foreach}
            }, function(error){});
        };
    {/if}

    {literal}
    if (typeof ajaxCart !== "undefined" && typeof ajaxCart.updateCartInformation === "function") {
        ajaxCart.updateCartInformation = (function() {
            var ajaxCartUpdateCartInformationCached = ajaxCart.updateCartInformation;

            return function(jsonData) {
                ajaxCartUpdateCartInformationCached.apply(this, arguments);
                var products = {};
                for (var i = 0, len = jsonData.products.length; i < len; i++) {
                    key = 'product-'+(parseInt(i)+1);
                    products[key] = "ID = "+jsonData.products[i].id+" | Name = "+jsonData.products[i].name+" | Quantity = "+jsonData.products[i].quantity;
                }

                if (typeof Tawk_API === 'object') {
	                Tawk_API.addEvent('products-in-the-cart',  products, function(error){});
	            }
            };
        })();
    };

    if (typeof updateCartSummary === "function") {
        updateCartSummary = (function() {
            var updateCartSummaryCached = updateCartSummary;

            return function(json) {
                updateCartSummaryCached.apply(this, arguments);
                var products = {};
                for (var i = 0, len = json.products.length; i < len; i++) {
                    key = 'product-'+(parseInt(i)+1);
                    products[key] = "ID = "+json.products[i].id+" | Name = "+json.products[i].name+" | Quantity = "+json.products[i].quantity;
                }

                if (typeof Tawk_API === 'object') {
                	Tawk_API.addEvent('products-in-the-cart',  products, function(error){});
                }
            };
        })();
    };
    {/literal}
</script>
<!--End of Tawk.to Script-->