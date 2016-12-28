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
//        $this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));
        $this->context = Context::getContext();

       /* $this->fieldImageSettings = array('name' => 'image', 'dir' => 'example');*/
		parent::__construct();
    }

    public function renderList()
    {
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
}