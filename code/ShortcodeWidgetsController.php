<?php

class ShortcodeWidgets_Controller extends Controller
{

    public static $allowed_actions = array(
        'forlightbox',
        'ExistingWidgetForm',
	   'loadTitles'
    );

    public function init()
    {
        if (!Permission::check('ADMIN')) {
            $this->redirect(Director::baseURL());
            die();
        }
        i18n::set_locale(Member::currentUser()->Locale);
        parent::init();
    }

    public function forlightbox()
    {
        return $this->ExistingWidgetForm()->forTemplate();
    }

    public function getAvailableWidgets()
    {
        $widgets = array();
        $types = array();
        $allWidgets = ShortcodeWidget::get();
        foreach ($allWidgets as $widget) {
            if($widget->stat('exclude_from_list')){
                continue;
            }
            $extradata = '';
            $summary = $widget->getSummary();
            if($summary != ''){
                $extradata = ' - ' . $summary;
            }
            $widgets[$widget->class][$widget->ID] = $widget->Title . $extradata . ' (ID: '.$widget->ID.')';
            $types[$widget->class] = $widget->cmsTitle();
        }
        asort($types);
        return array(
            'types' => $types,
            'widgets' => $widgets
        );
    }

    public function ExistingWidgetForm()
    {
        $fields = new FieldList(array(
            new LiteralField('desc', '<p>'._t('ShortcodeWidgets.EXISTING_WIDGET_IFNO', 'info').'</p>')
        ));
        $actions = new FieldList();
        $availableWidgets = $this->getAvailableWidgets();
        if(count($availableWidgets) == 0){
            $fields->push(new LiteralField('noWidgets', '<p style="color: red;">' . _t('ShortcodeWidgets.NO_WIDGETS', 'There are no widgets yet.') . '</p>'));
        }else{
            $fields->push(new DropdownField('widgetType', _t('ShortcodeWidgets.TYPE', 'Type'), $availableWidgets['types']));
            foreach($availableWidgets['types'] as $type => $value){
                $fields->push(new DropdownField('existingWidget_' . $type, $value, $availableWidgets['widgets'][$type]));
            }
            $formaction = FormAction::create('handleExistingWidgetForm', _t('ShortcodeWidgets.INSERT_WIDGET', 'Insert widget'));
            $formaction->addExtraClass('ss-ui-action-constructive');
            $formaction->setAttribute('data-icon', 'accept');
            $formaction->setUseButtonTag(true);
            $actions->push($formaction);
        }

        $form = new Form($this, "ExistingWidgetForm", $fields, $actions);
        $form->setFormAction('shortcodewidgets/ExistingWidgetForm');
        $form->unsetValidator();
        $form->disableSecurityToken();
        return $form;
    }

    public function handleExistingWidgetForm($data, Form $form)
    {
        if (array_key_exists('widgetType', $data)) {
            $id = $data['existingWidget_' . $data['widgetType']];
            $widget = ShortcodeWidget::get()->filter(array('ID' => $id))->first();
            $cmsTitle = $widget->cmsTitle();
		   return json_encode(array(
			   "ID" => $id,
			   "type" => $cmsTitle,
			   "title" => $widget->Title
		   ));
        }
        return false;
    }

	public function loadTitles() {
		$content = $this->request->postVar('content');
		$parser = new ShortcodeParser();
		$parser->register('widget',array('ShortcodeWidgetHandlerAdmin', 'handle'));
		return $parser->parse($content);
	}

}