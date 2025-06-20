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

class AdminTawktoController extends ModuleAdminController
{
    private static function checkIDs($pageId, $widgetId)
    {
        return preg_match('/^[0-9A-Fa-f]{24}$/', $pageId) === 1 && preg_match('/^[a-z0-9]{1,50}$/i', $widgetId) === 1;
    }

    public function ajaxProcessSetWidget()
    {
        if (!Tools::getValue('pageId')
            || !Tools::getValue('widgetId')
            || !(int) Tools::getValue('id_lang_tawkto')
            || !$this->checkIDs(Tools::getValue('pageId'), Tools::getValue('widgetId'))) {
            echo json_encode(['success' => false]);
            exit;
        }

        $widgets = Configuration::get('TAWKTO_WIDGET') ? json_decode(Configuration::get('TAWKTO_WIDGET'), true) : [];

        $lang = new Language((int) Tools::getValue('id_lang_tawkto'));
        if (isset($widgets[(int) Tools::getValue('id_lang_tawkto')])) {
            $lang = new Language((int) Tools::getValue('id_lang_tawkto'));
            $widgets[(int) Tools::getValue('id_lang_tawkto')]['lang'] = $lang->name;
            $widgets[(int) Tools::getValue('id_lang_tawkto')]['id_page'] = Tools::getValue('pageId');
            $widgets[(int) Tools::getValue('id_lang_tawkto')]['id_widget'] = Tools::getValue('widgetId');
        } else {
            $widget = [
                'id_lang_tawkto' => (int) Tools::getValue('id_lang_tawkto'),
                'lang' => $lang->name,
                'id_page' => Tools::getValue('pageId'),
                'id_widget' => Tools::getValue('widgetId'),
            ];
            $widgets[(int) Tools::getValue('id_lang_tawkto')] = $widget;
        }

        Configuration::updateValue('TAWKTO_WIDGET', json_encode($widgets));

        echo json_encode(['success' => true]);
        exit;
    }

    public function ajaxProcessRemoveWidget()
    {
        $widgets = Configuration::get('TAWKTO_WIDGET') ? json_decode(Configuration::get('TAWKTO_WIDGET'), true) : [];
        unset($widgets[(int) Tools::getValue('id_lang_tawkto')]);

        Configuration::updateValue('TAWKTO_WIDGET', json_encode($widgets));

        echo json_encode(['success' => true]);
        exit;
    }
}
