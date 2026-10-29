<?
$schemaData = [
    '@context' => 'http://schema.org',
    '@type' => 'Article',
    'headline' => $element->getName(),
    'image' => $previewPicture,
    'description' => strip_tags($element->getDetailText()),
    'datePublished' => date(\DateTime::ATOM, strtotime($element->getDateCreate())),
    'dateModified' => date(\DateTime::ATOM, strtotime($element->getTimestampX())),
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => $detailPageUrl,
    ],
    'author' => [
        [
            "@type" => "Organization",
            "name" => $region->getName(),
            "url" => $region->getDomain()->getValue(),
        ]
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => $region->getName(),
        'url' => $region->getDomain()->getValue(),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => $autor['PREVIEW_PICTURE'],
        ]
    ],
];

echo '<script type="application/ld+json">'.json_encode($schemaData).'</script>';
?>