<?
namespace Lang\Academy;

use Bitrix\Main\ORM\Event;
use Bitrix\Main\UserTable;
use Bitrix\Main\ORM\EventResult;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Iblock\Elements\ElementLangReviewTable as LangReview;

class ReviewRating
{
    private static function calcRating($object)
    {
        $score = 0;
        $count = 0;
        $items = LangReview::query()
            ->setSelect(['s' => 'score.VALUE'])
            ->where('ur.VALUE', $object->getUr()->getValue())
            ->exec()
            ->fetchCollection();

        if(!$items->isEmpty()) {
            foreach($items as $item) {
                $score += intval($item->getScore()->getValue());
                $count++;
            }
        }

        $score += intval($object->getScore()->getValue());
        $count++;

        return ['score' => sprintf('%.2f', $score / $count), 'count' => $count];
    }

    public static function onAfterAddReview(Event $event): EventResult
    {
        $result = new EventResult();
        $object = $event->getParameter('object');

        if(!empty($object?->getUr()?->getValue())) {
            $score = self::calcRating($object);

            if(!empty($score['score'])) {
                $user = new \CUser;
                $ok = $user->Update($object->getUr()->getValue(), [
                    'UF_SCORE' => $score['score'],
                    'UF_COUNT' => $score['count'],
                ]);
            }
        }

        return $result;
    }
}