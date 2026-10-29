<?

use Bitrix\Main\Loader;
use Bitrix\Iblock\Elements\ElementLangTextTable;

Loader::includeModule('iblock');

// $contact = ElementLangTextTable::getList([
//     'select' => [
//         "NAME",
//         'PREVIEW_PICTURE',
//         'city' => 'addressLocality.VALUE',
//         'street' => 'streetAddress.VALUE',
//         "em" => "email.VALUE",
//         "work" => "openingHours.VALUE",
//         "phone" => "telephone.VALUE",
//         "cite" => "url.VALUE",
//         "lati" => "geo.VALUE",
//         "longi" => "geo.DESCRIPTION",
//     ],
//     'filter' => ["ID" => 769],
//     'cache' => [
//         'ttl' => '36000000',
//         'cache_joins' => true,
//     ]
// ])->fetchAll();

if(!empty($contact))
{
    $contactSchemaOrg = [
        '@context' => 'http://schema.org',
        '@type' => 'LocalBusiness',
        'additionalType' => 'AutoRental',
        'address' => [
            '@type' => 'PostalAddress',
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => '',
            'longitude' => '',
        ],
    ];

    foreach($contact as $kCon => $iCon)
    {
        $contactSchemaOrg['name'] = $iCon['NAME'];
        $contactSchemaOrg['image'] = CFile::GetPath($iCon['PREVIEW_PICTURE']);
        $contactSchemaOrg['address']['addressLocality'] = $iCon['city'];
        $contactSchemaOrg['address']['streetAddress'] = $iCon['street'];
        $contactSchemaOrg['email'] = $iCon['em'];
        $contactSchemaOrg['url'] = $iCon['cite'];
        $contactSchemaOrg['openingHours'][] = $iCon['work'];
        $contactSchemaOrg['telephone'][] = $iCon['phone'];
        $contactSchemaOrg['geo']['latitude'] = $iCon['lati'];
        $contactSchemaOrg['geo']['longitude'] = $iCon['longi'];

    }

    echo '<script type="application/ld+json">'.json_encode($contactSchemaOrg).'</script>';
}
?>