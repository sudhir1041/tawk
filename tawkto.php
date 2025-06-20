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

include_once dirname(__FILE__) . '/classes/TawkToIdnovateValidation.php';

class TawkTo extends Module
{
    public const TAWKTO_BASE_URL = 'https://plugins.tawk.to';

    public function __construct()
    {
        $this->name = 'tawkto';
        $this->tab = 'front_office_features';
        $this->version = '1.0.15';
        $this->author = 'idnovate';
        $this->bootstrap = true;
        $this->module_key = '169eed7d39e36e43bb926b7d9a2e7f68';
        // $this->author_address = '0xd89bcCAeb29b2E6342a74Bc0e9C82718Ac702160';

        parent::__construct();

        $this->displayName = $this->l('Tawk.to - Live chat integration');
        $this->description = $this->l('Tawk.to - Live chat integration');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall module?');

        $this->tabs[] = [
            'class_name' => 'AdminTawkto',
            'name' => $this->l('Tawk.to'),
            'visible' => false,
        ];
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayHeader')
            && $this->installTab();
    }

    public function installTab()
    {
        if (version_compare(_PS_VERSION_, '1.7.1', '>=')) {
            return true;
        }

        foreach ($this->tabs as $myTab) {
            $id_tab = Tab::getIdFromClassName($myTab['class_name']);
            if (!$id_tab) {
                $tab = new Tab();
                $tab->class_name = $myTab['class_name'];
                $tab->module = $this->name;

                if (isset($myTab['parent_class_name'])) {
                    $tab->id_parent = Tab::getIdFromClassName($myTab['parent_class_name']);
                } else {
                    $tab->id_parent = -1;
                }

                $languages = Language::getLanguages(false);
                foreach ($languages as $lang) {
                    $tab->name[$lang['id_lang']] = $myTab['name'];
                }

                $tab->add();
            }
        }

        return true;
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->uninstallTab()
            && Configuration::updateValue('TAWKTO_WIDGET', '');
    }

    public function uninstallTab()
    {
        if (version_compare(_PS_VERSION_, '1.7.1', '>=')) {
            return true;
        }

        foreach ($this->tabs as $myTab) {
            $idTab = Tab::getIdFromClassName($myTab['class_name']);
            if ($idTab) {
                $tab = new Tab($idTab);
                $tab->delete();
            }
        }

        return true;
    }

    public function hookDisplayHeader($params)
    {
        if (!(int) Tools::getValue('content_only')) {
            $widgets = Configuration::get('TAWKTO_WIDGET') ? json_decode(Configuration::get('TAWKTO_WIDGET'), true) : [];
            if (isset($widgets[(int) $this->context->language->id])) {
                $pageId = $widgets[(int) $this->context->language->id]['id_page'];
                $widgetId = $widgets[(int) $this->context->language->id]['id_widget'];

                $this->context->smarty->assign([
                    'id_widget' => $widgetId,
                    'id_page' => $pageId,
                    'customer_name' => ($this->context->customer->logged ? $this->context->customer->firstname . ' ' .
                        $this->context->customer->lastname : false),
                    'customer_mail' => ($this->context->customer->email ? $this->context->customer->email : false),
                    'products_twk' => $this->context->cart->getProducts(true),
                    'id_cart' => $this->context->cart->id,
                ]);

                if (!empty($pageId) && !empty($widgetId)) {
                    return $this->display(__FILE__, 'widget.tpl');
                }
            }
        }
    }

    public function getContent()
    {
        if ($warnings = $this->getWarnings()) {
            return $this->displayError($warnings);
        }

        if (version_compare($this->version, $this->getDatabaseVersion(), '>')) {
            return $this->displayError($this->l('Upgrade available'));
        }

        if (Tools::isSubmit('submitTawkto')) {
            Configuration::updateValue('TAWKTO_WIDGET', json_encode(Tools::getValue('tawkto')));
        }

        // Widget remove
        if (Tools::getIsset('deletetawkto')) {
            $widgets = json_decode(Configuration::get('TAWKTO_WIDGET'), true);
            unset($widgets[Tools::getValue('id_lang_tawkto')]);
            Configuration::updateValue('TAWKTO_WIDGET', json_encode($widgets));
        } else {
            // Widget edit form
            if (Tools::getValue('id_lang_tawkto')) {
                return $this->renderWidgetForm();
            }
        }

        // List
        return $this->renderList();
    }

    protected function renderWidgetForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        $this->context->smarty->assign([
            'iframe_url' => $this->getIframeUrl(),
            'base_url' => TawkTo::TAWKTO_BASE_URL,
            'controller' => $this->context->link->getAdminLink('AdminTawkto'),
            'tab_id' => (int) $this->context->controller->id,
            'id_lang_tawkto' => (int) Tools::getValue('id_lang_tawkto'),
            'module_link' => AdminController::$currentIndex . '&token=' . Tools::getValue('token') . '&configure=' . $this->name . '&module_name=' . $this->name . '&tab_name=' . $this->tab,
        ]);

        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/iframe.tpl');
    }

    protected function renderList()
    {
        $fields_list = [
            'lang' => [
                'title' => $this->l('Language'),
                'type' => 'text',
                'align' => 'text-center',
            ],
            'id_page' => [
                'title' => $this->l('Page ID'),
                'type' => 'text',
                'align' => 'text-center',
            ],
            'id_widget' => [
                'title' => $this->l('Widget ID'),
                'type' => 'text',
                'align' => 'text-center',
            ],
        ];

        // Display widgets for languages without widgets
        $widgets = Configuration::get('TAWKTO_WIDGET') ? json_decode(Configuration::get('TAWKTO_WIDGET'), true) : [];
        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            if (!isset($widgets[(int) $language['id_lang']])) {
                $lang = new Language((int) $language['id_lang']);
                $widget = [
                    'id_lang_tawkto' => (int) $language['id_lang'],
                    'lang' => $lang->name,
                    'id_page' => '',
                    'id_widget' => '',
                ];
                $widgets[(int) $language['id_lang']] = $widget;
            }
        }

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = ['edit', 'delete'];
        $helper->show_toolbar = true;
        $helper->module = $this;
        $helper->identifier = 'id_lang_tawkto';
        $helper->title = $this->l('Widgets');
        $helper->tpl_vars['icon'] = 'icon-th-large';
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&module_name=' . $this->name . '&tab_name=' . $this->tab;
        $helper->listTotal = count($widgets);

        $html = $helper->generateList($widgets, $fields_list);

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->context->smarty->assign([
                'this_path' => $this->_path,
                'support_id' => '22327',
            ]);

            $available_lang_codes = ['en', 'es', 'fr', 'it', 'de'];
            $default_lang_code = 'en';
            $template_iso_suffix = in_array(strtok($this->context->language->language_code, '-'), $available_lang_codes) ? strtok($this->context->language->language_code, '-') : $default_lang_code;
            $html .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/company/information_' . $template_iso_suffix . '.tpl');
        }

        return $html;
    }

    protected function getIframeUrl()
    {
        return TawkTo::TAWKTO_BASE_URL
            . '/generic/widgets'
            . '?currentPageId=' . Configuration::get('TAWKTO_WIDGET_PAGE_ID')
            . '&currentWidgetId=' . Configuration::get('TAWKTO_WIDGET_WIDGET_ID');
    }

    public function getWarnings($getAll = true)
    {
        $warning = [];

        if (Configuration::get('PS_DISABLE_NON_NATIVE_MODULE')) {
            $warning[] = $this->l('You have to enable non PrestaShop modules at ADVANCED PARAMETERS - PERFORMANCE');
        }

        if (Shop::isFeatureActive() && (Shop::getContext() == Shop::CONTEXT_ALL || Shop::getContext() == Shop::CONTEXT_GROUP)) {
            $warning[] = $this->l('You have to select a shop');
        }

        if (count($warning) && version_compare(_PS_VERSION_, '1.6.1', '<')) {
            return $warning[0];
        }

        if (count($warning) && !$getAll) {
            return $warning[0];
        }

        return $warning;
    }

    public function getDatabaseVersion()
    {
        $query = 'SELECT `version`
            FROM `' . _DB_PREFIX_ . 'module`
            WHERE `name` = \'' . $this->name . '\';';

        return Db::getInstance()->getValue($query);
    }
}
