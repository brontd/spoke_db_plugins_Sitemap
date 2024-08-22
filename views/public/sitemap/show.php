<?php
//$this->navigation()
//     ->sitemap()
//     ->setFormatOutput(true); // default is false

// other possible methods:
// ->setUseXmlDeclaration(false); // default is true
// ->setServerUrl('http://my.otherhost.com');
// default is to detect automatically

$nav = new Omeka_Navigation;
$nav->loadAsOption(Omeka_Navigation::PUBLIC_NAVIGATION_MAIN_OPTION_NAME);
$nav->addPagesFromFilter(Omeka_Navigation::PUBLIC_NAVIGATION_MAIN_FILTER_NAME);

$collections = get_db()->getTable('Collection')->findAll();

// changes made in order to utilize ARK URLs, Eric C. Weig 2019-05-01

foreach($collections as $collection) {
    $ark = metadata($collection,array('Dublin Core','Identifier'));
    $page = new Omeka_Navigation_Page_Mvc(array(
//        'label'      => metadata($collection,array('Dublin Core','Title')),
//        'route'      => 'id',
//        'action'     => 'show',
//        'controller' => 'collections',
        'controller' => $ark,
        'params'     => array('controller' => $collection->controller)
    ));

    $nav->addPage($page);

}

if(plugin_is_active('ExhibitBuilder')){
    $exhibits = get_db()->getTable('Exhibit')->findAll();
    foreach($exhibits as $exhibit) {
        $page = new Omeka_Navigation_Page_Mvc(array(
            'route'      => 'exhibitSimple',
            'action'     => 'show',
            'controller' => 'exhibits',
            'params'     => array('slug' => $exhibit->slug)
        ));
        $nav->addPage($page);
    }
}

// changes made in order to utilize ARK URLs, Eric C. Weig 2019-05-01

$nav = $this->navigation()->sitemap($nav);
$nav = str_replace('%2F','/',$nav);
$nav = str_replace('%3A',':',$nav);
echo $nav;
//echo $this->navigation()->sitemap($nav);
?>
