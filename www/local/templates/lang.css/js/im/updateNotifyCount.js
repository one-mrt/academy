BX.MessengerNotify.prototype.updateNotifyCount = function(send) {
    send = send != false;

    let count = this.notifyCount

    let notifyCountLabel = ''

    if (count > 99) notifyCountLabel = '99+';
    else if (count > 0) notifyCountLabel = count;

    if (this.panelButtonNotifyCount) {
        this.panelButtonNotifyCount.innerHTML = notifyCountLabel;
        // this.adjustPosition({"resize": true, "timeout": 500});
    }

    if (this.messengerNotifyButtonCount) {
        this.messengerNotifyButtonCount.innerHTML = (this.notifyCount > 0) ? notifyCountLabel : this.notifyCount;
    }

    // if (BX.MessengerCommon.isPage()) {
    //     BX.MessengerWindow.setTabBadge('notify', count)
    // }

    BX.onCustomEvent(window, 'onImUpdateCounterNotify', [this.notifyCount, 'NOTIFY']);

    if (send) {
        BX.localStorage.set('nunc2', {'unread': this.unreadNotify, 'notifyCount': this.notifyCount}, 5);
    }
};

BX.MessengerNotify.prototype.updateNotifyNextCount = function(count, send)
{
    send = send != false;

    var notifyCountLabel = '';
    if (count > 99)
    {
        notifyCountLabel = '99+';
    }
    else if (count > 0)
    {
        notifyCountLabel = count;
    }

    if (this.panelButtonNotifyCount)
    {
        this.panelButtonNotifyCount.innerHTML = notifyCountLabel;
        this.adjustPosition({"resize": true, "timeout": 500});
    }

    if (this.messengerNotifyButtonCount)
    {
        this.messengerNotifyButtonCount.innerHTML = parseInt(notifyCountLabel) > 0 ? '<span class="bx-messenger-cl-count-digit">' + notifyCountLabel + '</span>' : '';
    }

    if (BX.MessengerCommon.isPage())
    {
        BX.MessengerWindow.setTabBadge('notify', count)
    }

    this.notifyCount = parseInt(count);

    BX.onCustomEvent(window, 'onImUpdateCounterNotify', [this.notifyCount, 'NOTIFY']);

    if (send)
    {
        BX.localStorage.set('nund', {'notifyCount': this.notifyCount}, 5);
    }
};