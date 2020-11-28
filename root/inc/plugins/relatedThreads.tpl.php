<?php
/**
 * This file is part of Related Threads plugin for MyBB.
 * Copyright (C) Lukasz Tkacz <lukasamd@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */ 

/**
 * Disallow direct access to this file for security reasons
 * 
 */
if (!defined("IN_MYBB")) exit;

/**
 * Plugin Activator Class
 * 
 */
class relatedThreadsActivator
{
	static public $plugin_info;

    public static function activate()
    {
		global $PL, $lang, $mybb, $db, $lang;

		$PL or require_once PLUGINLIBRARY;

        $lang->load('relatedThreads');

        $options = "select
0={$lang->relatedThreadsTimeOptionNone}
h={$lang->relatedThreadsTimeOptionHours}
d={$lang->relatedThreadsTimeOptionDays}
w={$lang->relatedThreadsTimeOptionWeeks}
m={$lang->relatedThreadsTimeOptionMonths}
y={$lang->relatedThreadsTimeOptionYears}";

        $options2 = "select
firstPost={$lang->relatedThreadsTimeOptionFirstPost}
lastPost={$lang->relatedThreadsTimeOptionLastPost}";

		$PL->settings('relatedThreads', $lang->relatedThreadsName, $lang->relatedThreadsGroupDesc, array(
			'CodeStatus'				=> array(
			   'title'			=> $lang->relatedThreadsCodeStatus,
			   'description'	=> $lang->relatedThreadsCodeStatusDesc,
			   'optionscode'	=> 'onoff',
			   'value'			=> 0
			),
			'Length'				=> array(
			   'title'			=> $lang->relatedThreadsLength,
			   'description'	=> $lang->relatedThreadsLengthDesc,
			   'optionscode'	=> 'text',
			   'value'			=> 4
			),
			'Limit'				=> array(
			   'title'			=> $lang->relatedThreadsLimit,
			   'description'	=> $lang->relatedThreadsLimitDesc,
			   'optionscode'	=> 'text',
			   'value'			=> 5
			),
			'LinkLastPost'				=> array(
			   'title'			=> $lang->relatedThreadsLinkLastPost,
			   'description'	=> $lang->relatedThreadsLinkLastPostDesc,
			   'optionscode'	=> 'onoff',
			   'value'			=> 0
			),
			'NewWindow'				=> array(
			   'title'			=> $lang->relatedThreadsNewWindow,
			   'description'	=> $lang->relatedThreadsNewWindowDesc,
			   'optionscode'	=> 'onoff',
			   'value'			=> 1
			),
			'Fulltext'				=> array(
			   'title'			=> $lang->relatedThreadsFulltext,
			   'description'	=> $lang->relatedThreadsFulltextDesc,
			   'optionscode'	=> 'onoff',
			   'value'			=> ($mybb->settings['searchtype'] == 'fulltext') ? 1 : 0
			),
			'ForumOnly'				=> array(
			   'title'			=> $lang->relatedThreadsForumOnly,
			   'description'	=> $lang->relatedThreadsForumOnlyDesc,
			   'optionscode'	=> 'onoff',
			   'value'			=> 0
			),
			'ShowPrefixes'				=> array(
			   'title'			=> $lang->relatedThreadsShowPrefixes,
			   'description'	=> $lang->relatedThreadsShowPrefixesDesc,
			   'optionscode'	=> 'onoff',
			   'value'			=> 1
			),
			'TimeLimitSelect'				=> array(
			   'title'			=> $lang->relatedThreadsTimeLimitSelect,
			   'description'	=> $lang->relatedThreadsTimeLimitSelectDesc,
			   'optionscode'	=> $options,
			   'value'			=> 0
			),
			'TimeLimit'				=> array(
			   'title'			=> $lang->relatedThreadsTimeLimit,
			   'description'	=> $lang->relatedThreadsTimeLimitDesc,
			   'optionscode'	=> 'text',
			   'value'			=> 0
			),
			'TimeLimitMethod'				=> array(
			   'title'			=> $lang->relatedThreadsTimeLimitMethod,
			   'description'	=> $lang->relatedThreadsTimeLimitMethodDesc,
			   'optionscode'	=> $options2,
			   'value'			=> 'firstPost'
			),
			'ForumGet'				=> array(
			   'title'			=> $lang->relatedThreadsForumGet,
			   'description'	=> $lang->relatedThreadsForumGetDesc,
			   'optionscode'	=> 'onoff',
			   'value'			=> 0
			),
			'Exceptions'				=> array(
			   'title'			=> $lang->relatedThreadsExceptions,
			   'description'	=> $lang->relatedThreadsExceptionsDesc,
			   'optionscode'	=> 'forumselect',
			   'value'			=> ''
			),
			'BadWords'				=> array(
			   'title'			=> $lang->relatedThreadsBadWords,
			   'description'	=> $lang->relatedThreadsBadWordsDesc,
			   'optionscode'	=> 'text',
			   'value'			=> ''
			),
			'Timer'				=> array(
			   'title'			=> $lang->relatedThreadsTimer,
			   'description'	=> $lang->relatedThreadsTimerDesc,
			   'optionscode'	=> 'text',
			   'value'			=> 1000
			),
		));

		$PL->templates('relatedThreads', $lang->relatedThreadsName, array(
			'code'	=> '<strong>' . $lang->relatedThreadsName . '</strong>',
			'javascript'	=> '
<script type="text/javascript" src="{$mybb->settings[\'bburl\']}/jscripts/relatedThreads.js"></script>            
<script type="text/javascript">
<!--
	var rTTimer = "{$mybb->settings[\'relatedThreads_Timer\']}";
	var rTMinLength = "{$mybb->settings[\'relatedThreads_Length\']}";
	var rTFid = "{$forum[\'fid\']}";
	var rTDisplay = "table-row";
// -->
</script>',
			'withForum'	=> '{$thread[\'threadprefix\']}<a {$linkTarget}href="{$thread[\'link\']}">{$thread[\'subject\']}</a> (<a {$linkTarget}href="{$forum[\'link\']}">{$forum[\'name\']}</a>)',
			'withoutForum'	=> '{$thread[\'threadprefix\']}<a {$linkTarget}href="{$thread[\'link\']}">{$thread[\'subject\']}</a>',
			'list'	=> '<ul class="relatedThreadsList">{$relatedThreads[\'list\']}</ul>',
			'listElement'	=> '<li>{$relatedThreads[\'element\']}</li>',
			'row'	=> '<tr id="relatedThreadsRow" style="display:none;"><td class="trow2" valign="top"><strong>{$lang->relatedThreadsTitle}</strong></td><td class="trow2" id="relatedThreads">{$relatedThreads}</td></tr>',
		));

		self::deactivate();

        find_replace_templatesets("newthread", '#' . preg_quote('</head>') . '#', "{\$relatedThreadsJavaScript}</head>");
        find_replace_templatesets("newthread", '#' . preg_quote('{$posticons}') . '#', '{$relatedThreadsRow}{$posticons}');
        find_replace_templatesets("newthread", '#' . preg_quote('name="subject"') . '#', 'name="subject" onkeyup="return relatedThreads.init(this.value);"');

		// Insert/update version into cache
		$plugins = $mybb->cache->read('ougc_plugins');
		if(!$plugins)
		{
			$plugins = array();
		}

		self::$plugin_info = relatedThreads_info();

		if(!isset($plugins['relatedThreads']))
		{
			$plugins['relatedThreads'] = self::$plugin_info['versioncode'];
		}

		/*~*~* RUN UPDATES START *~*~*/

		/*~*~* RUN UPDATES END *~*~*/

		$plugins['relatedThreads'] = self::$plugin_info['versioncode'];
		$mybb->cache->update('ougc_plugins', $plugins);
    }

    public static function deactivate()
    {
        include MYBB_ROOT . '/inc/adminfunctions_templates.php';
        find_replace_templatesets("newthread", '#' . preg_quote('{$relatedThreadsJavaScript}') . '#', "", 0);
        find_replace_templatesets("newthread", '#' . preg_quote('{$relatedThreadsRow}') . '#', "", 0);
        find_replace_templatesets("newthread", '#' . preg_quote(' onkeyup="return relatedThreads.init(this.value);"') . '#', "", 0);      
    }

}
