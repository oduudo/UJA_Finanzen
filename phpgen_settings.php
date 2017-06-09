<?php

//  define('SHOW_VARIABLES', 1);
//  define('DEBUG_LEVEL', 1);

//  error_reporting(E_ALL ^ E_NOTICE);
//  ini_set('display_errors', 'On');

set_include_path('.' . PATH_SEPARATOR . get_include_path());


include_once dirname(__FILE__) . '/' . 'components/utils/system_utils.php';

//  SystemUtils::DisableMagicQuotesRuntime();

SystemUtils::SetTimeZoneIfNeed('Europe/Belgrade');

function GetGlobalConnectionOptions()
{
    return array(
  'server' => 'localhost',
  'port' => '3306',
  'username' => 'uja',
  'password' => 'hugo1ujasql',
  'database' => 'uja_finanzen'
);
}

function HasAdminPage()
{
    return false;
}

function GetPageInfos()
{
    $result = array();
    $result[] = array('caption' => 'Aktien', 'short_caption' => 'Aktien', 'filename' => 'aktien.php', 'name' => 'aktien');
    $result[] = array('caption' => 'Anteile', 'short_caption' => 'Anteile', 'filename' => 'anteile.php', 'name' => 'anteile');
    $result[] = array('caption' => 'Depots', 'short_caption' => 'Depots', 'filename' => 'depots.php', 'name' => 'depots');
    $result[] = array('caption' => 'Kurse', 'short_caption' => 'Kurse', 'filename' => 'kurse.php', 'name' => 'kurse');
    return $result;
}

function GetPagesHeader()
{
    return
    '';
}

function GetPagesFooter()
{
    return
        ''; 
    }

function ApplyCommonPageSettings(Page $page, Grid $grid)
{
    $page->SetShowUserAuthBar(false);
    $grid->BeforeUpdateRecord->AddListener('Global_BeforeUpdateHandler');
    $grid->BeforeDeleteRecord->AddListener('Global_BeforeDeleteHandler');
    $grid->BeforeInsertRecord->AddListener('Global_BeforeInsertHandler');
}

/*
  Default code page: 1252
*/
function GetAnsiEncoding() { return 'windows-1252'; }

function Global_BeforeUpdateHandler($page, $rowData, &$cancel, &$message, $tableName)
{

}

function Global_BeforeDeleteHandler($page, $rowData, &$cancel, &$message, $tableName)
{

}

function Global_BeforeInsertHandler($page, $rowData, &$cancel, &$message, $tableName)
{

}

function GetDefaultDateFormat()
{
    return 'dd.mm.YY';
}

function GetFirstDayOfWeek()
{
    return 1;
}

function GetEnableLessFilesRunTimeCompilation()
{
    return false;
}



?>