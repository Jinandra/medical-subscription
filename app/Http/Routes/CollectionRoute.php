<?php



Route::group(['middleware' => ['auth','user.status'], 'prefix' => 'collection', 'as' => 'collection::'], function () {
  $CONTROLLER = 'CollectionController';

  Route::get("/",     "${CONTROLLER}@index");
  Route::post("/",    "${CONTROLLER}@store");
  Route::patch("/",   "${CONTROLLER}@patchCollections");
  Route::delete("/",  "${CONTROLLER}@deleteCollections");

  Route::get("/exists",    "${CONTROLLER}@isExist");
  Route::get("/pin",       "${CONTROLLER}@pinCollection");
  Route::put("/gridview",  "${CONTROLLER}@saveGridLayout");
  Route::get("/bundle/{id}/{action}", "${CONTROLLER}@bundle");
  Route::post("/bundle",   "${CONTROLLER}@bulkBundle");

  Route::get("/{id}/preview",  "${CONTROLLER}@preview");
  Route::get("/{id}/content",  "${CONTROLLER}@content");
  Route::patch("/{id}",        "${CONTROLLER}@patchSingle");
});


Route::group(['middleware' => ['auth','user.status'], 'prefix' => 'folder', 'as' => 'folder::'], function () {
  $CONTROLLER = 'CollectionController';

  Route::get('/{id}', ['as' => 'show', 'uses' => "${CONTROLLER}@folderDetail"]);
  Route::post('/{id}', ['uses' => "${CONTROLLER}@copyFolder"]);
});
