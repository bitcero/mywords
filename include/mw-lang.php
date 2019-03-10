<?php
/**
 * MyWords for XOOPS
 *
 * Copyright © 2017 Eduardo Cortés http://www.eduardocortes.mx
 * -------------------------------------------------------------
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * -------------------------------------------------------------
 * @copyright    Eduardo Cortés (http://www.eduardocortes.mx)
 * @license      GNU GPL 2
 * @package      mywords
 * @author       Eduardo Cortés (AKA bitcero)    <i.bitcero@gmail.com>
 * @url          http://www.eduardocortes.mx
 */
ob_start();
?>
var mwLang = {
    collecting: '<?php _e('Collecting information...', 'mywords'); ?>',
    noData: '<?php _e('There are not data to import!', 'mywords'); ?>',
    noArticles: '<?php _e('There are not articles to import!', 'mywords'); ?>',
    categoriesDone: '<?php _e('Categories importing done! Starting articles importing...', 'mywords'); ?>',
    importingCategory: '<?php _e('Importing category %s...', 'mywords'); ?>',
    articlesDone: '<?php _e('Articles importing done!', 'mywords'); ?>',
    importingArticle: '<?php _e('Importing article %s...', 'mywords'); ?>',
    confirmReportDeletion: '<?php _e('Do you really want to delete specified report?', 'mywords'); ?>',
    selectReport: '<?php _e('Select at least one report before to perform this action', 'mywords'); ?>',
};
<?php
$language = ob_get_clean();

RMTemplate::getInstance()->add_inline_script($language, 1);
