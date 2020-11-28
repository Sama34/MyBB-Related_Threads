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
 * Plugin Installator Class
 * 
 */
class relatedThreadsInstaller
{

    public static function install()
    {
    }

    public static function uninstall()
    {
		global $db, $PL, $cache;

		$PL or require_once PLUGINLIBRARY;

		// Delete settings
		$PL->settings_delete('relatedThreads');

		// Delete templates
		$PL->templates_delete('relatedThreads');

		// Delete version from cache
		$plugins = (array)$cache->read('ougc_plugins');

		if(isset($plugins['relatedThreads']))
		{
			unset($plugins['relatedThreads']);
		}

		if(!empty($plugins))
		{
			$cache->update('relatedThreads', $plugins);
		}
		else
		{
			$PL->cache_delete('relatedThreads');
		}
    }

}
