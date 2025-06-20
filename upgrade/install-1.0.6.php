<?php
/**
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
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_0_6()
{
    $languages = Language::getLanguages(false);
    $widgets = [];
    foreach ($languages as $language) {
        $lang = new Language((int) $language['id_lang']);
        $widget = [
            'id_lang_tawkto' => (int) $language['id_lang'],
            'lang' => $lang->name,
            'id_page' => Configuration::get('TAWKTO_WIDGET_PAGE_ID'),
            'id_widget' => Configuration::get('TAWKTO_WIDGET_WIDGET_ID'),
        ];
        $widgets[(int) $language['id_lang']] = $widget;
    }

    Configuration::updateValue('TAWKTO_WIDGET', json_encode($widgets));
    Configuration::deleteByName('TAWKTO_WIDGET_PAGE_ID');
    Configuration::deleteByName('TAWKTO_WIDGET_WIDGET_ID');

    return true;
}
