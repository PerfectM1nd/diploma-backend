<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => ['api'], 'namespace' => 'Api\v1'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('me', 'AuthController@fetchMe');
        Route::post('send-dialog-offer', 'WebRTCController@sendDialogOffer');
        Route::post('accept-dialog-offer', 'WebRTCController@acceptDialogOffer');
        Route::post('reject-dialog-offer', 'WebRTCController@rejectDialogOffer');

        Route::post('webrtc-offer', 'WebRTCController@webRtcOffer');
        Route::post('webrtc-answer', 'WebRTCController@webRtcAnswer');
        Route::post('webrtc-candidate', 'WebRTCController@webRtcCandidate');
    });

});
