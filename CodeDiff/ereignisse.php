<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page_includes.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthentication()->applyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class ereignissePage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Ereignisse');
            $this->SetMenuLabel('Ereignisse');
    
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`ereignisse`');
            $this->dataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new IntegerField('aktienid', true),
                    new StringField('type', true),
                    new DateTimeField('datetime', true),
                    new IntegerField('wert'),
                    new StringField('verhaeltnis')
                )
            );
            $this->dataset->AddLookupField('aktienid', 'aktien', new IntegerField('id'), new StringField('name', false, false, false, false, 'aktienid_name', 'aktienid_name_aktien'), 'aktienid_name_aktien');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(100);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'id', 'id', 'Id'),
                new FilterColumn($this->dataset, 'aktienid', 'aktienid_name', 'Aktienid'),
                new FilterColumn($this->dataset, 'datetime', 'datetime', 'Datetime'),
                new FilterColumn($this->dataset, 'verhaeltnis', 'verhaeltnis', 'Verhaeltnis'),
                new FilterColumn($this->dataset, 'type', 'type', 'Type'),
                new FilterColumn($this->dataset, 'wert', 'wert', 'Wert')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id'])
                ->addColumn($columns['aktienid'])
                ->addColumn($columns['datetime'])
                ->addColumn($columns['verhaeltnis'])
                ->addColumn($columns['type'])
                ->addColumn($columns['wert']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('aktienid')
                ->setOptionsFor('datetime');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_edit');
            
            $filterBuilder->addColumn(
                $columns['id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DynamicCombobox('aktienid_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_ereignisse_aktienid_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('aktienid', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_ereignisse_aktienid_search');
            
            $text_editor = new TextEdit('aktienid');
            
            $filterBuilder->addColumn(
                $columns['aktienid'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('datetime_edit', false, 'd.m.Y, H:i:s');
            
            $filterBuilder->addColumn(
                $columns['datetime'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('verhaeltnis_edit');
            $main_editor->SetMaxLength(16);
            
            $filterBuilder->addColumn(
                $columns['verhaeltnis'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('type_edit');
            $main_editor->SetMaxLength(16);
            
            $filterBuilder->addColumn(
                $columns['type'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('wert_edit');
            
            $filterBuilder->addColumn(
                $columns['wert'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for id field
            //
            $column = new NumberViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('aktienid', 'aktienid_name', 'Aktienid', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for datetime field
            //
            $column = new DateTimeViewColumn('datetime', 'datetime', 'Datetime', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y, H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for verhaeltnis field
            //
            $column = new TextViewColumn('verhaeltnis', 'verhaeltnis', 'Verhaeltnis', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for type field
            //
            $column = new TextViewColumn('type', 'type', 'Type', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for wert field
            //
            $column = new NumberViewColumn('wert', 'wert', 'Wert', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new NumberViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('aktienid', 'aktienid_name', 'Aktienid', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for datetime field
            //
            $column = new DateTimeViewColumn('datetime', 'datetime', 'Datetime', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y, H:i:s');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for verhaeltnis field
            //
            $column = new TextViewColumn('verhaeltnis', 'verhaeltnis', 'Verhaeltnis', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for type field
            //
            $column = new TextViewColumn('type', 'type', 'Type', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for wert field
            //
            $column = new NumberViewColumn('wert', 'wert', 'Wert', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for aktienid field
            //
            $editor = new DynamicCombobox('aktienid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`aktien`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('wkn'),
                    new StringField('isin'),
                    new StringField('ba_id'),
                    new StringField('f100id'),
                    new StringField('ariva_id'),
                    new StringField('boerse_id'),
                    new StringField('kurs_dat'),
                    new IntegerField('kurs'),
                    new IntegerField('aenderung'),
                    new IntegerField('aenderung_p'),
                    new StringField('kurs_dat_neu'),
                    new IntegerField('kurs_neu'),
                    new IntegerField('aenderung_neu'),
                    new IntegerField('aenderung_p_neu'),
                    new IntegerField('par3m'),
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('par30'),
                    new IntegerField('kgv'),
                    new IntegerField('ausschuettung'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new IntegerField('div_ba'),
                    new IntegerField('div_ba_p'),
                    new IntegerField('dsr_ba'),
                    new StringField('diamanten_ba'),
                    new StringField('branche'),
                    new StringField('land'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Aktienid', 'aktienid', 'aktienid_name', 'edit_ereignisse_aktienid_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for datetime field
            //
            $editor = new DateTimeEdit('datetime_edit', false, 'd.m.Y, H:i:s');
            $editColumn = new CustomEditColumn('Datetime', 'datetime', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for verhaeltnis field
            //
            $editor = new TextEdit('verhaeltnis_edit');
            $editor->SetMaxLength(16);
            $editColumn = new CustomEditColumn('Verhaeltnis', 'verhaeltnis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for type field
            //
            $editor = new TextEdit('type_edit');
            $editor->SetMaxLength(16);
            $editColumn = new CustomEditColumn('Type', 'type', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for wert field
            //
            $editor = new TextEdit('wert_edit');
            $editColumn = new CustomEditColumn('Wert', 'wert', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for aktienid field
            //
            $editor = new DynamicCombobox('aktienid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`aktien`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('wkn'),
                    new StringField('isin'),
                    new StringField('ba_id'),
                    new StringField('f100id'),
                    new StringField('ariva_id'),
                    new StringField('boerse_id'),
                    new StringField('kurs_dat'),
                    new IntegerField('kurs'),
                    new IntegerField('aenderung'),
                    new IntegerField('aenderung_p'),
                    new StringField('kurs_dat_neu'),
                    new IntegerField('kurs_neu'),
                    new IntegerField('aenderung_neu'),
                    new IntegerField('aenderung_p_neu'),
                    new IntegerField('par3m'),
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('par30'),
                    new IntegerField('kgv'),
                    new IntegerField('ausschuettung'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new IntegerField('div_ba'),
                    new IntegerField('div_ba_p'),
                    new IntegerField('dsr_ba'),
                    new StringField('diamanten_ba'),
                    new StringField('branche'),
                    new StringField('land'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Aktienid', 'aktienid', 'aktienid_name', 'multi_edit_ereignisse_aktienid_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for datetime field
            //
            $editor = new DateTimeEdit('datetime_edit', false, 'd.m.Y, H:i:s');
            $editColumn = new CustomEditColumn('Datetime', 'datetime', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for verhaeltnis field
            //
            $editor = new TextEdit('verhaeltnis_edit');
            $editor->SetMaxLength(16);
            $editColumn = new CustomEditColumn('Verhaeltnis', 'verhaeltnis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for type field
            //
            $editor = new TextEdit('type_edit');
            $editor->SetMaxLength(16);
            $editColumn = new CustomEditColumn('Type', 'type', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for wert field
            //
            $editor = new TextEdit('wert_edit');
            $editColumn = new CustomEditColumn('Wert', 'wert', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for aktienid field
            //
            $editor = new DynamicCombobox('aktienid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`aktien`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('wkn'),
                    new StringField('isin'),
                    new StringField('ba_id'),
                    new StringField('f100id'),
                    new StringField('ariva_id'),
                    new StringField('boerse_id'),
                    new StringField('kurs_dat'),
                    new IntegerField('kurs'),
                    new IntegerField('aenderung'),
                    new IntegerField('aenderung_p'),
                    new StringField('kurs_dat_neu'),
                    new IntegerField('kurs_neu'),
                    new IntegerField('aenderung_neu'),
                    new IntegerField('aenderung_p_neu'),
                    new IntegerField('par3m'),
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('par30'),
                    new IntegerField('kgv'),
                    new IntegerField('ausschuettung'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new IntegerField('div_ba'),
                    new IntegerField('div_ba_p'),
                    new IntegerField('dsr_ba'),
                    new StringField('diamanten_ba'),
                    new StringField('branche'),
                    new StringField('land'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Aktienid', 'aktienid', 'aktienid_name', 'insert_ereignisse_aktienid_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for datetime field
            //
            $editor = new DateTimeEdit('datetime_edit', false, 'd.m.Y, H:i:s');
            $editColumn = new CustomEditColumn('Datetime', 'datetime', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for verhaeltnis field
            //
            $editor = new TextEdit('verhaeltnis_edit');
            $editor->SetMaxLength(16);
            $editColumn = new CustomEditColumn('Verhaeltnis', 'verhaeltnis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for type field
            //
            $editor = new TextEdit('type_edit');
            $editor->SetMaxLength(16);
            $editColumn = new CustomEditColumn('Type', 'type', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for wert field
            //
            $editor = new TextEdit('wert_edit');
            $editColumn = new CustomEditColumn('Wert', 'wert', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        private function AddMultiUploadColumn(Grid $grid)
        {
    
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new NumberViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('aktienid', 'aktienid_name', 'Aktienid', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for datetime field
            //
            $column = new DateTimeViewColumn('datetime', 'datetime', 'Datetime', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y, H:i:s');
            $grid->AddPrintColumn($column);
            
            //
            // View column for verhaeltnis field
            //
            $column = new TextViewColumn('verhaeltnis', 'verhaeltnis', 'Verhaeltnis', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for type field
            //
            $column = new TextViewColumn('type', 'type', 'Type', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for wert field
            //
            $column = new NumberViewColumn('wert', 'wert', 'Wert', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new NumberViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('aktienid', 'aktienid_name', 'Aktienid', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for datetime field
            //
            $column = new DateTimeViewColumn('datetime', 'datetime', 'Datetime', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y, H:i:s');
            $grid->AddExportColumn($column);
            
            //
            // View column for verhaeltnis field
            //
            $column = new TextViewColumn('verhaeltnis', 'verhaeltnis', 'Verhaeltnis', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for type field
            //
            $column = new TextViewColumn('type', 'type', 'Type', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for wert field
            //
            $column = new NumberViewColumn('wert', 'wert', 'Wert', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('aktienid', 'aktienid_name', 'Aktienid', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for datetime field
            //
            $column = new DateTimeViewColumn('datetime', 'datetime', 'Datetime', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y, H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for verhaeltnis field
            //
            $column = new TextViewColumn('verhaeltnis', 'verhaeltnis', 'Verhaeltnis', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for type field
            //
            $column = new TextViewColumn('type', 'type', 'Type', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for wert field
            //
            $column = new NumberViewColumn('wert', 'wert', 'Wert', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(true);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setMultiEditAllowed($this->GetSecurityInfo()->HasEditGrant() && true);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(true);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddMultiEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
            $this->AddMultiUploadColumn($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(false);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(false);
            $this->setAllowPrintSelectedRecords(false);
            $this->setExportListAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportSelectedRecordsAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array());
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`aktien`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('wkn'),
                    new StringField('isin'),
                    new StringField('ba_id'),
                    new StringField('f100id'),
                    new StringField('ariva_id'),
                    new StringField('boerse_id'),
                    new StringField('kurs_dat'),
                    new IntegerField('kurs'),
                    new IntegerField('aenderung'),
                    new IntegerField('aenderung_p'),
                    new StringField('kurs_dat_neu'),
                    new IntegerField('kurs_neu'),
                    new IntegerField('aenderung_neu'),
                    new IntegerField('aenderung_p_neu'),
                    new IntegerField('par3m'),
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('par30'),
                    new IntegerField('kgv'),
                    new IntegerField('ausschuettung'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new IntegerField('div_ba'),
                    new IntegerField('div_ba_p'),
                    new IntegerField('dsr_ba'),
                    new StringField('diamanten_ba'),
                    new StringField('branche'),
                    new StringField('land'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_ereignisse_aktienid_search', 'id', 'name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`aktien`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('wkn'),
                    new StringField('isin'),
                    new StringField('ba_id'),
                    new StringField('f100id'),
                    new StringField('ariva_id'),
                    new StringField('boerse_id'),
                    new StringField('kurs_dat'),
                    new IntegerField('kurs'),
                    new IntegerField('aenderung'),
                    new IntegerField('aenderung_p'),
                    new StringField('kurs_dat_neu'),
                    new IntegerField('kurs_neu'),
                    new IntegerField('aenderung_neu'),
                    new IntegerField('aenderung_p_neu'),
                    new IntegerField('par3m'),
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('par30'),
                    new IntegerField('kgv'),
                    new IntegerField('ausschuettung'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new IntegerField('div_ba'),
                    new IntegerField('div_ba_p'),
                    new IntegerField('dsr_ba'),
                    new StringField('diamanten_ba'),
                    new StringField('branche'),
                    new StringField('land'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_ereignisse_aktienid_search', 'id', 'name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`aktien`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('wkn'),
                    new StringField('isin'),
                    new StringField('ba_id'),
                    new StringField('f100id'),
                    new StringField('ariva_id'),
                    new StringField('boerse_id'),
                    new StringField('kurs_dat'),
                    new IntegerField('kurs'),
                    new IntegerField('aenderung'),
                    new IntegerField('aenderung_p'),
                    new StringField('kurs_dat_neu'),
                    new IntegerField('kurs_neu'),
                    new IntegerField('aenderung_neu'),
                    new IntegerField('aenderung_p_neu'),
                    new IntegerField('par3m'),
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('par30'),
                    new IntegerField('kgv'),
                    new IntegerField('ausschuettung'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new IntegerField('div_ba'),
                    new IntegerField('div_ba_p'),
                    new IntegerField('dsr_ba'),
                    new StringField('diamanten_ba'),
                    new StringField('branche'),
                    new StringField('land'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_ereignisse_aktienid_search', 'id', 'name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`aktien`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('wkn'),
                    new StringField('isin'),
                    new StringField('ba_id'),
                    new StringField('f100id'),
                    new StringField('ariva_id'),
                    new StringField('boerse_id'),
                    new StringField('kurs_dat'),
                    new IntegerField('kurs'),
                    new IntegerField('aenderung'),
                    new IntegerField('aenderung_p'),
                    new StringField('kurs_dat_neu'),
                    new IntegerField('kurs_neu'),
                    new IntegerField('aenderung_neu'),
                    new IntegerField('aenderung_p_neu'),
                    new IntegerField('par3m'),
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('par30'),
                    new IntegerField('kgv'),
                    new IntegerField('ausschuettung'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new IntegerField('div_ba'),
                    new IntegerField('div_ba_p'),
                    new IntegerField('dsr_ba'),
                    new StringField('diamanten_ba'),
                    new StringField('branche'),
                    new StringField('land'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_ereignisse_aktienid_search', 'id', 'name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomDefaultValues(&$values, &$handled) 
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, $oldRowData, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $oldRowData, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doFileUpload($fieldName, $rowData, &$result, &$accept, $originalFileName, $originalFileExtension, $fileSize, $tempFileName)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPrepareColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function doPrepareFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function doGetSelectionFilters(FixedKeysArray $columns, &$result)
        {
    
        }
    
        protected function doGetCustomFormLayout($mode, FixedKeysArray $columns, FormLayout $layout)
        {
    
        }
    
        protected function doGetCustomColumnGroup(FixedKeysArray $columns, ViewColumnGroup $columnGroup)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doCalculateFields($rowData, $fieldName, &$value)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
        protected function doAddEnvironmentVariables(Page $page, &$variables)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new ereignissePage("ereignisse", "ereignisse.php", GetCurrentUserPermissionsForPage("ereignisse"), 'UTF-8');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("ereignisse"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
