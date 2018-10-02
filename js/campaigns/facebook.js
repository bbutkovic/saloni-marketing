var accessToken = '';

$(document).ready(function() {
    $('#campaignStart').datepicker({
        startDate: new Date()
    });
    $('#campaignEnd').datepicker({
        startDate: new Date()
    });

    $("#campaignAgeRange").ionRangeSlider({
        type: "double",
        grid: true,
        min: 0,
        max: 90,
        from: 0,
        to: 90,
        prefix: trans_age,
        max_postfix: "+"
    });

    var campaignForm = $('#newFacebookAdCampaign');
    campaignForm.on('submit', function(e) {
        e.preventDefault();

        var form = new FormData();
        form.append('name', $('#campaignName').val());
        form.append('objective', $('#campaignObjective').val());
        form.append('status', $('#campaignStatus').val());
        form.append('adset_name', $('#adSetName').val());
        form.append('start', $('#campaignStart').val());
        form.append('end', $('#campaignEnd').val());
        form.append('budget', $('#campaignBudget').val());
        form.append('bid', $('#campaignBids').val());
        form.append('audience_countries', $('#audienceCountries').val());
        form.append('audience_cities', $('#audienceCities').val());
        form.append('audience_age_range', $('#campaignAgeRange').val());
        form.append('audience_interests', $('#audienceInterests').val());
        form.append('audience_gender', $('#campaignGender').val());
        form.append('ad_image', $('#adImage')[0].files[0]);
        form.append('facebook_page_id', $('#facebookPageID').val());
        form.append('ad_title', $('#adTitle').val());
        form.append('ad_message', $('#adMsg').val());
        form.append('redirect_link', $('#redirectLink').val());

        var checkFacebookApi = new Promise(
            function (resolve, reject) {
                FB.getLoginStatus(function(response) {
                    if (response.status === 'connected') {
                        var data = [];

                        accessToken = response.authResponse.accessToken;

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
                        }, {scope: 'email,user_likes,ads_management,business_management,manage_pages'});
                    }
                });
            }
        ).then(function (fulfilled) {
            form.append('access_token', fulfilled[0]['token']);
            form.append('uid', fulfilled[0]['uid']);
            $.ajax({
                type: 'post',
                url: ajax_url + 'campaigns/facebook/new',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                data: form,
                success: function(data) {
                    if(data.status != 1) {
                        toastr.error(data.message);
                    } else {
                        toastr.success(data.message);
                    }
                },
            });
        }).catch(function (error) {
            toastr.error(error.message);
        });
    });
});
