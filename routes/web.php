<?php

Route::get('clearcache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE';
});

Route::get('/', ['as'=>'site.home', 'uses'=>'TotalController@total']);
Route::get('/total', ['as'=>'site.home.total', 'uses'=>'TotalController@total']);
Route::get('/group', ['as'=>'site.home.group', 'uses'=>'GroupController@group']);
Route::get('/individual/{competitor_id}', ['as'=>'site.home.individual', 'uses'=>'IndividualController@individual']);

Route::get('/login', ['as'=>'site.login', 'uses'=>'LoginController@index']);
Route::post('/login/authenticate', ['as'=>'site.login.authenticate', 'uses'=>'LoginController@authenticate']);
Route::get('/logout', ['as'=>'site.logout', 'uses'=>'LoginController@logout']);

Route::group(['middleware'=>'auth'], function(){
    Route::get('/judges', ['as'=>'site.judges', 'uses'=>'JudgesController@index']);
    Route::get('/judges/add', ['as'=>'site.judges.add', 'uses'=>'JudgesController@add']);
    Route::post('/judges/save', ['as'=>'site.judges.save', 'uses'=>'JudgesController@save']);
    Route::get('/judges/edit/{id}', ['as'=>'site.judges.edit', 'uses'=>'JudgesController@edit']);
    Route::post('/judges/update/{id}', ['as'=>'site.judges.update', 'uses'=>'JudgesController@update']);
    Route::get('/judges/delete/{id}', ['as'=>'site.judges.delete', 'uses'=>'JudgesController@delete']);
    Route::get('/judges/export', ['as'=>'site.judges.export', 'uses'=>'JudgesController@export']);

    Route::get('/competitors', ['as'=>'site.competitors', 'uses'=>'CompetitorsController@index']);
    Route::get('/competitors/add', ['as'=>'site.competitors.add', 'uses'=>'CompetitorsController@add']);
    Route::post('/competitors/save', ['as'=>'site.competitors.save', 'uses'=>'CompetitorsController@save']);
    Route::get('/competitors/edit/{id}', ['as'=>'site.competitors.edit', 'uses'=>'CompetitorsController@edit']);
    Route::post('/competitors/update/{id}', ['as'=>'site.competitors.update', 'uses'=>'CompetitorsController@update']);
    Route::get('/competitors/delete/{id}', ['as'=>'site.competitors.delete', 'uses'=>'CompetitorsController@delete']);
    Route::get('/competitors/export', ['as'=>'site.competitors.export', 'uses'=>'CompetitorsController@export']);

    Route::get('/announcers', ['as'=>'site.announcers', 'uses'=>'AnnouncersController@index']);
    Route::get('/announcers/add', ['as'=>'site.announcers.add', 'uses'=>'AnnouncersController@add']);
    Route::post('/announcers/save', ['as'=>'site.announcers.save', 'uses'=>'AnnouncersController@save']);
    Route::get('/announcers/edit/{id}', ['as'=>'site.announcers.edit', 'uses'=>'AnnouncersController@edit']);
    Route::post('/announcers/update/{id}', ['as'=>'site.announcers.update', 'uses'=>'AnnouncersController@update']);
    Route::get('/announcers/delete/{id}', ['as'=>'site.announcers.delete', 'uses'=>'AnnouncersController@delete']);
    Route::get('/announcers/export', ['as'=>'site.announcers.export', 'uses'=>'AnnouncersController@export']);

    Route::get('/competition', ['as'=>'site.competition', 'uses'=>'CompetitionController@index']);
    Route::get('/competition/restart', ['as'=>'site.competition.start', 'uses'=>'CompetitionController@restart']);    
    Route::get('/competition/confirmrestart', ['as'=>'site.competition.restart', 'uses'=>'CompetitionController@confirmRestart']);    
    Route::get('/competition/settings', ['as'=>'site.competition.edit', 'uses'=>'CompetitionController@settings']);
    Route::post('/competition/settings/save', ['as'=>'site.competition.settings.save', 'uses'=>'CompetitionController@saveSettings']);
    Route::get('/competition/ordergroups', ['as'=>'site.competition.ordergroups', 'uses'=>'CompetitionController@orderGroups']);
    Route::get('/competition/ordergroups/insertingorder', ['as'=>'site.categories.ordergroups.insertingorder', 'uses'=>'CompetitionController@insertingOrder']);
    Route::get('/competition/ordergroups/randomorder', ['as'=>'site.categories.ordergroups.randomorder', 'uses'=>'CompetitionController@randomOrder']);
    
    Route::get('/categories', ['as'=>'site.categories', 'uses'=>'CategoriesController@index']);
    Route::get('/categories/add', ['as'=>'site.categories.add', 'uses'=>'CategoriesController@add']);
    Route::post('/categories/save', ['as'=>'site.categories.save', 'uses'=>'CategoriesController@save']);
    Route::get('/categories/edit/{id}', ['as'=>'site.categories.edit', 'uses'=>'CategoriesController@edit']);
    Route::post('/categories/update/{id}', ['as'=>'site.categories.update', 'uses'=>'CategoriesController@update']);
    Route::get('/categories/delete/{id}', ['as'=>'site.categories.delete', 'uses'=>'CategoriesController@delete']);
    Route::get('/categories/confirmdelete/{id}', ['as'=>'site.categories.confirmdelete', 'uses'=>'CategoriesController@confirmDelete']);
    Route::get('/categories/export/{id}', ['as'=>'site.categories.export', 'uses'=>'CategoriesController@export']);
    Route::get('/categories/start/{id}', ['as'=>'site.categories.start', 'uses'=>'CategoriesController@start']);    
    Route::get('/categories/confirmstart/{id}', ['as'=>'site.categories.confirmstart', 'uses'=>'CategoriesController@confirmStart']);    
    Route::get('/categories/competitors/{category_id}', ['as'=>'site.categories.competitors', 'uses'=>'CategoriesController@competitors']);
    Route::get('/categories/releasecompetitor/{id}', ['as'=>'site.categories.releasecompetitor', 'uses'=>'CategoriesController@releaseCompetitor']);

    Route::get('/score', ['as'=>'site.score', 'uses'=>'ScoreController@index']);
    Route::post('/score/save', ['as'=>'site.score.save', 'uses'=>'ScoreController@save']);
    
});