LG.Util = class
{
    constructor() {

    }

    receive(event, el, handler) {
        if(window.addEventListener) el.addEventListener(event, handler, false);
    }

    send(event, obj) {
        if (document.createEvent) {
            var e = document.createEvent("Events");
            e.initEvent(event, true, false);
        } else if (document.createEventObject()) {
            var e = document.createEventObject();
        } else
            return;

        if (obj.dispatchEvent) obj.dispatchEvent(e);
        else if (obj.fireEvent) obj.fireEvent(event, e);
    }
}