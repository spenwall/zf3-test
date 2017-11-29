<?php

namespace Database;

use Weddingstar\Db\Table;
/**
 * Class representing the addon_products table model
 * 
 * @version SVN: $Id: Addons.php 48975 2016-01-13 15:01:06Z twaldner $
 */
class AddonProducts extends Table\Abstract
{
    const TABLE_NAME = 'addon_products';
    
    // column names
    const COL_ID                = 'id';
    const COL_ADDON_ID          = 'addon_id';
    const COL_ADDON_PRODUCTCODE = 'addon_productcode';
    const COL_PRIORITY          = 'priority';

    /**
     * Gets the addons for the add_on id
     * 
     * @param int $addonId - addon id
     * 
     * @return type
     */
    public function getAddons($addonId)
    {
        $addons = $this->findRowsForColumnValue(self::COL_ADDON_ID, $addonId);
        return $addons;
    }
}
