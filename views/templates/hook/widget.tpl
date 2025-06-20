{**
 * ISC License
 *
 * Copyright (c) 2023 idnovate.com
 * idnovate is a Registered Trademark & Property of idnovate.com, innovación y desarrollo SCP
 *
 * Permission to use, copy, modify, and/or distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
 * REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT,
 * INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM
 * LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
 * OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
 * PERFORMANCE OF THIS SOFTWARE.
 *
 * @author    idnovate
 * @copyright 2023 idnovate
 * @license   https://www.isc.org/licenses/ https://opensource.org/licenses/ISC ISC License
 *}
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    {literal}
    var Tawk_API=Tawk_API || {},$_Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src="https://embed.tawk.to/{/literal}{$id_page|escape:'quotes':'UTF-8'}/{$id_widget|escape:'quotes':'UTF-8'}{literal}";
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    {/literal}

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

    var removeBranding = function() {
        try {
            var element = document.querySelector("iframe[title*=chat]:nth-child(2)").contentDocument.querySelector(`a[class*=tawk-branding]`);

            if (element) {
                element.remove();
            }
        } catch (e) {}
    }

    var tick = 250;
    setInterval(removeBranding, tick);
</script>
<!--End of Tawk.to Script-->
