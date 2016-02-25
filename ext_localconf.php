<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}
if (!isset($_EXTKEY)) {
    $_EXTKEY = 'typo3_roadmap';
}
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_typo3roadmap_majorversion=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_typo3roadmap_minorversion=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_typo3roadmap_phpversion=1
');

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_typo3roadmap_pi1.php', '_pi1', 'list_type', 1);
