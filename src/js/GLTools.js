class GLTools {
    iframeSearch = "iframe.side-panel-iframe";
    containerShowEvents;
    dom;
    constructor() {
        this.dom = this.getDom();
    }

    getSidePanels() {
        let sidePanels = document.querySelectorAll(this.iframeSearch);
        if(sidePanels.length !== 0) {
            return sidePanels;
        }
        return false;
    }

    getSidePanelMain() {
        let sidePanels = this.getSidePanels();
        if(sidePanels[0] !== 'undefined') {
            return sidePanels[0];
        }
        return false;
    }

    getSidePanelLast() {
        let sidePanels = this.getSidePanels();
        let len = sidePanels.length - 1;
        if(sidePanels[len] !== 'undefined') {
            return sidePanels[len];
        }
        return false;
    }

    getSidePanelId(iframe) {
        let panel = iframe;
        if(!panel)
            return false;

        return panel.id;
    }

    getDom() {
        let panelId = this.getSidePanelId(this.getSidePanelLast())
        if(!panelId)
            return document;
        let iframe = document.getElementById(panelId);
        return (iframe.contentDocument) ? iframe.contentDocument : iframe.contentWindow.document;
    }

    showBXEvents(BX) {
        var originalBxOnCustomEvent = BX.onCustomEvent;
        var hardcoreBXOnEventFrontLog = {
            events: {},
        };

        BX.onCustomEvent = function(objOrEvent, eventIHope, eventParams, secureParams) {
            if (!objOrEvent) {
                objOrEvent = null;
            }

            if (!eventIHope) {
                eventIHope = null;
            }

            if (!eventParams) {
                eventParams = null;
            }

            if (!secureParams) {
                secureParams = null;
            }

            let info = {};
            let realEvent, realObj;

            if (typeof objOrEvent === 'string') {
                realEvent = objOrEvent;
            } else if (typeof eventIHope === 'string') {
                realEvent = eventIHope;
            }

            if (typeof objOrEvent === 'object') {
                realObj = objOrEvent;
            } else if (typeof eventIHope === 'object') {
                realObj = eventIHope;
            } else if (typeof eventParams === 'object') {
                realObj = eventParams;
            }

            let err = new Error();
            info.trace = err.stack;
            info.event = realEvent;
            info.obj = realObj;
            info.params = eventParams;
            info.arguments = arguments;

            hardcoreBXFrontPrintOnEvent(info, true);

            let eventNameList = realEvent.split(' ');
            eventNameList.forEach(function (evt) {
                if (!hardcoreBXOnEventFrontLog.events[evt]) {
                    hardcoreBXOnEventFrontLog.events[evt] = [];
                }
                hardcoreBXOnEventFrontLog.events[evt].push(info);
            });

            return originalBxOnCustomEvent.call(this, objOrEvent, eventIHope, eventParams, secureParams);
        };

        this.containerShowEvents = function (event) {
            for (let e in hardcoreBXOnEventFrontLog.events) {
                if (
                    !hardcoreBXOnEventFrontLog.events.hasOwnProperty(e)
                    || e !== event
                ) {
                    continue;
                }

                for (let ins = 0; ins < hardcoreBXOnEventFrontLog.events[e].length; ins++) {
                    hardcoreBXFrontPrintOnEvent(hardcoreBXOnEventFrontLog.events[e][ins]);
                }
            }
        };

        var hardcoreBXFrontPrintOnEvent = function (info, live) {

            let localInfo = Object.assign({}, info);

            console.log(
                'BX.on%c%s',
                'background: #fa8544; color: #fff; ' +
                'font-weight: bold; padding: 3px 9px;' +
                'border-radius: 30px 0 0 30px;' +
                'border-left: 7px solid #1d1b57',
                localInfo.event
            );

            if (localInfo.obj) {
                console.log(localInfo.obj);
            }

            console.groupCollapsed('trace');
            if (live) {
                console.trace();
                delete (localInfo.trace);
            } else {
                console.log(localInfo.trace);
            }
            console.groupEnd();

            console.groupCollapsed('info');
            for (let i in localInfo) {
                if (localInfo.hasOwnProperty(i)) {
                    console.log(i + ':%o', localInfo[i]);
                }
            }

            console.groupEnd();
        };
    }
}

GLTools = new GLTools();