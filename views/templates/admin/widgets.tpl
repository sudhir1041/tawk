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

<style>
    .dashboard { display: none; }
    .help:hover + img {
        display: block;
        position: absolute;
        top: 25px;
        left: 25px;
        z-index: 1;
        background-color: white;
        border: 1px solid black;
        padding: 5px;
        max-width: 100%;
    }
</style>

<form class="form" method="POST" name="submitTawkto">
    <table class="table" cellpadding="0" cellspacing="0" width="100%">
        <thead>
            <tr class="nodrag nodrop">
                <th>{l s='Language' mod='tawkto'}</th>
                <th>{l s='Script' mod='tawkto'}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$widgets item=widget name=widgetLoop}
                <tr {if ($smarty.foreach.widgetLoop.index % 2 == 0)}class='alt_row'{/if}>
                    <td>{$widget['lang']|escape:'html':'UTF-8'}</td>
                    <td><textarea rows="6" cols="100" type="text" name="tawkto[{$widget['id_lang_tawkto']|intval}]">{$widget['script']}</textarea></td>
                    <td><span><img class="help" src="../img/admin/help2.png" alt="{l s='Info' mod='tawkto'}" /><img class="dashboard" src="../modules/tawkto/views/img/dashboard.png" alt="{l s='Info' mod='tawkto'}" /></span>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <p>
        <input type="submit" name="submitTawkto" class="button">
    </p>
</form>
