var accessToken = '';

$(document).ready(function() {
    var campaignForm = $('#newFacebookAdCampaign');
    campaignForm.on('submit', function(e) {
        e.preventDefault();

        var campaign_name = $('#campaignName').val();
        var campaign_objective = $('#campignObjective').val();
        var campaign_status = $('#campaignStatus').val();

        var checkFacebookApi = new Promise(
            function (resolve, reject) {
                FB.getLoginStatus(function(response) {
                    if (response.status === 'connected') {
                        var data = [];

                        accessToken = response.authResponse.accessToken;
                        FB.api('/'+accessToken, function(response) {
                            console.log(response);
                        });
                        uid = response.authResponse.userID;

                        data.push({'token':accessToken,'uid':uid});

                        resolve(data);
                    } else {
                        FB.login(function(response) {
                            if (response.authResponse) {
                                var data = [];

                                accessToken = response.authResponse.accessToken;
                                uid = response.authResponse.userID;

                                data.push({'token':accessToken,'uid':uid});

                                resolve(data);
                            } else {
                                reject('User cancelled login or did not fully authorize.');
                            }
                        }, {scope: 'email,user_likes,ads_management,business_management'});
                    }
                });
            }
        ).then(function (fulfilled) {
            $.ajax({
                type: 'post',
                url: ajax_url + 'campaigns/facebook/new',
                beforeSend: function (request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                data: {'name':campaign_name,'objective':campaign_objective,'status':campaign_status,'access_token':fulfilled},
                success: function(data) {
                    console.log(data);
                    if(data.status != 1) {
                        toastr.error(data.message);
                    }
                },
            });
        }).catch(function (error) {
            toastr.error(error.message);
        });
    });
});
