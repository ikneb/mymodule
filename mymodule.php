<?php
if (!defined('_PS_VERSION_'))
    exit;

/* Checking compatibility with older PrestaShop and fixing it */
if (!defined('_MYSQL_ENGINE_'))
    define('_MYSQL_ENGINE_', 'MyISAM');
/* Loading Models */
require_once(_PS_MODULE_DIR_ . 'mymodule/models/ShortcodeData.php');


class MyModule extends Module
{
    public function __construct()
    {
        $this->name = 'mymodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Firstname Lastname';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Shortcode');
        $this->description = $this->l('Description of my module.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME'))
            $this->warning = $this->l('No name provided');
    }


    public function install()
    {
        $sql = array();
        include(dirname(__FILE__) . '/sql/install.php');
        foreach ($sql as $s)
            if (!Db::getInstance()->execute($s))
                return false;


        /*$tab = new Tab();
        foreach (Language::getLanguages() as $language) {
            $tab->name[$language['id_lang']] = 'Shortcode';
        }
        $tab->class_name = 'Shortcode';
        $tab->module = 'mymodule';
        $tab->id_parent = 72; // Root tab
        $tab->add();*/
        $this->installTab('AdminCatalog','Shortcode','Shortcode');

        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        return parent::install() &&
            $this->registerHook('displayProductButtons') &&
            $this->registerHook('displayHeader');
    }

    public function installTab($parent, $class_name, $name)
    {
        // Create new admin tab
        $tab = new Tab();
        $tab->id_parent = (int)Tab::getIdFromClassName($parent);
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang)
            $tab->name[$lang['id_lang']] = $name;
        $tab->class_name = $class_name;
        $tab->module = $this->name;
        $tab->active = 1;
        return $tab->add();
    }

    public function uninstallTab($class_name)
    {
        // Retrieve Tab ID
        $id_tab = (int)Tab::getIdFromClassName($class_name);
        // Load tab
        $tab = new Tab((int)$id_tab);
        // Delete it
        return $tab->delete();
    }


    public function hookDisplayHeader($params)
    {
        $this->context->controller->addJS($this->_path . 'views/js/mymod.js');
        $this->context->controller->addCSS($this->_path . 'views/css/mymod.css', 'all');
    }

    public function processProductButtons()
    {
        if (Tools::isSubmit('mymod_pc_submit_comment')) {
            $id_product = Tools::getValue('id_product');
            $grade = Tools::getValue('grade');
            $comment = Tools::getValue('comment');
            /*$insert = array(
                'id_product' => (int)$id_product,
                'grade' => (int)$grade,
                'comment' => pSQL($comment),
                'date_add' => date('Y-m-d H:i:s'),
            );*/
            $MyModComment = new MyModComment();
            $MyModComment->id_product = (int)$id_product;
            /*$MyModComment->firstname = $firstname;
            $MyModComment->lastname = $lastname;
            $MyModComment->email = $email;*/
            $MyModComment->grade = (int)$grade;
            $MyModComment->comment = nl2br($comment);
            $MyModComment->add();

            /*if (!Validate::isName($firstname) || !Validate::isName($lastname) ||
                !Validate::isEmail($email))
            {
                $this->context->smarty->assign('new_comment_posted', 'error');
                return false;
            }*/
//            Db::getInstance()->insert('ps_mymod_comment', $insert);
        }
    }

    public function assignProductButtons()
    {
        $enable_grades = Configuration::get('MYMOD_GRADES');
        $enable_comments = Configuration::get('MYMOD_COMMENTS');
        $id_product = Tools::getValue('id_product');
        $comments = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'mymod_comment WHERE id_product = ' . (int)$id_product);
        $this->context->smarty->assign('enable_grades', $enable_grades);
        $this->context->smarty->assign('enable_comments', $enable_comments);
        $this->context->smarty->assign('comments', $comments);
        $this->context->smarty->assign('id_product', $id_product);
    }


    public function hookDisplayProductButtons($params)
    {
        $this->processProductButtons();
        $this->assignProductButtons();
        return $this->display(__FILE__, 'displayProductTabContent.tpl');
    }


    public function processConfiguration()
    {
        if (Tools::isSubmit('mymod_pc_form')) {
            $enable_grades = Tools::getValue('enable_grades');
            $enable_comments = Tools::getValue('enable_comments');
            Configuration::updateValue('MYMOD_GRADES', $enable_grades);
            Configuration::updateValue('MYMOD_COMMENTS', $enable_comments);

            $this->context->smarty->assign('confirmation', 'ok');
        }
    }

    public function assignConfiguration()
    {
        $enable_grades = Configuration::get('MYMOD_GRADES');
        $enable_comments = Configuration::get('MYMOD_COMMENTS');
        $this->context->smarty->assign('enable_grades', $enable_grades);
        $this->context->smarty->assign('enable_comments', $enable_comments);
    }

    public function renderForm()
    {
        $this->l('My Module configuration');
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('My Module configuration'),
                    'icon' => 'icon-envelope'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable grades:'),
                        'name' => 'enable_grades',
                        'desc' => $this->l('Enable grades on products.'),
                        'values' => array(
                            array(
                                'id' => 'enable_grades_1',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'enable_grades_0',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable comments:'),
                        'name' => 'enable_comments',
                        'desc' => $this->l('Enable comments on products.'),
                        'values' => array(
                            array(
                                'id' => 'enable_comments_1',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'enable_comments_0',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->table = 'mymodcomments';
        $helper->default_form_language =
            (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang =
            (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->submit_action = 'mymod_pc_form';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules',
                false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(
                'enable_grades' => Tools::getValue('enable_grades',
                    Configuration::get('MYMOD_GRADES')),
                'enable_comments' => Tools::getValue('enable_comments',
                    Configuration::get('MYMOD_COMMENTS')),
            ),
            'languages' => $this->context->controller->getLanguages()
        );
        return $helper->generateForm(array($fields_form));

    }

    public function getContent()
    {
//        $this->processConfiguration();
//        $this->assignConfiguration();
//        $this->display(__FILE__, 'getContent.tpl');
        $html_form = $this->renderForm();
        return $html_form;
    }


    public function uninstall()
    {
        $sql = array();
        include(dirname(__FILE__) . '/sql/uninstall.php');
        foreach ($sql as $s)
            if (!Db::getInstance()->execute($s))
                return false;

        /*$moduleTabs = Tab::getCollectionFromModule($this->name);
        if (!empty($moduleTabs)) {
            foreach ($moduleTabs as $moduleTab) {
                $moduleTab->delete();
            }
        }*/

        // Uninstall admin tab
        if (!$this->uninstallTab('AdminMyModComments'))
            return false;

        if (!parent::uninstall() ||
            !Configuration::deleteByName('MYMODULE_NAME')
        )
            return false;

        return true;
    }
}