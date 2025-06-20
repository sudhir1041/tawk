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
{if version_compare($smarty.const._PS_VERSION_,'1.6','<')}
    <fieldset>
        <legend>{l s='Widget' mod='tawkto'}</legend>
        <div style="text-align: center">
            <iframe id="tawkIframe" src="" style="min-height: 300px; width : 100%; border: none;"></iframe>
        </div>
    </fieldset>
{else}
    <div class="bootstrap panel">
        <div class="panel-heading">
            <i class="icon-picture-o"></i> {l s='Widget' mod='tawkto'}
        </div>
        <div style="text-align: center">
            <iframe id="tawkIframe" src="" style="min-height: 400px; width : 100%; border: none;"></iframe>
        </div>
    </div>
{/if}

<script type="text/javascript">
    var currentHost = window.location.protocol + "//" + window.location.host,
        url = "{$iframe_url|escape:'quotes':'UTF-8'}&parentDomain=" + currentHost,
        baseUrl = '{$base_url|escape:'quotes':'UTF-8'}',
        current_id_tab = '{$tab_id|escape:'quotes':'UTF-8'}',
        controller = '{$controller|escape:'quotes':'UTF-8'}',
        id_lang_tawkto = '{$id_lang_tawkto|escape:'quotes':'UTF-8'}';

    {literal}
    jQuery('#tawkIframe').attr('src', url);

    window.addEventListener('message', function(e) {
        if(e.origin === baseUrl) {
            if(e.data.action === 'setWidget') {
                setWidget(e);
            }

            if(e.data.action === 'removeWidget') {
                removeWidget(e);
            }
        }
    });

    function setWidget(e) {
        $.ajax({
            type : 'POST',
            url : controller,
            dataType : 'json',
            data : {
                controller : 'AdminTawkto',
                action : 'setWidget',
                ajax : true,
                id_tab : current_id_tab,
                pageId : e.data.pageId,
                widgetId : e.data.widgetId,
                id_lang_tawkto : id_lang_tawkto,
            },
            success : function(r) {
                if(r.success) {
                    alert("{/literal}{l s='Configuration saved successfully' mod='tawkto'}{literal}");
                } else {
                    alert("{/literal}{l s='Error while saving configuration' mod='tawkto'}{literal}");
                }

                {/literal}
                window.location.href = '{$module_link|escape:'quotes':'UTF-8'}';
                {literal}
            }
        });
    }

    function removeWidget(e) {
        $.ajax({
            type : 'POST',
            url : controller,
            dataType : 'json',
            data : {
                controller : 'AdminTawkto',
                action : 'removeWidget',
                ajax : true,
                id_tab : current_id_tab,
                id_lang_tawkto : id_lang_tawkto,
            },
            success : function(r) {
                if(r.success) {
                    alert("{/literal}{l s='Configuration saved successfully' mod='tawkto'}{literal}");
                } else {
                    alert("{/literal}{l s='Error while saving configuration' mod='tawkto'}{literal}");
                }

                {/literal}
                window.location.href = '{$module_link|escape:'quotes':'UTF-8'}';
                {literal}
            }
        });
    }
    {/literal}
</script>
