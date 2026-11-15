<?php

namespace Lang\Academy\Agent;

final class OfferStatus
{
	const string LANG_ORDER_OFFER = "LangOrderOffer";

	public static function run(): ?string
	{
		$instance = new static();
		$result = $instance->runInternal();

		if ($result === false) {
			return null;
		}

		return __METHOD__ . '();';
	}

	private function runInternal(): bool
	{
		# process
		// Получить все заявки в статусе предвыбранное предложение (sel=Y)
		// и датой изменения (TIMESTAMP_X) большую на 12 часов
		// Если есть такие , то удаляем предложение и рассылаем уведомления

		return true;
	}
}