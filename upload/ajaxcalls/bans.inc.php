<?php

/*

	GStats++: GHost++ Web-Based Statistics
    Copyright (C) 2009 Marc André 'Manhim' Audet

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

class AjaxcallsBans extends Ajaxcalls implements iAjaxcalls
{
	function run()
	{
		if (($rResult = $this->_dbs->query('
			SELECT id, name, server, date, reason
			FROM `dbs_bans`
			' . $this->_sWhere . '
			' . $this->_sOrder . '
			' . $this->_sLimit)) === false)
			die ('Query error: ' . $this->_dbs->error());
			
		if (($rResultTotal = $this->_dbs->query('
			SELECT id
			FROM `dbs_bans`')) === false)
			die ('Query error: ' . $this->_dbs->error());
			
		$iTotal = $this->_dbs->num_rows($rResultTotal);
		
		if ($this->_sWhere != '')
		{
			if (($rResultFilterTotal = $this->_dbs->query('
				SELECT id
				FROM `dbs_bans`
				' . $this->_sWhere)) === false)
				die ('Query error: ' . $this->_dbs->error());
			
			$iFilteredTotal = $this->_dbs->num_rows($rResultFilterTotal);
		}
		else
		{
			$iFilteredTotal = $iTotal;
		}
		
		$out = '{';
		$out .= '"iTotalRecords": ' . $iTotal . ', ';
		$out .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
		$out .= '"aaData": [ ';
		
		while ($row = $this->_dbs->fetch_array($rResult))
		{
			$out .= '[';
			$out .= '\'' . $this->purify($row['name']) . '\',';
			$out .= '\'4\',';
			$out .= '\'' . $this->purify($row['server']) . '\',';
			$out .= '\'' . $row['date'] . '\',';
			$out .= '\'' . $this->purify($row['reason']) . '\'';
			$out .= '],';
		}
		
		echo substr_replace($out, '] }', -1);
	}
}

?>