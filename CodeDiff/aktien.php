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
    
    
    
    class aktien_anteilePage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Anteile');
            $this->SetMenuLabel('Anteile');
    
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`anteile`');
            $this->dataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new IntegerField('depotname', true),
                    new IntegerField('aktienname', true),
                    new IntegerField('anzahl'),
                    new IntegerField('wert'),
                    new IntegerField('aenderung_ant'),
                    new IntegerField('preis'),
                    new IntegerField('kosten'),
                    new IntegerField('invest'),
                    new IntegerField('kauf_kurs'),
                    new IntegerField('steigerung'),
                    new IntegerField('gewinn'),
                    new IntegerField('rendite')
                )
            );
            $this->dataset->AddLookupField('depotname', 'depots', new IntegerField('id'), new StringField('name', false, false, false, false, 'depotname_name', 'depotname_name_depots'), 'depotname_name_depots');
            $this->dataset->AddLookupField('aktienname', 'aktien', new IntegerField('id'), new StringField('name', false, false, false, false, 'aktienname_name', 'aktienname_name_aktien'), 'aktienname_name_aktien');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
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
                new FilterColumn($this->dataset, 'depotname', 'depotname_name', 'Depotname'),
                new FilterColumn($this->dataset, 'aktienname', 'aktienname_name', 'Aktienname'),
                new FilterColumn($this->dataset, 'anzahl', 'anzahl', 'Anzahl'),
                new FilterColumn($this->dataset, 'preis', 'preis', 'Preis'),
                new FilterColumn($this->dataset, 'kosten', 'kosten', 'Kosten'),
                new FilterColumn($this->dataset, 'invest', 'invest', 'Invest'),
                new FilterColumn($this->dataset, 'steigerung', 'steigerung', 'Steigerung'),
                new FilterColumn($this->dataset, 'gewinn', 'gewinn', 'Gewinn'),
                new FilterColumn($this->dataset, 'rendite', 'rendite', 'Rendite'),
                new FilterColumn($this->dataset, 'kauf_kurs', 'kauf_kurs', 'Kauf Kurs'),
                new FilterColumn($this->dataset, 'wert', 'wert', 'Wert'),
                new FilterColumn($this->dataset, 'aenderung_ant', 'aenderung_ant', 'Aenderung Ant')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id'])
                ->addColumn($columns['depotname'])
                ->addColumn($columns['aktienname'])
                ->addColumn($columns['anzahl'])
                ->addColumn($columns['preis'])
                ->addColumn($columns['kosten'])
                ->addColumn($columns['invest'])
                ->addColumn($columns['steigerung'])
                ->addColumn($columns['gewinn'])
                ->addColumn($columns['rendite'])
                ->addColumn($columns['kauf_kurs'])
                ->addColumn($columns['wert'])
                ->addColumn($columns['aenderung_ant']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('depotname')
                ->setOptionsFor('aktienname');
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
            
            $main_editor = new DynamicCombobox('depotname_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_aktien_anteile_depotname_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('depotname', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_aktien_anteile_depotname_search');
            
            $text_editor = new TextEdit('depotname');
            
            $filterBuilder->addColumn(
                $columns['depotname'],
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
            
            $main_editor = new DynamicCombobox('aktienname_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_aktien_anteile_aktienname_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('aktienname', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_aktien_anteile_aktienname_search');
            
            $text_editor = new TextEdit('aktienname');
            
            $filterBuilder->addColumn(
                $columns['aktienname'],
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
            
            $main_editor = new TextEdit('anzahl_edit');
            
            $filterBuilder->addColumn(
                $columns['anzahl'],
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
            
            $main_editor = new TextEdit('preis_edit');
            
            $filterBuilder->addColumn(
                $columns['preis'],
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
            
            $main_editor = new TextEdit('kosten_edit');
            
            $filterBuilder->addColumn(
                $columns['kosten'],
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
            
            $main_editor = new TextEdit('invest_edit');
            
            $filterBuilder->addColumn(
                $columns['invest'],
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
            
            $main_editor = new TextEdit('steigerung_edit');
            
            $filterBuilder->addColumn(
                $columns['steigerung'],
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
            
            $main_editor = new TextEdit('gewinn_edit');
            
            $filterBuilder->addColumn(
                $columns['gewinn'],
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
            
            $main_editor = new TextEdit('rendite_edit');
            
            $filterBuilder->addColumn(
                $columns['rendite'],
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
            
            $main_editor = new TextEdit('kauf_kurs_edit');
            
            $filterBuilder->addColumn(
                $columns['kauf_kurs'],
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
            
            $main_editor = new TextEdit('aenderung_ant_edit');
            
            $filterBuilder->addColumn(
                $columns['aenderung_ant'],
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
            $column = new TextViewColumn('depotname', 'depotname_name', 'Depotname', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('aktienname', 'aktienname_name', 'Aktienname', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzahl field
            //
            $column = new NumberViewColumn('anzahl', 'anzahl', 'Anzahl', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for preis field
            //
            $column = new NumberViewColumn('preis', 'preis', 'Preis', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kosten field
            //
            $column = new NumberViewColumn('kosten', 'kosten', 'Kosten', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for invest field
            //
            $column = new NumberViewColumn('invest', 'invest', 'Invest', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for steigerung field
            //
            $column = new NumberViewColumn('steigerung', 'steigerung', 'Steigerung', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for gewinn field
            //
            $column = new NumberViewColumn('gewinn', 'gewinn', 'Gewinn', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for rendite field
            //
            $column = new NumberViewColumn('rendite', 'rendite', 'Rendite', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kauf_kurs field
            //
            $column = new NumberViewColumn('kauf_kurs', 'kauf_kurs', 'Kauf Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
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
            
            //
            // View column for aenderung_ant field
            //
            $column = new NumberViewColumn('aenderung_ant', 'aenderung_ant', 'Aenderung Ant', $this->dataset);
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
            $column = new TextViewColumn('depotname', 'depotname_name', 'Depotname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('aktienname', 'aktienname_name', 'Aktienname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzahl field
            //
            $column = new NumberViewColumn('anzahl', 'anzahl', 'Anzahl', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for preis field
            //
            $column = new NumberViewColumn('preis', 'preis', 'Preis', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kosten field
            //
            $column = new NumberViewColumn('kosten', 'kosten', 'Kosten', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for invest field
            //
            $column = new NumberViewColumn('invest', 'invest', 'Invest', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for steigerung field
            //
            $column = new NumberViewColumn('steigerung', 'steigerung', 'Steigerung', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for gewinn field
            //
            $column = new NumberViewColumn('gewinn', 'gewinn', 'Gewinn', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for rendite field
            //
            $column = new NumberViewColumn('rendite', 'rendite', 'Rendite', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kauf_kurs field
            //
            $column = new NumberViewColumn('kauf_kurs', 'kauf_kurs', 'Kauf Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
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
            
            //
            // View column for aenderung_ant field
            //
            $column = new NumberViewColumn('aenderung_ant', 'aenderung_ant', 'Aenderung Ant', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for depotname field
            //
            $editor = new DynamicCombobox('depotname_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`depots`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('owner'),
                    new StringField('wpperm'),
                    new StringField('f100id', true),
                    new StringField('portf', true),
                    new StringField('start', true),
                    new IntegerField('invest'),
                    new TimeField('last_date_t'),
                    new StringField('last_date', true),
                    new IntegerField('last_wert'),
                    new StringField('akt_date', true),
                    new IntegerField('wert'),
                    new IntegerField('aenderung_dep'),
                    new IntegerField('aenderung_dep_p'),
                    new IntegerField('dividende'),
                    new IntegerField('kosten'),
                    new IntegerField('gewinn'),
                    new IntegerField('gewinn_prozent')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Depotname', 'depotname', 'depotname_name', 'edit_aktien_anteile_depotname_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for aktienname field
            //
            $editor = new DynamicCombobox('aktienname_edit', $this->CreateLinkBuilder());
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Aktienname', 'aktienname', 'aktienname_name', 'edit_aktien_anteile_aktienname_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for anzahl field
            //
            $editor = new TextEdit('anzahl_edit');
            $editColumn = new CustomEditColumn('Anzahl', 'anzahl', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for preis field
            //
            $editor = new TextEdit('preis_edit');
            $editColumn = new CustomEditColumn('Preis', 'preis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kosten field
            //
            $editor = new TextEdit('kosten_edit');
            $editColumn = new CustomEditColumn('Kosten', 'kosten', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for invest field
            //
            $editor = new TextEdit('invest_edit');
            $editColumn = new CustomEditColumn('Invest', 'invest', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for steigerung field
            //
            $editor = new TextEdit('steigerung_edit');
            $editColumn = new CustomEditColumn('Steigerung', 'steigerung', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for gewinn field
            //
            $editor = new TextEdit('gewinn_edit');
            $editColumn = new CustomEditColumn('Gewinn', 'gewinn', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for rendite field
            //
            $editor = new TextEdit('rendite_edit');
            $editColumn = new CustomEditColumn('Rendite', 'rendite', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kauf_kurs field
            //
            $editor = new TextEdit('kauf_kurs_edit');
            $editColumn = new CustomEditColumn('Kauf Kurs', 'kauf_kurs', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
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
            
            //
            // Edit column for aenderung_ant field
            //
            $editor = new TextEdit('aenderung_ant_edit');
            $editColumn = new CustomEditColumn('Aenderung Ant', 'aenderung_ant', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for depotname field
            //
            $editor = new DynamicCombobox('depotname_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`depots`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('owner'),
                    new StringField('wpperm'),
                    new StringField('f100id', true),
                    new StringField('portf', true),
                    new StringField('start', true),
                    new IntegerField('invest'),
                    new TimeField('last_date_t'),
                    new StringField('last_date', true),
                    new IntegerField('last_wert'),
                    new StringField('akt_date', true),
                    new IntegerField('wert'),
                    new IntegerField('aenderung_dep'),
                    new IntegerField('aenderung_dep_p'),
                    new IntegerField('dividende'),
                    new IntegerField('kosten'),
                    new IntegerField('gewinn'),
                    new IntegerField('gewinn_prozent')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Depotname', 'depotname', 'depotname_name', 'multi_edit_aktien_anteile_depotname_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for aktienname field
            //
            $editor = new DynamicCombobox('aktienname_edit', $this->CreateLinkBuilder());
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Aktienname', 'aktienname', 'aktienname_name', 'multi_edit_aktien_anteile_aktienname_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for anzahl field
            //
            $editor = new TextEdit('anzahl_edit');
            $editColumn = new CustomEditColumn('Anzahl', 'anzahl', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for preis field
            //
            $editor = new TextEdit('preis_edit');
            $editColumn = new CustomEditColumn('Preis', 'preis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kosten field
            //
            $editor = new TextEdit('kosten_edit');
            $editColumn = new CustomEditColumn('Kosten', 'kosten', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for invest field
            //
            $editor = new TextEdit('invest_edit');
            $editColumn = new CustomEditColumn('Invest', 'invest', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for steigerung field
            //
            $editor = new TextEdit('steigerung_edit');
            $editColumn = new CustomEditColumn('Steigerung', 'steigerung', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for gewinn field
            //
            $editor = new TextEdit('gewinn_edit');
            $editColumn = new CustomEditColumn('Gewinn', 'gewinn', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for rendite field
            //
            $editor = new TextEdit('rendite_edit');
            $editColumn = new CustomEditColumn('Rendite', 'rendite', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kauf_kurs field
            //
            $editor = new TextEdit('kauf_kurs_edit');
            $editColumn = new CustomEditColumn('Kauf Kurs', 'kauf_kurs', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
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
            
            //
            // Edit column for aenderung_ant field
            //
            $editor = new TextEdit('aenderung_ant_edit');
            $editColumn = new CustomEditColumn('Aenderung Ant', 'aenderung_ant', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for depotname field
            //
            $editor = new DynamicCombobox('depotname_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`depots`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('owner'),
                    new StringField('wpperm'),
                    new StringField('f100id', true),
                    new StringField('portf', true),
                    new StringField('start', true),
                    new IntegerField('invest'),
                    new TimeField('last_date_t'),
                    new StringField('last_date', true),
                    new IntegerField('last_wert'),
                    new StringField('akt_date', true),
                    new IntegerField('wert'),
                    new IntegerField('aenderung_dep'),
                    new IntegerField('aenderung_dep_p'),
                    new IntegerField('dividende'),
                    new IntegerField('kosten'),
                    new IntegerField('gewinn'),
                    new IntegerField('gewinn_prozent')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Depotname', 'depotname', 'depotname_name', 'insert_aktien_anteile_depotname_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for aktienname field
            //
            $editor = new DynamicCombobox('aktienname_edit', $this->CreateLinkBuilder());
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Aktienname', 'aktienname', 'aktienname_name', 'insert_aktien_anteile_aktienname_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for anzahl field
            //
            $editor = new TextEdit('anzahl_edit');
            $editColumn = new CustomEditColumn('Anzahl', 'anzahl', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for preis field
            //
            $editor = new TextEdit('preis_edit');
            $editColumn = new CustomEditColumn('Preis', 'preis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kosten field
            //
            $editor = new TextEdit('kosten_edit');
            $editColumn = new CustomEditColumn('Kosten', 'kosten', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for invest field
            //
            $editor = new TextEdit('invest_edit');
            $editColumn = new CustomEditColumn('Invest', 'invest', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for steigerung field
            //
            $editor = new TextEdit('steigerung_edit');
            $editColumn = new CustomEditColumn('Steigerung', 'steigerung', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for gewinn field
            //
            $editor = new TextEdit('gewinn_edit');
            $editColumn = new CustomEditColumn('Gewinn', 'gewinn', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for rendite field
            //
            $editor = new TextEdit('rendite_edit');
            $editColumn = new CustomEditColumn('Rendite', 'rendite', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kauf_kurs field
            //
            $editor = new TextEdit('kauf_kurs_edit');
            $editColumn = new CustomEditColumn('Kauf Kurs', 'kauf_kurs', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
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
            
            //
            // Edit column for aenderung_ant field
            //
            $editor = new TextEdit('aenderung_ant_edit');
            $editColumn = new CustomEditColumn('Aenderung Ant', 'aenderung_ant', $editor, $this->dataset);
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
            $column = new TextViewColumn('depotname', 'depotname_name', 'Depotname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('aktienname', 'aktienname_name', 'Aktienname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzahl field
            //
            $column = new NumberViewColumn('anzahl', 'anzahl', 'Anzahl', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for preis field
            //
            $column = new NumberViewColumn('preis', 'preis', 'Preis', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for kosten field
            //
            $column = new NumberViewColumn('kosten', 'kosten', 'Kosten', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for invest field
            //
            $column = new NumberViewColumn('invest', 'invest', 'Invest', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for steigerung field
            //
            $column = new NumberViewColumn('steigerung', 'steigerung', 'Steigerung', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for gewinn field
            //
            $column = new NumberViewColumn('gewinn', 'gewinn', 'Gewinn', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for rendite field
            //
            $column = new NumberViewColumn('rendite', 'rendite', 'Rendite', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for kauf_kurs field
            //
            $column = new NumberViewColumn('kauf_kurs', 'kauf_kurs', 'Kauf Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
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
            
            //
            // View column for aenderung_ant field
            //
            $column = new NumberViewColumn('aenderung_ant', 'aenderung_ant', 'Aenderung Ant', $this->dataset);
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
            $column = new TextViewColumn('depotname', 'depotname_name', 'Depotname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('aktienname', 'aktienname_name', 'Aktienname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzahl field
            //
            $column = new NumberViewColumn('anzahl', 'anzahl', 'Anzahl', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for preis field
            //
            $column = new NumberViewColumn('preis', 'preis', 'Preis', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for kosten field
            //
            $column = new NumberViewColumn('kosten', 'kosten', 'Kosten', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for invest field
            //
            $column = new NumberViewColumn('invest', 'invest', 'Invest', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for steigerung field
            //
            $column = new NumberViewColumn('steigerung', 'steigerung', 'Steigerung', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for gewinn field
            //
            $column = new NumberViewColumn('gewinn', 'gewinn', 'Gewinn', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for rendite field
            //
            $column = new NumberViewColumn('rendite', 'rendite', 'Rendite', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for kauf_kurs field
            //
            $column = new NumberViewColumn('kauf_kurs', 'kauf_kurs', 'Kauf Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
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
            
            //
            // View column for aenderung_ant field
            //
            $column = new NumberViewColumn('aenderung_ant', 'aenderung_ant', 'Aenderung Ant', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new NumberViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('depotname', 'depotname_name', 'Depotname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('aktienname', 'aktienname_name', 'Aktienname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzahl field
            //
            $column = new NumberViewColumn('anzahl', 'anzahl', 'Anzahl', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for preis field
            //
            $column = new NumberViewColumn('preis', 'preis', 'Preis', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for kosten field
            //
            $column = new NumberViewColumn('kosten', 'kosten', 'Kosten', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for invest field
            //
            $column = new NumberViewColumn('invest', 'invest', 'Invest', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for steigerung field
            //
            $column = new NumberViewColumn('steigerung', 'steigerung', 'Steigerung', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for gewinn field
            //
            $column = new NumberViewColumn('gewinn', 'gewinn', 'Gewinn', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for rendite field
            //
            $column = new NumberViewColumn('rendite', 'rendite', 'Rendite', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for kauf_kurs field
            //
            $column = new NumberViewColumn('kauf_kurs', 'kauf_kurs', 'Kauf Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
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
            
            //
            // View column for aenderung_ant field
            //
            $column = new NumberViewColumn('aenderung_ant', 'aenderung_ant', 'Aenderung Ant', $this->dataset);
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
            $result->SetUseFixedHeader(false);
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
                '`depots`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('owner'),
                    new StringField('wpperm'),
                    new StringField('f100id', true),
                    new StringField('portf', true),
                    new StringField('start', true),
                    new IntegerField('invest'),
                    new TimeField('last_date_t'),
                    new StringField('last_date', true),
                    new IntegerField('last_wert'),
                    new StringField('akt_date', true),
                    new IntegerField('wert'),
                    new IntegerField('aenderung_dep'),
                    new IntegerField('aenderung_dep_p'),
                    new IntegerField('dividende'),
                    new IntegerField('kosten'),
                    new IntegerField('gewinn'),
                    new IntegerField('gewinn_prozent')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_aktien_anteile_depotname_search', 'id', 'name', null, 20);
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_aktien_anteile_aktienname_search', 'id', 'name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`depots`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('owner'),
                    new StringField('wpperm'),
                    new StringField('f100id', true),
                    new StringField('portf', true),
                    new StringField('start', true),
                    new IntegerField('invest'),
                    new TimeField('last_date_t'),
                    new StringField('last_date', true),
                    new IntegerField('last_wert'),
                    new StringField('akt_date', true),
                    new IntegerField('wert'),
                    new IntegerField('aenderung_dep'),
                    new IntegerField('aenderung_dep_p'),
                    new IntegerField('dividende'),
                    new IntegerField('kosten'),
                    new IntegerField('gewinn'),
                    new IntegerField('gewinn_prozent')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_aktien_anteile_depotname_search', 'id', 'name', null, 20);
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_aktien_anteile_aktienname_search', 'id', 'name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`depots`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('owner'),
                    new StringField('wpperm'),
                    new StringField('f100id', true),
                    new StringField('portf', true),
                    new StringField('start', true),
                    new IntegerField('invest'),
                    new TimeField('last_date_t'),
                    new StringField('last_date', true),
                    new IntegerField('last_wert'),
                    new StringField('akt_date', true),
                    new IntegerField('wert'),
                    new IntegerField('aenderung_dep'),
                    new IntegerField('aenderung_dep_p'),
                    new IntegerField('dividende'),
                    new IntegerField('kosten'),
                    new IntegerField('gewinn'),
                    new IntegerField('gewinn_prozent')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_aktien_anteile_depotname_search', 'id', 'name', null, 20);
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_aktien_anteile_aktienname_search', 'id', 'name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`depots`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new StringField('name'),
                    new StringField('owner'),
                    new StringField('wpperm'),
                    new StringField('f100id', true),
                    new StringField('portf', true),
                    new StringField('start', true),
                    new IntegerField('invest'),
                    new TimeField('last_date_t'),
                    new StringField('last_date', true),
                    new IntegerField('last_wert'),
                    new StringField('akt_date', true),
                    new IntegerField('wert'),
                    new IntegerField('aenderung_dep'),
                    new IntegerField('aenderung_dep_p'),
                    new IntegerField('dividende'),
                    new IntegerField('kosten'),
                    new IntegerField('gewinn'),
                    new IntegerField('gewinn_prozent')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_aktien_anteile_depotname_search', 'id', 'name', null, 20);
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_aktien_anteile_aktienname_search', 'id', 'name', null, 20);
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
    
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class aktien_kursePage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Kurse');
            $this->SetMenuLabel('Kurse');
    
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`kurse`');
            $this->dataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new IntegerField('aktienid', true),
                    new DateTimeField('datetime', true),
                    new IntegerField('kurs')
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
            $partitionNavigator->SetRowsPerPage(20);
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
                new FilterColumn($this->dataset, 'kurs', 'kurs', 'Kurs')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id'])
                ->addColumn($columns['aktienid'])
                ->addColumn($columns['datetime'])
                ->addColumn($columns['kurs']);
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
            $main_editor->SetHandlerName('filter_builder_aktien_kurse_aktienid_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('aktienid', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_aktien_kurse_aktienid_search');
            
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
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('kurs_edit');
            
            $filterBuilder->addColumn(
                $columns['kurs'],
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
            // View column for kurs field
            //
            $column = new NumberViewColumn('kurs', 'kurs', 'Kurs', $this->dataset);
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
            // View column for kurs field
            //
            $column = new NumberViewColumn('kurs', 'kurs', 'Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Aktienid', 'aktienid', 'aktienid_name', 'edit_aktien_kurse_aktienid_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
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
            // Edit column for kurs field
            //
            $editor = new TextEdit('kurs_edit');
            $editColumn = new CustomEditColumn('Kurs', 'kurs', $editor, $this->dataset);
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Aktienid', 'aktienid', 'aktienid_name', 'multi_edit_aktien_kurse_aktienid_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
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
            // Edit column for kurs field
            //
            $editor = new TextEdit('kurs_edit');
            $editColumn = new CustomEditColumn('Kurs', 'kurs', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Aktienid', 'aktienid', 'aktienid_name', 'insert_aktien_kurse_aktienid_search', $editor, $this->dataset, $lookupDataset, 'id', 'name', '');
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
            // Edit column for kurs field
            //
            $editor = new TextEdit('kurs_edit');
            $editColumn = new CustomEditColumn('Kurs', 'kurs', $editor, $this->dataset);
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
            // View column for kurs field
            //
            $column = new NumberViewColumn('kurs', 'kurs', 'Kurs', $this->dataset);
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
            // View column for kurs field
            //
            $column = new NumberViewColumn('kurs', 'kurs', 'Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new NumberViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
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
            // View column for kurs field
            //
            $column = new NumberViewColumn('kurs', 'kurs', 'Kurs', $this->dataset);
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
            $result->SetUseFixedHeader(false);
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_aktien_kurse_aktienid_search', 'id', 'name', null, 20);
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_aktien_kurse_aktienid_search', 'id', 'name', null, 20);
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_aktien_kurse_aktienid_search', 'id', 'name', null, 20);
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_aktien_kurse_aktienid_search', 'id', 'name', null, 20);
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
    
    // OnBeforePageExecute event handler
    
    
    
    class aktienPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Aktien');
            $this->SetMenuLabel('Aktien');
    
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`aktien`');
            $this->dataset->addFields(
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
                    new IntegerField('par1'),
                    new IntegerField('par3'),
                    new IntegerField('par5'),
                    new IntegerField('par10'),
                    new IntegerField('par20'),
                    new IntegerField('kgv'),
                    new IntegerField('dividende'),
                    new IntegerField('dividende_p'),
                    new IntegerField('dsr'),
                    new StringField('branche'),
                    new StringField('bewertung'),
                    new StringField('kommentar')
                )
            );
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
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
                new FilterColumn($this->dataset, 'name', 'name', 'Name'),
                new FilterColumn($this->dataset, 'wkn', 'wkn', 'Wkn'),
                new FilterColumn($this->dataset, 'isin', 'isin', 'Isin'),
                new FilterColumn($this->dataset, 'f100id', 'f100id', 'F100id'),
                new FilterColumn($this->dataset, 'ba_id', 'ba_id', 'Ba Id'),
                new FilterColumn($this->dataset, 'ariva_id', 'ariva_id', 'Ariva Id'),
                new FilterColumn($this->dataset, 'boerse_id', 'boerse_id', 'Boerse Id'),
                new FilterColumn($this->dataset, 'kurs_dat', 'kurs_dat', 'Kurs Dat'),
                new FilterColumn($this->dataset, 'kurs', 'kurs', 'Kurs'),
                new FilterColumn($this->dataset, 'aenderung_p', 'aenderung_p', 'Aenderung P'),
                new FilterColumn($this->dataset, 'aenderung', 'aenderung', 'Aenderung'),
                new FilterColumn($this->dataset, 'par1', 'par1', 'Par1'),
                new FilterColumn($this->dataset, 'par3', 'par3', 'Par3'),
                new FilterColumn($this->dataset, 'par5', 'par5', 'Par5'),
                new FilterColumn($this->dataset, 'par10', 'par10', 'Par10'),
                new FilterColumn($this->dataset, 'par20', 'par20', 'Par20'),
                new FilterColumn($this->dataset, 'kgv', 'kgv', 'Kgv'),
                new FilterColumn($this->dataset, 'dividende', 'dividende', 'Dividende'),
                new FilterColumn($this->dataset, 'dividende_p', 'dividende_p', 'Dividende P'),
                new FilterColumn($this->dataset, 'dsr', 'dsr', 'Dsr'),
                new FilterColumn($this->dataset, 'branche', 'branche', 'Branche'),
                new FilterColumn($this->dataset, 'bewertung', 'bewertung', 'Bewertung'),
                new FilterColumn($this->dataset, 'kommentar', 'kommentar', 'Kommentar')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id'])
                ->addColumn($columns['name'])
                ->addColumn($columns['wkn'])
                ->addColumn($columns['isin'])
                ->addColumn($columns['f100id'])
                ->addColumn($columns['ba_id'])
                ->addColumn($columns['ariva_id'])
                ->addColumn($columns['boerse_id'])
                ->addColumn($columns['kurs_dat'])
                ->addColumn($columns['kurs'])
                ->addColumn($columns['aenderung_p'])
                ->addColumn($columns['aenderung'])
                ->addColumn($columns['par1'])
                ->addColumn($columns['par3'])
                ->addColumn($columns['par5'])
                ->addColumn($columns['par10'])
                ->addColumn($columns['par20'])
                ->addColumn($columns['kgv'])
                ->addColumn($columns['dividende'])
                ->addColumn($columns['dividende_p'])
                ->addColumn($columns['dsr'])
                ->addColumn($columns['branche'])
                ->addColumn($columns['bewertung'])
                ->addColumn($columns['kommentar']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('id');
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
            
            $main_editor = new TextEdit('name_edit');
            $main_editor->SetMaxLength(32);
            
            $filterBuilder->addColumn(
                $columns['name'],
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
            
            $main_editor = new TextEdit('wkn_edit');
            $main_editor->SetMaxLength(6);
            
            $filterBuilder->addColumn(
                $columns['wkn'],
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
            
            $main_editor = new TextEdit('isin_edit');
            $main_editor->SetMaxLength(32);
            
            $filterBuilder->addColumn(
                $columns['isin'],
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
            
            $main_editor = new TextEdit('f100id_edit');
            $main_editor->SetMaxLength(32);
            
            $filterBuilder->addColumn(
                $columns['f100id'],
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
            
            $main_editor = new TextEdit('ba_id_edit');
            $main_editor->SetMaxLength(32);
            
            $filterBuilder->addColumn(
                $columns['ba_id'],
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
            
            $main_editor = new TextEdit('ariva_id_edit');
            $main_editor->SetMaxLength(32);
            
            $filterBuilder->addColumn(
                $columns['ariva_id'],
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
            
            $main_editor = new TextEdit('boerse_id_edit');
            $main_editor->SetMaxLength(4);
            
            $filterBuilder->addColumn(
                $columns['boerse_id'],
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
            
            $main_editor = new TextEdit('kurs_dat_edit');
            $main_editor->SetMaxLength(32);
            
            $filterBuilder->addColumn(
                $columns['kurs_dat'],
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
            
            $main_editor = new TextEdit('kurs_edit');
            
            $filterBuilder->addColumn(
                $columns['kurs'],
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
            
            $main_editor = new TextEdit('aenderung_p_edit');
            
            $filterBuilder->addColumn(
                $columns['aenderung_p'],
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
            
            $main_editor = new TextEdit('aenderung_edit');
            
            $filterBuilder->addColumn(
                $columns['aenderung'],
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
            
            $main_editor = new TextEdit('par1_edit');
            
            $filterBuilder->addColumn(
                $columns['par1'],
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
            
            $main_editor = new TextEdit('par3_edit');
            
            $filterBuilder->addColumn(
                $columns['par3'],
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
            
            $main_editor = new TextEdit('par5_edit');
            
            $filterBuilder->addColumn(
                $columns['par5'],
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
            
            $main_editor = new TextEdit('par10_edit');
            
            $filterBuilder->addColumn(
                $columns['par10'],
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
            
            $main_editor = new TextEdit('par20_edit');
            
            $filterBuilder->addColumn(
                $columns['par20'],
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
            
            $main_editor = new TextEdit('kgv_edit');
            
            $filterBuilder->addColumn(
                $columns['kgv'],
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
            
            $main_editor = new TextEdit('dividende_edit');
            
            $filterBuilder->addColumn(
                $columns['dividende'],
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
            
            $main_editor = new TextEdit('dividende_p_edit');
            
            $filterBuilder->addColumn(
                $columns['dividende_p'],
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
            
            $main_editor = new TextEdit('dsr_edit');
            
            $filterBuilder->addColumn(
                $columns['dsr'],
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
            
            $main_editor = new TextEdit('branche_edit');
            $main_editor->SetMaxLength(32);
            
            $filterBuilder->addColumn(
                $columns['branche'],
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
            
            $main_editor = new MultiValueSelect('bewertung');
            $main_editor->addChoice('\'\'', '\'\'');
            $main_editor->addChoice('*', '*');
            $main_editor->addChoice('**', '**');
            $main_editor->addChoice('***', '***');
            $main_editor->addChoice('****', '****');
            $main_editor->addChoice('*****', '*****');
            
            $text_editor = new TextEdit('bewertung');
            
            $filterBuilder->addColumn(
                $columns['bewertung'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('kommentar');
            
            $filterBuilder->addColumn(
                $columns['kommentar'],
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
            if (GetCurrentUserPermissionsForPage('aktien.anteile')->HasViewGrant() && $withDetails)
            {
            //
            // View column for aktien_anteile detail
            //
            $column = new DetailColumn(array('id'), 'aktien.anteile', 'aktien_anteile_handler', $this->dataset, 'Anteile');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            if (GetCurrentUserPermissionsForPage('aktien.kurse')->HasViewGrant() && $withDetails)
            {
            //
            // View column for aktien_kurse detail
            //
            $column = new DetailColumn(array('id'), 'aktien.kurse', 'aktien_kurse_handler', $this->dataset, 'Kurse');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
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
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for wkn field
            //
            $column = new TextViewColumn('wkn', 'wkn', 'Wkn', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for isin field
            //
            $column = new TextViewColumn('isin', 'isin', 'Isin', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for f100id field
            //
            $column = new TextViewColumn('f100id', 'f100id', 'F100id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ba_id field
            //
            $column = new TextViewColumn('ba_id', 'ba_id', 'Ba Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ariva_id field
            //
            $column = new TextViewColumn('ariva_id', 'ariva_id', 'Ariva Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for boerse_id field
            //
            $column = new TextViewColumn('boerse_id', 'boerse_id', 'Boerse Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kurs_dat field
            //
            $column = new TextViewColumn('kurs_dat', 'kurs_dat', 'Kurs Dat', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kurs field
            //
            $column = new NumberViewColumn('kurs', 'kurs', 'Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for aenderung_p field
            //
            $column = new NumberViewColumn('aenderung_p', 'aenderung_p', 'Aenderung P', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for aenderung field
            //
            $column = new NumberViewColumn('aenderung', 'aenderung', 'Aenderung', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for par1 field
            //
            $column = new NumberViewColumn('par1', 'par1', 'Par1', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for par3 field
            //
            $column = new NumberViewColumn('par3', 'par3', 'Par3', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for par5 field
            //
            $column = new NumberViewColumn('par5', 'par5', 'Par5', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for par10 field
            //
            $column = new NumberViewColumn('par10', 'par10', 'Par10', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for par20 field
            //
            $column = new NumberViewColumn('par20', 'par20', 'Par20', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kgv field
            //
            $column = new NumberViewColumn('kgv', 'kgv', 'Kgv', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for dividende field
            //
            $column = new NumberViewColumn('dividende', 'dividende', 'Dividende', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for dividende_p field
            //
            $column = new NumberViewColumn('dividende_p', 'dividende_p', 'Dividende P', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for dsr field
            //
            $column = new NumberViewColumn('dsr', 'dsr', 'Dsr', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for branche field
            //
            $column = new TextViewColumn('branche', 'branche', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for bewertung field
            //
            $column = new TextViewColumn('bewertung', 'bewertung', 'Bewertung', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kommentar field
            //
            $column = new TextViewColumn('kommentar', 'kommentar', 'Kommentar', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
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
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for wkn field
            //
            $column = new TextViewColumn('wkn', 'wkn', 'Wkn', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for isin field
            //
            $column = new TextViewColumn('isin', 'isin', 'Isin', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for f100id field
            //
            $column = new TextViewColumn('f100id', 'f100id', 'F100id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ba_id field
            //
            $column = new TextViewColumn('ba_id', 'ba_id', 'Ba Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ariva_id field
            //
            $column = new TextViewColumn('ariva_id', 'ariva_id', 'Ariva Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for boerse_id field
            //
            $column = new TextViewColumn('boerse_id', 'boerse_id', 'Boerse Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kurs_dat field
            //
            $column = new TextViewColumn('kurs_dat', 'kurs_dat', 'Kurs Dat', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kurs field
            //
            $column = new NumberViewColumn('kurs', 'kurs', 'Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for aenderung_p field
            //
            $column = new NumberViewColumn('aenderung_p', 'aenderung_p', 'Aenderung P', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for aenderung field
            //
            $column = new NumberViewColumn('aenderung', 'aenderung', 'Aenderung', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for par1 field
            //
            $column = new NumberViewColumn('par1', 'par1', 'Par1', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for par3 field
            //
            $column = new NumberViewColumn('par3', 'par3', 'Par3', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for par5 field
            //
            $column = new NumberViewColumn('par5', 'par5', 'Par5', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for par10 field
            //
            $column = new NumberViewColumn('par10', 'par10', 'Par10', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for par20 field
            //
            $column = new NumberViewColumn('par20', 'par20', 'Par20', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kgv field
            //
            $column = new NumberViewColumn('kgv', 'kgv', 'Kgv', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for dividende field
            //
            $column = new NumberViewColumn('dividende', 'dividende', 'Dividende', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for dividende_p field
            //
            $column = new NumberViewColumn('dividende_p', 'dividende_p', 'Dividende P', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for dsr field
            //
            $column = new NumberViewColumn('dsr', 'dsr', 'Dsr', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for branche field
            //
            $column = new TextViewColumn('branche', 'branche', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for bewertung field
            //
            $column = new TextViewColumn('bewertung', 'bewertung', 'Bewertung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kommentar field
            //
            $column = new TextViewColumn('kommentar', 'kommentar', 'Kommentar', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for wkn field
            //
            $editor = new TextEdit('wkn_edit');
            $editor->SetMaxLength(6);
            $editColumn = new CustomEditColumn('Wkn', 'wkn', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for isin field
            //
            $editor = new TextEdit('isin_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Isin', 'isin', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for f100id field
            //
            $editor = new TextEdit('f100id_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('F100id', 'f100id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ba_id field
            //
            $editor = new TextEdit('ba_id_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Ba Id', 'ba_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ariva_id field
            //
            $editor = new TextEdit('ariva_id_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Ariva Id', 'ariva_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for boerse_id field
            //
            $editor = new TextEdit('boerse_id_edit');
            $editor->SetMaxLength(4);
            $editColumn = new CustomEditColumn('Boerse Id', 'boerse_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kurs_dat field
            //
            $editor = new TextEdit('kurs_dat_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Kurs Dat', 'kurs_dat', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kurs field
            //
            $editor = new TextEdit('kurs_edit');
            $editColumn = new CustomEditColumn('Kurs', 'kurs', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for aenderung_p field
            //
            $editor = new TextEdit('aenderung_p_edit');
            $editColumn = new CustomEditColumn('Aenderung P', 'aenderung_p', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for aenderung field
            //
            $editor = new TextEdit('aenderung_edit');
            $editColumn = new CustomEditColumn('Aenderung', 'aenderung', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for par1 field
            //
            $editor = new TextEdit('par1_edit');
            $editColumn = new CustomEditColumn('Par1', 'par1', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for par3 field
            //
            $editor = new TextEdit('par3_edit');
            $editColumn = new CustomEditColumn('Par3', 'par3', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for par5 field
            //
            $editor = new TextEdit('par5_edit');
            $editColumn = new CustomEditColumn('Par5', 'par5', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for par10 field
            //
            $editor = new TextEdit('par10_edit');
            $editColumn = new CustomEditColumn('Par10', 'par10', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for par20 field
            //
            $editor = new TextEdit('par20_edit');
            $editColumn = new CustomEditColumn('Par20', 'par20', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kgv field
            //
            $editor = new TextEdit('kgv_edit');
            $editColumn = new CustomEditColumn('Kgv', 'kgv', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for dividende field
            //
            $editor = new TextEdit('dividende_edit');
            $editColumn = new CustomEditColumn('Dividende', 'dividende', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for dividende_p field
            //
            $editor = new TextEdit('dividende_p_edit');
            $editColumn = new CustomEditColumn('Dividende P', 'dividende_p', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for dsr field
            //
            $editor = new TextEdit('dsr_edit');
            $editColumn = new CustomEditColumn('Dsr', 'dsr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for branche field
            //
            $editor = new TextEdit('branche_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Branche', 'branche', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for bewertung field
            //
            $editor = new CheckBoxGroup('bewertung_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->addChoice('\'\'', '\'\'');
            $editor->addChoice('*', '*');
            $editor->addChoice('**', '**');
            $editor->addChoice('***', '***');
            $editor->addChoice('****', '****');
            $editor->addChoice('*****', '*****');
            $editColumn = new CustomEditColumn('Bewertung', 'bewertung', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommentar field
            //
            $editor = new TextAreaEdit('kommentar_edit', 50, 8);
            $editColumn = new CustomEditColumn('Kommentar', 'kommentar', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for wkn field
            //
            $editor = new TextEdit('wkn_edit');
            $editor->SetMaxLength(6);
            $editColumn = new CustomEditColumn('Wkn', 'wkn', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for isin field
            //
            $editor = new TextEdit('isin_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Isin', 'isin', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for f100id field
            //
            $editor = new TextEdit('f100id_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('F100id', 'f100id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for ba_id field
            //
            $editor = new TextEdit('ba_id_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Ba Id', 'ba_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for ariva_id field
            //
            $editor = new TextEdit('ariva_id_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Ariva Id', 'ariva_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for boerse_id field
            //
            $editor = new TextEdit('boerse_id_edit');
            $editor->SetMaxLength(4);
            $editColumn = new CustomEditColumn('Boerse Id', 'boerse_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kurs_dat field
            //
            $editor = new TextEdit('kurs_dat_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Kurs Dat', 'kurs_dat', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kurs field
            //
            $editor = new TextEdit('kurs_edit');
            $editColumn = new CustomEditColumn('Kurs', 'kurs', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for aenderung_p field
            //
            $editor = new TextEdit('aenderung_p_edit');
            $editColumn = new CustomEditColumn('Aenderung P', 'aenderung_p', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for aenderung field
            //
            $editor = new TextEdit('aenderung_edit');
            $editColumn = new CustomEditColumn('Aenderung', 'aenderung', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for par1 field
            //
            $editor = new TextEdit('par1_edit');
            $editColumn = new CustomEditColumn('Par1', 'par1', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for par3 field
            //
            $editor = new TextEdit('par3_edit');
            $editColumn = new CustomEditColumn('Par3', 'par3', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for par5 field
            //
            $editor = new TextEdit('par5_edit');
            $editColumn = new CustomEditColumn('Par5', 'par5', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for par10 field
            //
            $editor = new TextEdit('par10_edit');
            $editColumn = new CustomEditColumn('Par10', 'par10', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for par20 field
            //
            $editor = new TextEdit('par20_edit');
            $editColumn = new CustomEditColumn('Par20', 'par20', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kgv field
            //
            $editor = new TextEdit('kgv_edit');
            $editColumn = new CustomEditColumn('Kgv', 'kgv', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for dividende field
            //
            $editor = new TextEdit('dividende_edit');
            $editColumn = new CustomEditColumn('Dividende', 'dividende', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for dividende_p field
            //
            $editor = new TextEdit('dividende_p_edit');
            $editColumn = new CustomEditColumn('Dividende P', 'dividende_p', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for dsr field
            //
            $editor = new TextEdit('dsr_edit');
            $editColumn = new CustomEditColumn('Dsr', 'dsr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for branche field
            //
            $editor = new TextEdit('branche_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Branche', 'branche', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for bewertung field
            //
            $editor = new CheckBoxGroup('bewertung_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->addChoice('\'\'', '\'\'');
            $editor->addChoice('*', '*');
            $editor->addChoice('**', '**');
            $editor->addChoice('***', '***');
            $editor->addChoice('****', '****');
            $editor->addChoice('*****', '*****');
            $editColumn = new CustomEditColumn('Bewertung', 'bewertung', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kommentar field
            //
            $editor = new TextAreaEdit('kommentar_edit', 50, 8);
            $editColumn = new CustomEditColumn('Kommentar', 'kommentar', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for wkn field
            //
            $editor = new TextEdit('wkn_edit');
            $editor->SetMaxLength(6);
            $editColumn = new CustomEditColumn('Wkn', 'wkn', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for isin field
            //
            $editor = new TextEdit('isin_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Isin', 'isin', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for f100id field
            //
            $editor = new TextEdit('f100id_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('F100id', 'f100id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ba_id field
            //
            $editor = new TextEdit('ba_id_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Ba Id', 'ba_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ariva_id field
            //
            $editor = new TextEdit('ariva_id_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Ariva Id', 'ariva_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for boerse_id field
            //
            $editor = new TextEdit('boerse_id_edit');
            $editor->SetMaxLength(4);
            $editColumn = new CustomEditColumn('Boerse Id', 'boerse_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kurs_dat field
            //
            $editor = new TextEdit('kurs_dat_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Kurs Dat', 'kurs_dat', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kurs field
            //
            $editor = new TextEdit('kurs_edit');
            $editColumn = new CustomEditColumn('Kurs', 'kurs', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for aenderung_p field
            //
            $editor = new TextEdit('aenderung_p_edit');
            $editColumn = new CustomEditColumn('Aenderung P', 'aenderung_p', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for aenderung field
            //
            $editor = new TextEdit('aenderung_edit');
            $editColumn = new CustomEditColumn('Aenderung', 'aenderung', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for par1 field
            //
            $editor = new TextEdit('par1_edit');
            $editColumn = new CustomEditColumn('Par1', 'par1', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for par3 field
            //
            $editor = new TextEdit('par3_edit');
            $editColumn = new CustomEditColumn('Par3', 'par3', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for par5 field
            //
            $editor = new TextEdit('par5_edit');
            $editColumn = new CustomEditColumn('Par5', 'par5', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for par10 field
            //
            $editor = new TextEdit('par10_edit');
            $editColumn = new CustomEditColumn('Par10', 'par10', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for par20 field
            //
            $editor = new TextEdit('par20_edit');
            $editColumn = new CustomEditColumn('Par20', 'par20', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kgv field
            //
            $editor = new TextEdit('kgv_edit');
            $editColumn = new CustomEditColumn('Kgv', 'kgv', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for dividende field
            //
            $editor = new TextEdit('dividende_edit');
            $editColumn = new CustomEditColumn('Dividende', 'dividende', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for dividende_p field
            //
            $editor = new TextEdit('dividende_p_edit');
            $editColumn = new CustomEditColumn('Dividende P', 'dividende_p', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for dsr field
            //
            $editor = new TextEdit('dsr_edit');
            $editColumn = new CustomEditColumn('Dsr', 'dsr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for branche field
            //
            $editor = new TextEdit('branche_edit');
            $editor->SetMaxLength(32);
            $editColumn = new CustomEditColumn('Branche', 'branche', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for bewertung field
            //
            $editor = new CheckBoxGroup('bewertung_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->addChoice('\'\'', '\'\'');
            $editor->addChoice('*', '*');
            $editor->addChoice('**', '**');
            $editor->addChoice('***', '***');
            $editor->addChoice('****', '****');
            $editor->addChoice('*****', '*****');
            $editColumn = new CustomEditColumn('Bewertung', 'bewertung', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommentar field
            //
            $editor = new TextAreaEdit('kommentar_edit', 50, 8);
            $editColumn = new CustomEditColumn('Kommentar', 'kommentar', $editor, $this->dataset);
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
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for wkn field
            //
            $column = new TextViewColumn('wkn', 'wkn', 'Wkn', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for isin field
            //
            $column = new TextViewColumn('isin', 'isin', 'Isin', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for f100id field
            //
            $column = new TextViewColumn('f100id', 'f100id', 'F100id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ba_id field
            //
            $column = new TextViewColumn('ba_id', 'ba_id', 'Ba Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ariva_id field
            //
            $column = new TextViewColumn('ariva_id', 'ariva_id', 'Ariva Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for boerse_id field
            //
            $column = new TextViewColumn('boerse_id', 'boerse_id', 'Boerse Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kurs_dat field
            //
            $column = new TextViewColumn('kurs_dat', 'kurs_dat', 'Kurs Dat', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kurs field
            //
            $column = new NumberViewColumn('kurs', 'kurs', 'Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for aenderung_p field
            //
            $column = new NumberViewColumn('aenderung_p', 'aenderung_p', 'Aenderung P', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for aenderung field
            //
            $column = new NumberViewColumn('aenderung', 'aenderung', 'Aenderung', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for par1 field
            //
            $column = new NumberViewColumn('par1', 'par1', 'Par1', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for par3 field
            //
            $column = new NumberViewColumn('par3', 'par3', 'Par3', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for par5 field
            //
            $column = new NumberViewColumn('par5', 'par5', 'Par5', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for par10 field
            //
            $column = new NumberViewColumn('par10', 'par10', 'Par10', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for par20 field
            //
            $column = new NumberViewColumn('par20', 'par20', 'Par20', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for kgv field
            //
            $column = new NumberViewColumn('kgv', 'kgv', 'Kgv', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for dividende field
            //
            $column = new NumberViewColumn('dividende', 'dividende', 'Dividende', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for dividende_p field
            //
            $column = new NumberViewColumn('dividende_p', 'dividende_p', 'Dividende P', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for dsr field
            //
            $column = new NumberViewColumn('dsr', 'dsr', 'Dsr', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for branche field
            //
            $column = new TextViewColumn('branche', 'branche', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for bewertung field
            //
            $column = new TextViewColumn('bewertung', 'bewertung', 'Bewertung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kommentar field
            //
            $column = new TextViewColumn('kommentar', 'kommentar', 'Kommentar', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
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
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for wkn field
            //
            $column = new TextViewColumn('wkn', 'wkn', 'Wkn', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for isin field
            //
            $column = new TextViewColumn('isin', 'isin', 'Isin', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for f100id field
            //
            $column = new TextViewColumn('f100id', 'f100id', 'F100id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ba_id field
            //
            $column = new TextViewColumn('ba_id', 'ba_id', 'Ba Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ariva_id field
            //
            $column = new TextViewColumn('ariva_id', 'ariva_id', 'Ariva Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for boerse_id field
            //
            $column = new TextViewColumn('boerse_id', 'boerse_id', 'Boerse Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kurs_dat field
            //
            $column = new TextViewColumn('kurs_dat', 'kurs_dat', 'Kurs Dat', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kurs field
            //
            $column = new NumberViewColumn('kurs', 'kurs', 'Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for aenderung_p field
            //
            $column = new NumberViewColumn('aenderung_p', 'aenderung_p', 'Aenderung P', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for aenderung field
            //
            $column = new NumberViewColumn('aenderung', 'aenderung', 'Aenderung', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for par1 field
            //
            $column = new NumberViewColumn('par1', 'par1', 'Par1', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for par3 field
            //
            $column = new NumberViewColumn('par3', 'par3', 'Par3', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for par5 field
            //
            $column = new NumberViewColumn('par5', 'par5', 'Par5', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for par10 field
            //
            $column = new NumberViewColumn('par10', 'par10', 'Par10', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for par20 field
            //
            $column = new NumberViewColumn('par20', 'par20', 'Par20', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for kgv field
            //
            $column = new NumberViewColumn('kgv', 'kgv', 'Kgv', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for dividende field
            //
            $column = new NumberViewColumn('dividende', 'dividende', 'Dividende', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for dividende_p field
            //
            $column = new NumberViewColumn('dividende_p', 'dividende_p', 'Dividende P', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for dsr field
            //
            $column = new NumberViewColumn('dsr', 'dsr', 'Dsr', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for branche field
            //
            $column = new TextViewColumn('branche', 'branche', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for bewertung field
            //
            $column = new TextViewColumn('bewertung', 'bewertung', 'Bewertung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kommentar field
            //
            $column = new TextViewColumn('kommentar', 'kommentar', 'Kommentar', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new NumberViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for wkn field
            //
            $column = new TextViewColumn('wkn', 'wkn', 'Wkn', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for isin field
            //
            $column = new TextViewColumn('isin', 'isin', 'Isin', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for f100id field
            //
            $column = new TextViewColumn('f100id', 'f100id', 'F100id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for ba_id field
            //
            $column = new TextViewColumn('ba_id', 'ba_id', 'Ba Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for ariva_id field
            //
            $column = new TextViewColumn('ariva_id', 'ariva_id', 'Ariva Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for boerse_id field
            //
            $column = new TextViewColumn('boerse_id', 'boerse_id', 'Boerse Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kurs_dat field
            //
            $column = new TextViewColumn('kurs_dat', 'kurs_dat', 'Kurs Dat', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kurs field
            //
            $column = new NumberViewColumn('kurs', 'kurs', 'Kurs', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for aenderung_p field
            //
            $column = new NumberViewColumn('aenderung_p', 'aenderung_p', 'Aenderung P', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for aenderung field
            //
            $column = new NumberViewColumn('aenderung', 'aenderung', 'Aenderung', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for par1 field
            //
            $column = new NumberViewColumn('par1', 'par1', 'Par1', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for par3 field
            //
            $column = new NumberViewColumn('par3', 'par3', 'Par3', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for par5 field
            //
            $column = new NumberViewColumn('par5', 'par5', 'Par5', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for par10 field
            //
            $column = new NumberViewColumn('par10', 'par10', 'Par10', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for par20 field
            //
            $column = new NumberViewColumn('par20', 'par20', 'Par20', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for kgv field
            //
            $column = new NumberViewColumn('kgv', 'kgv', 'Kgv', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for dividende field
            //
            $column = new NumberViewColumn('dividende', 'dividende', 'Dividende', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for dividende_p field
            //
            $column = new NumberViewColumn('dividende_p', 'dividende_p', 'Dividende P', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for dsr field
            //
            $column = new NumberViewColumn('dsr', 'dsr', 'Dsr', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for branche field
            //
            $column = new TextViewColumn('branche', 'branche', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for bewertung field
            //
            $column = new TextViewColumn('bewertung', 'bewertung', 'Bewertung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommentar field
            //
            $column = new TextViewColumn('kommentar', 'kommentar', 'Kommentar', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
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
    
        function CreateMasterDetailRecordGrid()
        {
            $result = new Grid($this, $this->dataset);
            
            $this->AddFieldColumns($result, false);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
            
            $result->SetAllowDeleteSelected(false);
            $result->SetShowUpdateLink(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $this->setupGridColumnGroup($result);
            $this->attachGridEventHandlers($result);
            
            return $result;
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
            $result->SetUseFixedHeader(false);
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
            $this->setDescription('Inline Description');
            $this->setDetailedDescription('Detailed Description');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $detailPage = new aktien_anteilePage('aktien_anteile', $this, array('aktienname'), array('id'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserPermissionsForPage('aktien.anteile'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('aktien.anteile'));
            $detailPage->SetHttpHandlerName('aktien_anteile_handler');
            $handler = new PageHTTPHandler('aktien_anteile_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $detailPage = new aktien_kursePage('aktien_kurse', $this, array('aktienid'), array('id'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserPermissionsForPage('aktien.kurse'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('aktien.kurse'));
            $detailPage->SetHttpHandlerName('aktien_kurse_handler');
            $handler = new PageHTTPHandler('aktien_kurse_handler', $detailPage);
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
        $Page = new aktienPage("aktien", "aktien.php", GetCurrentUserPermissionsForPage("aktien"), 'UTF-8');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("aktien"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
