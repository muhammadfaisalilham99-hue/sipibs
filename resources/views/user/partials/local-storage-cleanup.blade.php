<script>
    (function () {
        var currentUserId = {!! json_encode((string) (Auth::id() ?? '')) !!};
        if (!currentUserId) return;
        var storedUserId = null;
        try { storedUserId = localStorage.getItem('sipibs_user_id'); } catch (e) {}
        if (storedUserId === currentUserId) return;

        var keys = [
            'sipibsLoanHistory', 'sipibsLoanDecision', 'sipibsLoanRequest',
            'sipibsReturnLoanItems', 'sipibsLoanSubmitted', 'sipibsLoanNotificationDone',
            'sipibsLoanNotification', 'sipibsActiveFine', 'sipibsReturnSubmissions',
            'sipibsStaticReturnedIds', 'sipibsFines', 'sipibs_user_profile',
            'sipibs_profile', 'sipibsUserNotifications', 'sipibsAdminNotifications',
            'sipibsSelectedReturnId'
        ];
        keys.forEach(function (key) {
            try { localStorage.removeItem(key); } catch (e) {}
        });
        var toRemove = [];
        for (var i = 0; i < localStorage.length; i++) {
            var k = localStorage.key(i);
            if (k && k.indexOf('sipibsLoanSubmitted:') === 0) toRemove.push(k);
        }
        toRemove.forEach(function (key) {
            try { localStorage.removeItem(key); } catch (e) {}
        });
        try { localStorage.setItem('sipibs_user_id', currentUserId); } catch (e) {}
    })();
</script>