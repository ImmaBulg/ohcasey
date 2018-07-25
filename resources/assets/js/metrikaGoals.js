window.metrikaGoals = function () {
    return {
        deviceSelected: function (deviceName) {
            return this.getMetrika().reachGoal('CHOOSEPHONE');
        },
        caseySelected: function (caseyName) {
            return this.getMetrika().reachGoal('CHOOSECASE');
        },
        backgroundDeviceSelected: function (bgName) {
            return this.getMetrika().oneTimeReachGoal('BGCLICK');
        },
        getMetrika: function () {
            yaCounter32242774.oneTimeReachGoal = function (goalName) {
                var cookieName = 'ya_counter_' + goalName;

                if (!Cookies.get(cookieName)) {
                    this.reachGoal(goalName);
                    document.cookie = cookieName + "=1; expires=0; path=/";
                }
            };
            return yaCounter32242774;
        },
        shopCartSubmitted: function (callback) {
            var i = 0;

            function waitForLoading() {
                if (window.yaCounter32242774) {
                    if (window.APP_ENV == 'production') {
                        if (!callback) {
                            callback = function () {
                            };
                        }
                        return window.yaCounter32242774.reachGoal('SITECONVERSION', callback);
                    }
                    console.log('GOAL REACHED')
                } else {
                    setTimeout(waitForLoading, 1000); // callback
                }
                console.log(i);
                i++;
            }

            waitForLoading();
        },
    }
}();