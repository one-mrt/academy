BX.IM.prototype.closeNotify = function()
{
	BX.onCustomEvent(window, 'onImNotifyWindowClose', []);
	if (this.messenger.popupMessenger != null)
		this.messenger.popupMessenger.destroy();

    BXIM.notifyOpen = false;
    BXIM.messenger.popupMessengerExtra?.classList.remove('rt-0')
};

BX.MessengerNotify.prototype.closeNotify = function()
{
    // console.info('close.notify')
    // if (!BX.MessengerCommon.isPage())
    // {
    // 	this.messengerNotifyButton.className = "bx-messenger-cl-notify-button";
    // }

    this.BXIM.messenger.popupMessengerExtra?.classList.remove('rt-0')

    if (this.notify.notificationApp && this.notify.notificationApp.hasVueInstance())
    {
        this.notify.notificationApp.destroyVueInstance();
    }

    this.BXIM.notifyOpen = false;
    this.popupNotifyItem = null;
    BX.unbindAll(this.popupNotifyButtonFilter);
    BX.unbindAll(this.popupNotifyItem);

    BXIM.notifyOpen = false
};