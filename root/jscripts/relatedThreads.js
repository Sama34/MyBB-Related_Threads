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

var rTTimeout = 0;              

var relatedThreads = 
{
    init: function(subject)
    {
        if (rTTimer == 0)
        {
            rTTimer = $('#rTTimer').val();
        }
        if (rTMinLength == 0)
        {
            rTMinLength = $('#rTMinLength').val(); 
        }
        
        if (subject.length >= rTMinLength)
        {
            if (rTFid == 0)
            {
                rTFid = $('#rTFid').val(); 
            }
          
            clearTimeout(rTTimeout);
            rTTimeout = setTimeout("relatedThreads.refresh('" + subject + "', '" + rTFid + "')", rTTimer);
        }
        return false;
    },
  
  
	refresh: function(subject, fid)
	{
		if(use_xmlhttprequest == 1)
		{
			$.ajax(
			{
				url: 'xmlhttp.php?action=relatedThreads&subject=' + subject + '&fid=' + fid,
				type: 'get',
				complete: function (request, status)
				{
					relatedThreads.display(request);
				}
			});

			return false;
		}
		else
		{
			return true;
		}
    },


    display: function(request)
    {
        if (request.responseText != "")
        {
            $('#relatedThreadsRow').css("display", rTDisplay);
            $('#relatedThreads').html(request.responseText);         
        } 
        else 
        {
            $('#relatedThreadsRow').css("display", "none");
            $('#relatedThreads').html("");
        } 
    }
};
            