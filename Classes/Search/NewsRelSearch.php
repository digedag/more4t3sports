<?php

namespace Sys25\More4T3sports\Search;

use Sys25\RnBase\Domain\Model\BaseModel;
use Sys25\RnBase\Search\SearchBase;
use Sys25\RnBase\Utility\Misc;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2025 Rene Nitzsche
 *  Contact: rene@system25.de
 *  All rights reserved
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 ***************************************************************/

/**
 * Class to search match notes from database.
 *
 * @author Rene Nitzsche
 */
class NewsRelSearch extends SearchBase
{
    protected function getTableMappings()
    {
        $tableMapping = [];
        $tableMapping['NEWSREL'] = $this->getBaseTable();

        // Hook to append other tables
        Misc::callHook('more4t3sports', 'search_NewsRel_getTableMapping_hook', [
            'tableMapping' => &$tableMapping,
        ], $this);

        return $tableMapping;
    }

    protected function getBaseTable()
    {
        return 'tx_more4t3sports_newsrel';
    }

    protected function getBaseTableAlias()
    {
        return 'NEWSREL';
    }

    public function getWrapperClass()
    {
        return BaseModel::class;
    }

    protected function getJoins($tableAliases)
    {
        $join = [];

        // Hook to append other tables
        Misc::callHook('more4t3sports', 'search_NewsRel_getJoins_hook', [
            'join' => &$join,
            'tableAliases' => $tableAliases,
        ], $this);

        return $join;
    }
}
