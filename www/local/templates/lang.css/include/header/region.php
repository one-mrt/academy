<?
use Lang\Region;

$ID = rand();
$currentRegion = Region::getCurrent(['select' => ["domain"]]);
$allRegion = Region::getAll(['select' => ['NAME', "domain"]]);
$fancyboxOptions = [
    "type" => "inline",
    "src" => "#LangRegionAll{$ID}",
];
?>

<script>
    sFRuk = () => {
        if(!LangRegionAll<?=$ID?>.classList.contains('d-n'))
        {
            document.body.classList.remove('of-h');
            document.body.classList.remove('md:pr-15');
        }
        else
        {
            document.body.classList.add('of-h');
            document.body.classList.add('md:pr-15');
        }

        LangRegionAll<?=$ID?>.classList.toggle('d-n');
    }
</script>

<button onclick="sFRuk()" class="d-n md:d-b m-0 p-0 bw-0 tdl-u tdt-1 tuo-2 bc-t h:c-py cr-p tn-all-3 lh-1" title="Открыть список филиалов" aria-label="Открыть список филиалов">Филиалы</button>


<div class="pn-f i-0 z-2 bc-ffffff08 bf-b5 d-n d-f ai-c jc-c" id="LangRegionAll<?=$ID?>">

    <div class="w-100 md:p-48 md:w-720px lg:w-960px xl:w-1015px bc-EDF0F9 lg:r-24 d-f fd-c rg-24 pn-r lg:bxs-py p-16 of-a h-100 lg:h-a">

        <button onclick="sFRuk()" class="bc-t bw-0 m-0 p-0 mt-24 mr-24 pn-a t-0 rt-0 d-f ai-c jc-c h:c-py tn-all-3">
            <svg width="18" height="18" viewBox="0 0 17 17" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                <line x1="0.354454" y1="16.436" x2="16.4369" y2="0.353568"/>
                <line x1="16.4355" y1="16.6547" x2="0.35308" y2="0.572272"/>
            </svg>
        </button>

        <div class="w-100 f-18 lg:f-20 ftw-6">Выберите филиал:</div>

        <div class="d-f fw-w mr--16 ml--16 rg-16">
            <?foreach($allRegion as $region):
                $modActive = "bc-w h:bc-py h:c-w";

                if($currentRegion->getDomain()->getValue() == $region->getDomain()->getValue())
                {
                    $modActive = "bc-py c-w";
                }
                ?>
                <div class="w-100 md:w-6/12 lg:w-4/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0 cr-p d-f ai-c jc-c">
                    <a href="<?=$region->getDomain()->getValue()?>" class="w-100 h-100 pt-16 pr-24 pb-16 pl-24 d-f ai-c cg-16 r-12 va-m <?=$modActive?> tn-all-3 md:f-12 xl:f-16">
                        <div class="fg-1"><?=$region->getName()?></div>
                        <div class="d-f">
                            <svg width="24" height="24" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="23" height="23" rx="5" fill="white"></rect>
                                <path d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z" fill="#DD445A"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            <?endforeach;?>
        </div>
    </div>
</div>