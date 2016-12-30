<?php


class ShortcodeController extends ModuleAdminController
{

    public function __construct()
    {
        $this->table = 'shortcode_data';
        $this->className = 'ShortcodeData';
        $this->lang = false;
        $this->deleted = false;
        $this->colorOnBackground = false;
        $this->context = Context::getContext();
        $this->bootstrap = true;





        /* $this->fieldImageSettings = array('name' => 'image', 'dir' => 'example');*/
		parent::__construct();

		
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Add / Edit Shortcode'),
                'image' => '../img/admin/contact.gif'
            ),
            'input' => array(
                array('type' => 'text', 'label' => $this->l('Name'), 'name' =>
                    'shortcode_name', 'size' => 50, 'required' => true),
                array('type' => 'text', 'label' => $this->l('Description'), 'name' =>
                    'shortcode_description', 'size' => 50, 'required' => true),
                array('type' => 'textarea', 'label' => $this->l('Content'), 'name' =>
                    'shortcode_content', 'cols' => 50, 'rows' => 5, 'required' => false),

                array('type' => 'text', 'label' => $this->l('status'), 'name' =>
                        'shortcode_status', 'size' => 30, 'required' => true),

                ),
                'submit' => array('title' => $this->l('Save'))
        );

    }

    public function renderList()
    {
        $this->addRowAction('view');

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );
        $this->fields_list = array(
            'id_shortcode_data' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 25
            ),
            'shortcode_name' => array(
                'title' => $this->l('Name'),
                'width' => 200,
            ),
            'shortcode_description' => array(
                'title' => $this->l('Description'),
                'width' => 500,
            ),
            'shortcode_content' => array(
                'title' => $this->l('Content'),
                'width' => 500,
            ),
            'shortcode_status' => array(
                'title' => $this->l('Status'),
                'width' => 50,
            ),
        );

        $lists = parent::renderList();
        parent::initToolbar();
      return $lists;
    }

    protected function processBulkMyAction()
    {
        Tools::dieObject($this->boxes);
    }

    public function renderView()
    {

        $tpl = $this->context->smarty->createTemplate(
            dirname(__FILE__).
            '/../../views/templates/admin/view.tpl');
        $tpl->assign('shortcodes', $this->object);
        return $tpl->fetch();
    }

}