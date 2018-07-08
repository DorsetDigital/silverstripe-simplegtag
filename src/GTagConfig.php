<?php

namespace	DorsetDigital\SimpleGTag;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldList;

/**
 * Description of GTagConfig
 *
 * @author tim
 */
class	GTagConfig	extends DataExtension {
	
     private static $db = [
        'GTMID' => 'Varchar(255)'
    ];
    
    public function updateCMSFields(FieldList $fields) {        
        $fields->addFieldToTab('Root.GoogleTagManager', TextField::create('GTMID')->setDescription('eg. GTM-ABC123'));
        return $fields;        
    }
	
}
