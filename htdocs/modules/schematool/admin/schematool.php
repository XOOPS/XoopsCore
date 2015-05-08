<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Richard Griffith <richard@geekwright.com>
 */

require __DIR__ . '/admin_header.php';

/* --------------------------------------------------------------- */

use Xmf\Debug;
use Xoops\Core\Database\Schema\ExportVisitor;
use Xoops\Core\Database\Schema\ImportSchema;
use Xoops\Core\Database\Schema\RemovePrefixes;
use Xoops\Core\Yaml;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Synchronizer\SingleDatabaseSynchronizer;

// from $_POST we use keys: op, mod_dirname
$clean_input = XoopsFilterInput::gather(
    'post',
    array(
        array('op','string', 'selectmodule', true),
        array('mod_dirname','string', '', true),
    )
);

$op = $clean_input['op'];
$mod_dirname = $clean_input['mod_dirname'];
if ($op != 'showschema' || empty($mod_dirname)) {
    $op = 'selectmodule';
}

//echo '<h2>' . _MI_SCHEMATOOL_NAME . '</h2>';
$indexAdmin = new \Xoops\Module\Admin();
$indexAdmin->displayNavigation('schematool.php');

if ($op == 'showschema') {
    $helper = $xoops->getModuleHelper($mod_dirname);
    $mod_to_use = $helper->getModule();
    $mod_to_use->loadInfo($mod_dirname, false);
    $mod_ver = $mod_to_use->modinfo;
    $table_list = array();
    if (isset($mod_ver['tables'])) {
        $table_list = $mod_ver['tables'];
    }
    //Debug::dump($table_list);
    if (empty($table_list)) {
        echo $xoops->alert(
            'warning',
            sprintf(_MI_SCHEMATOOL_MSG_NO_TABLES, $mod_dirname),
            _MI_SCHEMATOOL_TITLE_NO_TABLES
        );
    } else {

        // get a schema manager
        $schemaManager = $xoops->db()->getSchemaManager();

        // create schema from the current database
        $schema = $schemaManager->createSchema();

        // invoke our RemovePrefixes visitor with list of core tables
        $visitor = new RemovePrefixes;
        $visitor->setTableFilter($table_list);
        $schema->visit($visitor);

        // Get the schema we built with the RemovePrefixes visitor.
        // Should be just core tables with no prefix
        $newSchema = $visitor->getNewSchema();

        // Invoke an ExportVisitor that will build a clean array version
        // of our schema, so we can serialize it.
        $export = new ExportVisitor;
        $newSchema->visit($export);

        echo '<h2>' . _MI_SCHEMATOOL_EXPORT_SCHEMA . '</h2>';
        $yamldump = Yaml::dump($export->getSchemaArray(), 5);
        //echo '<div contenteditable><pre>' . $yamldump . '</pre></div>';
        $schemadump = <<<EOT1
<section>
  <div id="container">
    <div contenteditable><pre>{$yamldump}</pre></div>
    <input type="text" value="schema.yml" placeholder="schema.yml">
    <button onclick="downloadFile()">Create file</button> <output></output>
  </div>
</section>
EOT1;

        $script = <<<EOT2
<script>
var container = document.querySelector('#container');
var typer = container.querySelector('[contenteditable]');
var output = container.querySelector('output');

const MIME_TYPE = 'text/plain';

// Rockstars use event delegation!
document.body.addEventListener('dragstart', function(e) {
  var a = e.target;
  if (a.classList.contains('dragout')) {
    e.dataTransfer.setData('DownloadURL', a.dataset.downloadurl);
  }
}, false);

document.body.addEventListener('dragend', function(e) {
  var a = e.target;
  if (a.classList.contains('dragout')) {
    cleanUp(a);
  }
}, false);

document.addEventListener('keydown', function(e) {
  if (e.keyCode == 27) {  // Esc
    document.querySelector('details').open = false;
  } else if (e.shiftKey && e.keyCode == 191) { // shift + ?
    document.querySelector('details').open = true;
  }
}, false);

var cleanUp = function(a) {
  a.textContent = 'Downloaded';
  a.dataset.disabled = true;

  // Need a small delay for the revokeObjectURL to work properly.
  setTimeout(function() {
    window.URL.revokeObjectURL(a.href);
  }, 1500);
};

var downloadFile = function() {
  window.URL = window.webkitURL || window.URL;

  var prevLink = output.querySelector('a');
  if (prevLink) {
    window.URL.revokeObjectURL(prevLink.href);
    output.innerHTML = '';
  }

  var bb = new Blob([typer.textContent], {type: MIME_TYPE});

  var a = document.createElement('a');
  a.download = container.querySelector('input[type="text"]').value;
  a.href = window.URL.createObjectURL(bb);
  a.textContent = 'Download ready';

  a.dataset.downloadurl = [MIME_TYPE, a.download, a.href].join(':');
  a.draggable = false; // Don't really need, but good practice.
  //a.classList.add('dragout');

  output.appendChild(a);

  a.onclick = function(e) {
    if ('disabled' in this.dataset) {
      return false;
    }

    cleanUp(this);
  };
};
</script>
EOT2;

        echo $schemadump;
        echo $script;
    }
    $op = 'selectmodule';
}

if ($op == 'selectmodule') {
    $activeModules = $xoops->getActiveModules();
    natcasesort($activeModules);

    $form = new Xoops\Form\ThemeForm('', 'schema_form', '', 'post', true, 'inline');

    $ele = new Xoops\Form\Select(_MI_SCHEMATOOL_FORM_CAPTION, 'mod_dirname', $mod_dirname);
    foreach ($activeModules as $dirname) {
        $mHelper = $xoops->getModuleHelper($dirname);
        if (is_object($mHelper)) {
            $ele->addOption($dirname, $mHelper->getModule()->getVar('name'));
        }
    }
    $form->addElement($ele);
    $form->addElement(new Xoops\Form\Hidden('op', 'showschema'));
    $form->addElement(new Xoops\Form\Button('', 'button', XoopsLocale::A_SUBMIT, 'submit'));
    echo $form->render();
}



/*
    $importer = new ImportSchema;
    $importSchema = $importer->importSchemaArray(Yaml::load($yamldump));

    echo '<h2>Original Schema</h2>';
    Debug::dump($schema);

    echo '<h2>Imported Schema</h2>';
    Debug::dump($importSchema);

    $synchronizer = new SingleDatabaseSynchronizer($xoops->db());
    $to_sql = $synchronizer->getUpdateSchema($importSchema, true);
    //$to_sql = $synchronizer->getCreateSchema($importSchema);

    //$diff=Comparator::compareSchemas($schema, $importSchema);
    echo '<h2>compareSchemas(original,imported)</h2>';
    Kint::dump($to_sql);

    echo '<h1>End of Test</h1>';
*/

$xoops->footer();
